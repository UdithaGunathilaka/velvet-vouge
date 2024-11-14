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

  <title>dash board page</title>
</head>
<body>
  <?php include("components/adminHeader.php"); ?>

  <div class="main">
    <h1 class="title">manage products</h1>
    <section class="products">
      <?php
        $selectProducts = $conn->prepare("SELECT * FROM `products`");
        $selectProducts->execute();

        if ($selectProducts->rowCount() > 0) {
          while($fetchProduct = $selectProducts->fetch(PDO::FETCH_ASSOC)) {

      ?>
      <a href="crudProduct.php?pid=<?=$fetchProduct['id']?>" style="color: #555;" class="product-box">
        <img src="img/products/<?=$fetchProduct["image"]; ?>.png" class="product-img">
        <div class="row">
          <h3 class="name"><?=$fetchProduct["name"]; ?></h3>
          <p class="price">price: $<?=$fetchProduct["price"]; ?></p>
        </div>
      </a>
      <?php
          }
        } else {
          echo "<p class='empty'>no products added yet</p>";
        }
      ?>
    </section>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>