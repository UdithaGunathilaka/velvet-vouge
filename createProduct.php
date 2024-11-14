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

  // create
  $filename = "";

  if (isset($_POST["create"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $rate = $_POST["rate"];
    $price = $_POST["price"];
    $image = $_POST["image"];
    $description = $_POST["description"];

    $createProduct = $conn->prepare("INSERT INTO `products` VALUES(?, ?, ?, ?, ?, ?");
    $createProduct->execute([$id, $name, $rate, $price, $image, $description]);

    if ($createProduct->rowCount() > 0) {
      echo "Product reated successfully!";
     } else {
      echo "No products were created.";
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

  <title>create product page</title>
</head>
<body>
  <?php include("components/adminHeader.php"); ?>

  <div class="main">
    <h1 class="title">create product</h1>
    <section class="manage-product-container">
      <form method="post" action="">
        <div>
          <label for="id" style="text-transform: uppercase;">ID</label>
          <input type="number" name="id" required>
        </div>

        <div>
          <label for="name">name</label>
          <input type="text" name="name" required>
        </div>

        <div>
          <label for="rate">rate</label>
          <input type="number" name="rate" required>
        </div>

        <div>
          <label for="price">price</label>
          <input type="number" name="price" required>
        </div>

        <div>
          <label for="image">image</label>
          <input type="file" name="image" id="image" accept="image/*">
        </div>

        <div>
          <label for="description">description</label>
          <textarea name="description" required></textarea>
        </div>
 
        <div>
          <button type="submit" name="create" onclick="return confirm('create item')" class="btn create-btn">create</button>
        </div>
      </form>
    </section>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>