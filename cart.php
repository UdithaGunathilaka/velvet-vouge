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
  
  // delete products from cart
  if (isset($_POST["deleteItem"])) {
    $cartID = $_POST["cartID"];
    $cartID = filter_var($cartID, FILTER_SANITIZE_STRING);

    $verifyDeleteItem = $conn->prepare("SELECT * FROM `cart` WHERE id = ?");
    $verifyDeleteItem->execute([$cartID]);

    if ($verifyDeleteItem->rowCount() > 0) {
      $deleteItem = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
      $deleteItem->execute([$cartID]);

      $successMsg[] = "cart item delete successfully";
      echo "cart item delete successfully";
    } else {
      $warningMsg[] = "cart item already deleted";
      echo "cart item already deleted";
    }
  }

  // update cart
  if (isset($_POST["updateCart"])) {
    $cartID = $_POST["cartID"];
    $cartID = filter_var($cartID, FILTER_SANITIZE_STRING);
    $qty = $_POST["qty"];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    $updateQty = $conn->prepare("UPDATE `cart` SET qty = ? WHERE id = ?");
    $updateQty->execute([$qty, $cartID]);

    $successMsg[] = "cart quantity updated successfully";
    echo "cart quantity updated successfully";
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <title>cart page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <h1 class="title">your cart</h1>
    <section class="cart-products">
      <div class="box-container">
        <?php
          $grandTotal = 0;
          $selectCart = $conn->prepare("SELECT * FROM `cart` WHERE userID = ?");
          $selectCart->execute([$userID]);

          if ($selectCart->rowCount() > 0) {
            while($fetchCart = $selectCart->fetch(PDO::FETCH_ASSOC)) {
              $selectProducts = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
              $selectProducts->execute([$fetchCart["productID"]]);

              if ($selectProducts->rowCount() > 0) {
                $fetchProducts = $selectProducts->fetch(PDO::FETCH_ASSOC);

         ?>
        <form action="" method="post">
          <input type="hidden" name="cartID" value="<?=$fetchCart["id"]; ?>">

          <div class="product-img">
            <img src="img/products/<?=$fetchProducts["image"]; ?>.png" class="img">
          </div>

          <div class="product-detail">
            <div class="flex">
              <h3 class="name"><?=$fetchProducts["name"]; ?></h3>
              <p class="price">$<?=$fetchProducts["price"]; ?>/-</p>
            </div>

            <div class="flex">
              <input type="number" name="qty" required min="1" value="<?=$fetchCart["qty"]; ?>" max="99" maxlength="2" class="qty">
              <button type="submit" name="updateCart" class="bx bxs-edit edit-btn"></button>
            </div>

            <div class="flex">
              <p class="sub-total">sub total:  <span>$<?=$subTotal = ($fetchCart["qty"] * $fetchCart["price"]);?></span></p>
            </div>

            <button type="submit" name="deleteItem" class="btn dlt-btn" onclick="return confirm('delete this item')">
              delete
            </button>
          </div>
        </form>
        <?php
              $grandTotal += $subTotal;
              } else {
                echo "<p class='empty'>product was not found</p>";
              }
            }
          } else {
            echo "<p class='empty'>no products added yet</p>";
          }
        ?>
      </div>
      <?php
        if ($grandTotal !== 0) {
      ?>
      <div class="cart-summary">
        <p class="title">order summary</p>

        <div class="flex">
          <p>subtotal</p>
          <span>$<?=$grandTotal; ?></span>
        </div>

        <div class="flex">
          <p>discount</p>
          <span>$-0</span>
        </div>

        <div class="flex">
          <p>delivery fee</p>
          <span>$0</span>
        </div>

        <div class="flex">
          <p>total</p>
          <span>$<?=$grandTotal; ?></span>
        </div>

        <div class="buttons">
          <a href="checkout.php" class="btn check-btn">go to checkouts
            <i class="fa-regular fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php
        }
      ?>
    </section>

    <?php include("components/footer.php"); ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>