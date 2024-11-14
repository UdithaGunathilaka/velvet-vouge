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

  if (isset($_GET["getID"])) {
    $getID = $_GET["getID"];
  } else {
    $getID = "";
    header("location:order.php");
  }

  if (isset($_POST["cancel"])) {
    $updateOrder = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
    $updateOrder->execute(["canceled", $getID]);
    header("location: order.php");
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">

  <title>order detail page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <h1 class="title">order details</h1>
    <section class="order-detail">
      <?php
        $grandTotal = 0;
        $selectOrder = $conn->prepare("SELECT * FROM `orders` WHERE id = ? LIMIT 1");
        $selectOrder->execute([$getID]);

        if ($selectOrder->rowCount() > 0) {
          while($fetchOrder = $selectOrder->fetch(PDO::FETCH_ASSOC)) {
            $selectProduct = $conn->prepare("SELECT * FROM `products` WHERE id = ?LIMIT 1");
            $selectProduct->execute([$fetchOrder["productID"]]);

            if ($selectProduct->rowCount() > 0) {
              while($fetchProduct = $selectProduct->fetch(PDO::FETCH_ASSOC)) {
                $subTotal = ($fetchOrder["price"] * $fetchOrder["qty"]);
                $grandTotal += $subTotal;
      ?>
      <div class="box">
        <img src="img/products/<?=$fetchProduct["image"]; ?>.png" class="img order-product-img">

        <div class="col">
          <p class="title-2">
            <i class="bi bi-calendar-fill"><?=$fetchOrder["date"]; ?></i>
          </p>

          <h3 class="name"><?=$fetchProduct["name"]; ?></h3>

          <p class="price"><?=$fetchProduct["price"];?> X <?= $fetchOrder["qty"]; ?></p>

          <p class="grand-total">total amount payable: $<?=($fetchProduct["price"] * $fetchOrder["qty"]); ?>/-</p>
        </div>

        <div class="col">
          <p class="title-2">billing address</p>

          <p class="user">
            <i class="bi bi-person-bounding-box"></i>
            <?=$fetchOrder["name"]; ?>
          </p>

          <p class="user">
            <i class="bi bi-phone"></i>
            <?=$fetchOrder["number"]; ?>
          </p>

          <p class="user">
            <i class="bi bi-envelope"></i>
            <?=$fetchOrder["email"]; ?>
          </p>

          <p class="user">
            <i class="bi bi-pin-map-fill"></i>
            <?=$fetchOrder["address"]; ?>
          </p>
        </div>

        <div class="col">
          <p class="title-2">status</p>

          <p class="status" style="color: <?php if ($fetchOrder["status"] === 'delivered') {echo 'green';}else if($fetchOrder["status"] === 'canceled') {echo 'red';}else {echo 'orange';}?>;"><?=$fetchOrder["status"]?></p>

          <?php
            if ($fetchOrder["status"] === "canceled") {
              ?>
              <a href="checkout.php?getID=<?=$fetchProduct["id"]; ?>" class="btn">order again</a>
              <?php
            } else {
              ?>
              <form action="" method="post">
                <button type="submit" name="cancel" class="btn cancel-btn" onclick="return confirm('do you wnat to cancel this order')">cancel order</button>
              </form>
              <?php
            }
          ?>
        </div>
      </div>
      <?php
              }
            } else {
              echo "<p class='product not found</p>";
            }
          }
        } else {
          echo "<p class='no order found</p>";
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