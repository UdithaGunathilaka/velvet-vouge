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
  <title>home page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <img src="img/star.png" class="star">
    <img src="img/star.png" class="star">
    
    <section class="home-section">
      <div class="detail">
        <h1>Find clothes<br> that matches<br> your style</h1>
        <p>Browse through our diverse range of meticulosly carfted garments, <br> designed to bring out your individually and cater to your sense of style</p>
        <a href="viewProducts.php" class="btn">shop now</a>
      </div>
    </section>

    <section class="brand">
      <div class="box">
        <img src="img/brands/brand-1.png">
      </div>

      <div class="box">
        <img src="img/brands/brand-2.png">
      </div>

      <div class="box">
        <img src="img/brands/brand-3.png">
      </div>

      <div class="box">
        <img src="img/brands/brand-4.png">
      </div>

      <div class="box">
        <img src="img/brands/brand-5.png">
      </div>
    </section>

    <section class="product-sec">
      <h2>new arrival</h2>

      <div class="box-container">
        <div class="product-card">
          <img src="img/products/product-1.png">

          <div class="produt-detail">
            <p>t-shirt with tape details</p>
            <div class="product-rate">
              <img src="img/rate/star-4.50.png">
              <p>4.5/5</p>
            </div>
            <p>$120</p>
          </div>
        </div>

        <div class="product-card">
          <img src="img/products/product-4.png">

          <div class="produt-detail">
            <p>skinny fit jeans</p>
            <div class="product-rate">
              <img src="img/rate/star-3.50.png">
              <p>3.5/5</p>
            </div>
            <p>$240</p>
          </div>
        </div>

        <div class="product-card">
          <img src="img/products/product-5.png">

          <div class="produt-detail">
            <p>checkered shirt</p>
            <div class="product-rate">
              <img src="img/rate/star-4.50.png">
              <p>4.5/5</p>
            </div>
            <p>$180</p>
          </div>
        </div>

        <div class="product-card">
          <img src="img/products/product-6.png">

          <div class="produt-detail">
            <p>sleeve striped t-shirt</p>
            <div class="product-rate">
              <img src="img/rate/star-4.50.png">
              <p>4.5/5</p>
            </div>
            <p>$130</p>
          </div>
        </div>
      </div>

      <a href="viewProducts.php">view all</a>

      <hr>
    </section>

    <section class="product-sec">
      <h2>top seling</h2>

      <div class="box-container">
        <div class="product-card">
          <img src="img/products/product-7.png">

          <div class="produt-detail">
            <p>vertical stripes shirt</p>
            <div class="product-rate">
              <img src="img/rate/star-4.50.png">
              <p>4.5/5</p>
            </div>
            <p>$200</p>
          </div>
        </div>

        <div class="product-card">
          <img src="img/products/product-8.png">

          <div class="produt-detail">
            <p>courge graphic shirt</p>
            <div class="product-rate">
              <img src="img/rate/star-3.50.png">
              <p>3.5/5</p>
            </div>
            <p>$145</p>
          </div>
        </div>

        <div class="product-card">
          <img src="img/products/product-9.png">

          <div class="produt-detail">
            <p>loose fit bermuda shorts</p>
            <div class="product-rate">
              <img src="img/rate/star-4.50.png">
              <p>4.5/5</p>
            </div>
            <p>$80</p>
          </div>
        </div>

        <div class="product-card">
          <img src="img/products/product-10.png">

          <div class="produt-detail">
            <p>faded skinny jeans</p>
            <div class="product-rate">
              <img src="img/rate/star-3.50.png">
              <p>3.5/5</p>
            </div>
            <p>$210</p>
          </div>
        </div>
      </div>

      <a href="viewProducts.php">view all</a>
    </section>

    <section class="category">
      <h2>browse by dressstyle</h2>
      <div class="box-container">
        <div class="row">
          <img src="img/banner/banner-1.png">
          <img src="img/banner/banner-2.png">
        </div>

        <div class="row">
          <img src="img/banner/banner-3.png">
          <img src="img/banner/banner-4.png">
        </div>
      </div>
    </section>

    <?php include("components/footer.php"); ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>