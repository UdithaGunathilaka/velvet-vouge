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

  $productID = $_GET["pid"];

  $selectProduct = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
  $selectProduct->execute([$productID]);
  $product = $selectProduct->fetch(PDO::FETCH_ASSOC);

  // delete
  if (isset($_POST["delete"])) {
     $deleteProduct = $conn->prepare("DELETE FROM `products` WHERE id = ?");
     $deleteProduct->execute([$productID]);

     if ($deleteProduct->rowCount() > 0) {
      echo "Product deleted successfully!";
     } else {
      echo "No changes were made to the product.";
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">

  <title>delete product page</title>
</head>
<body>
  <?php include("components/adminHeader.php"); ?>

  <div class="main">
    <h1 class="title">manage product</h1>
    <section class="product">
      <div class="col-1">
        <img src="img/products/<?= $product["image"]; ?>.png" class="product-img">
      </div>

      <div class="col-2">
        <p><?= $product["name"]; ?></parent>
        <p><span>price: $</span><?= $product["price"]; ?></p>
        <p><span>description:</span> <?= $product["productDetail"]; ?></p>

        <form method="post" action="" class="row-1">
          <button type="button" onclick="location.href='updateProduct.php?pid=<?= $productID; ?>'" class="btn update-btn">Update</button>
          <button type="submit" name="delete" onclick="return confirm('delete this item')" class="btn dlt-btn">Delete</button>
        </form>
      </div>
    </section>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>