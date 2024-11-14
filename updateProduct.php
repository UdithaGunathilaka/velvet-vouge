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

  // update
  if (isset($_POST["update"])) {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];

     $updateProduct = $conn->prepare("UPDATE `products` SET name = ?, price = ?, productDetail = ? WHERE id = ?");
     $updateProduct->execute([$name, $price, $description, $productID]);

     if ($updateProduct->rowCount() > 0) {
      echo "Product updated successfully!";
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

  <title>update product page</title>
</head>
<body>
  <?php include("components/adminHeader.php"); ?>

  <div class="main">
    <h1 class="title">update product</h1>
    <section class="manage-product-container">
      <form method="post" action="">
        <div>
          <img src="img/products/<?= $product["image"]; ?>.png">
        </div>

        <div>
          <label for="name">product name</label>
          <input type="text" name="name" value="<?= $product["name"]; ?>" required>

          <label for="price">price</label>
          <input type="number" name="price" value="<?= $product["price"]; ?>" required>

          <label for="description">product detail</label>
          <textarea name="description" required><?= $product["productDetail"]; ?></textarea>

          <button type="submit" name="update" onclick="return confirm('save changes')" class="btn save-btn">save changes</button>
        </div>

      </form>
    </section>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>