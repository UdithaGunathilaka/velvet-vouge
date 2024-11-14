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
  <title>contact us page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <div class="form-container">
      <form method="post">
        <h1 class="title">leave a message</h1>

        <div class="input-field">
          <p>your name <sup>*</sup></p>
          <input type="text" name="name" placeholder="enter your name">
        </div>

        <div class="input-field">
          <p>your email <sup>*</sup></p>
          <input type="email" name="email" placeholder="enter your email">
        </div>

        <div class="input-field">
          <p>your number <sup>*</sup></p>
          <input type="text" name="number" placeholder="enter your contact number">
        </div>

        <div class="input-field">
          <p>your message <sup>*</sup></p>
          <textarea name="message"></textarea>
        </div>

        <button type="submit" name="submit" placeholder="send message" class="btn send-btn">send message</button>
      </form>
    </div>

    <?php include("components/footer.php"); ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>