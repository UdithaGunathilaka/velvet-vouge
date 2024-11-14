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
  <title>about us page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <h1 class="title">what people say about us</h1>
    <div class="testimonial-container">
      <div class="container">
        <div class="testimonial-item active">
          <img src="img/peoples/people-1.png">
          <h1 class="name">sara smith</h1>
          <p class="opinion">"This t-shirt is a must-have for anyone who appreciates good design. The minimalistic yet stylish pattern caught my eye, and the fit is perfect. I can see the designer's touch in every aspect of this shirt."</p>
        </div>

        <div class="testimonial-item">
          <img src="img/peoples/people-2.png">
          <h1 class="name">john doe</h1>
          <p class="opinion">"This t-shirt is a fusion of comfort and creativity. The fabric is soft, and the design speaks volumes about the designer's skill. It's like wearing a piece of art that reflects my passion for both design and fashion."</p>
        </div>

        <div class="testimonial-item">
          <img src="img/peoples/people-3.png">
          <h1 class="name">dave gray</h1>
          <p class="opinion">"I'm not just wearing a t-shirt; I'm wearing a piece of design philosophy. The intricate details and thoughtful layout of the design make this shirt a conversation starter."</p>
        </div>

        <div class="left-arrow">
          <i class="bx bxs-left-arrow-alt"></i>
        </div>
        
        <div class="right-arrow">
          <i class="bx bxs-right-arrow-alt"></i>
        </div>
      </div>
    </div>
  
    <?php include("components/footer.php"); ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>