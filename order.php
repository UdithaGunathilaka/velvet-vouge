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

  <title>orders page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <h1 class="title">my orders</h1>
    <section class="products">
      <div class="box-container">
        <?php
          $selecctOrders = $conn->prepare("SELECT * FROM `orders` WHERE userID = ? ORDER BY date DESC");
          $selecctOrders->execute([$userID]);

          if ($selecctOrders->rowCount() > 0) {
            while($fetchOrder = $selecctOrders->fetch(PDO::FETCH_ASSOC)) {
              $selectProducts = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
              $selectProducts->execute([$fetchOrder["productID"]]);

              if ($selectProducts->rowCount() > 0) {
                while($fetchProduct = $selectProducts->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
          <a href="viewOrder.php?getID=<?=$fetchOrder['id']?>" style="color: #555;" class="inner-box">
            <p class="date">
              <i class="bi bi-calendar-fill"></i>
              <span><?=$fetchOrder["date"]; ?></span>
            </p>
            <img src="img/products/<?=$fetchProduct["image"]; ?>.png" class="product-img">
            <div class="row">
              <h3 class="name"><?=$fetchProduct["name"]; ?></h3>
              <p class="price">price: $<?=$fetchOrder["price"] * $fetchOrder["qty"]; ?></p>
              <p class="status" style="color: <?php if ($fetchOrder["status"] === 'delivered') {echo 'green';}else if($fetchOrder["status"] === 'canceled') {echo 'red';}else {echo 'orange';}?>;"><?=$fetchOrder["status"]?></p>
            </div>
          </a>
        </div>
        <?php
                }
              }
            }
          } else {
            echo "<p class='empty'>no orders takes place yet!</p>";
          }
        ?>
      </div>
    </section>

    <?php include("components/footer.php"); ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>