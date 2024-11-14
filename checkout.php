<?php
  include("components/db.php");
  session_start();

  if (isset($_SESSION["userID"])) {
    $userID = $_SESSION["userID"];
  } else {
    $userID = "";
  }

  if (isset($_POST["logout"])) {
    session_destroy();
    header("location: login.php");
  }

  if (isset($_POST["placeOrder"])) {
      $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
      $number = filter_var($_POST["number"], FILTER_SANITIZE_STRING);
      $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
      $address = $_POST["flat"] . ', ' . $_POST["street"] . ', ' . $_POST["city"] . ', ' . $_POST["country"] . ', ' . $_POST["pincode"];
      $address = filter_var($address, FILTER_SANITIZE_STRING);
      $method = filter_var($_POST["method"], FILTER_SANITIZE_STRING);

      $verifyCart = $conn->prepare("SELECT * FROM `cart` WHERE userID = ?");
      $verifyCart->execute([$userID]);

      if (isset($_GET["getID"])) {
        $getProduct = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
        $getProduct->execute([$_GET["getID"]]);

        if ($getProduct->rowCount() > 0) {
          while($fetchP = $getProduct->fetch(PDO::FETCH_ASSOC)) {
            $insertOrder = $conn->prepare("INSERT INTO `orders` (id, userID, name, number, email, address, method, productID, price, qty) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insertOrder->execute([uniqueID(), $userID, $name, $number, $email, $address, $method, $fetchP["productID"], $fetchP["price"], $fetchP["qty"], /*1*/]);
            header("location: order.php");
          }
        } else {
          $warningMsg[] = "something went wrong";
          echo "something went wrong";
        }
      } else if ($verifyCart->rowCount() > 0) {
        while($fCart = $verifyCart->fetch(PDO::FETCH_ASSOC)) {
          $insertOrder = $conn->prepare("INSERT INTO `orders` (id, userID, name, number, email, address, method, productID, price, qty) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $insertOrder->execute([uniqueID(), $userID, $name, $number, $email, $address, $method, $fCart["productID"], $fCart["price"], $fCart["qty"], /*1*/]);
          header("location: order.php");
        }
        if ($insertOrder) {
          $deleteOrder = $conn->prepare("DELETE FROM `cart` WHERE userID = ?");
          $deleteOrder->execute([$userID]);
          header("location: order.php");
        }
      } else {
        $warningMsg[] = "something went wrong";
        echo "something went wrong";
      }
  }
?>

<style type="text/css">
  <?php include("style.css"); ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <title>checkout page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <h1 class="title">your checkouts</h1>
    <section class="checkout-container">
      <div class="checkout-summary">
        <?php
          $grandTotal = 0;

          if (isset($_GET["getID"])) {
            $selectGet = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
            $selectGet->execute([$_GET["getID"]]);

            while ($fetchGet = $selectGet->fetch(PDO::FETCH_ASSOC)) {
              $subTotal = $fetchGet["price"];
              $grandTotal += $subTotal;
        ?>
          <div class="flex">
            <div class="product-img">
              <img src="img/products/<?=$fetchGet["image"]; ?>.png" class="img">
            </div>
            <div class="product-details">
              <h3><?=$fetchGet["name"]; ?></h3>
              <p>$<?=$fetchGet["price"]; ?>/-</p>
            </div>
          </div>
        <?php
            }
          } else {
            $selectCart = $conn->prepare("SELECT * FROM `cart` WHERE userID = ?");
            $selectCart->execute([$userID]);

            if ($selectCart->rowCount() > 0) {
              while ($fetchCart = $selectCart->fetch(PDO::FETCH_ASSOC)) {
                $selectProducts = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                $selectProducts->execute([$fetchCart["productID"]]);
                $fetchProduct = $selectProducts->fetch(PDO::FETCH_ASSOC);
                $subTotal = ($fetchCart["qty"] * $fetchProduct["price"]);
                $grandTotal += $subTotal;
        ?>
          <div class="flex">
            <div class="product-img">
              <img src="img/products/<?=$fetchProduct["image"]; ?>.png" class="product-img-checkout">
            </div>
            <div class="product-details">
              <h3><?=$fetchProduct["name"]; ?></h3>
              <p><?=$fetchProduct["price"]; ?> X <?=$fetchCart["qty"]; ?></p>
            </div>
          </div>
        <?php
              }
            } else {
              echo "<p>your cart is empty!</p>";
            }
          }
        ?>
        <div class="grand-total">
          <span>total amount payable:</span> $<?=$grandTotal?>
        </div>
      </div>

      <div class="payment-details">
        <form action="" method="post">
          <div class="flex">
            <div class="box">
              <div class="input-field">
                <p>your name <span>*</span></p>
                <input type="text" name="name" required maxlength="50" placeholder="enter your name" class="input">
              </div>

              <div class="input-field">
                <p>your number <span>*</span></p>
                <input type="number" name="number" required maxlength="15" placeholder="enter your number" class="input">
              </div>

              <div class="input-field">
                <p>your email <span>*</span></p>
                <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="input">
              </div>

              <div class="input-field">
                <p>payment method <span>*</span></p>
                <select name="method" class="input">
                  <option value="cashOnDelivery">cash on delivery</option>
                  <option value="creditDebit">credit or debit card</option>
                  <option value="paypal">paypal</option>
                </select>
              </div>
            </div>

            <div class="box">
              <div class="input-field">
                <p>address line 01 <span>*</span></p>
                <input type="text" name="flat" required maxlength="50" placeholder="e.g flat & building number" class="input">
              </div>

              <div class="input-field">
                <p>address line 02 <span>*</span></p>
                <input type="text" name="street" required maxlength="50" placeholder="e.g street name" class="input">
              </div>

              <div class="input-field">
                <p>city name <span>*</span></p>
                <input type="text" name="city" required maxlength="50" placeholder="enter your city name" class="input">
              </div>

              <div class="input-field">
                <p>counrty name <span>*</span></p>
                <input type="text" name="country" required maxlength="50" placeholder="enter your country name" class="input">
              </div>

              <div class="input-field">
                <p>pincode <span>*</span></p>
                <input type="text" name="pincode" required maxlength="6" min="0" max="999999" placeholder="001122" class="input">
              </div>
            </div>
          </div>

          <button type="submit" name="placeOrder" class="btn order-btn">place order</button>
        </form>
      </div>
    </section>

    <?php include("components/footer.php"); ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>