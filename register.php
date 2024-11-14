<?php
  include("components/db.php");
  session_start();

  if (isset($_SESSION["userID"])) {
    $userID = $_SESSION["userID"];
  } else {
    $userID = "";
  }

  //register user
  if (isset($_POST["submit"])) {
    $id = uniqueID();
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $pw = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $cpw = filter_var($_POST["cpassword"], FILTER_SANITIZE_STRING);

    $selectUser = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $selectUser->execute([$email]);
    $row = $selectUser->fetch(PDO::FETCH_ASSOC);

    if ($selectUser-> rowCount() > 0) {
      $message[] = "email already exist";
      echo "email already exist";
    } else {
      if ($pw !== $cpw) {
        $message[] = "password do not match, confirm your password";
        echo "password do not match, confirm your password";
      } else {
        $insertUser = $conn->prepare("INSERT INTO `users`(id, name, email, password) VALUES(?,?,?,?)");
        $insertUser->execute([$id, $name, $email, $pw]);
        
        header("location: login.php");

        $selectUser = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
        $selectUser->execute([$email, $pw]);
        $row = $selectUser->fetch(PDO::FETCH_ASSOC);

        if ($selectUser->rowCount() > 0) {
          $_SESSION["userID"] = $row["id"];
          $_SESSION["userName"] = $row["name"];
          $_SESSION["userEmail"] = $row["email"];
        }
      }
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
  <title>register page</title>
</head>
<body>
  <div class="main">
    <section class="form-container">
      <h1 class="title">register now</h1>

      <form action="" method="post">
        <div class="input-field">
          <p>your name <sup>*</sup></p>
          <input type="text" name="name" required placeholder="enter your name" maxlength="50">
        </div>

        <div class="input-field">
          <p>your email <sup>*</sup></p>
          <input type="email" name="email" required placeholder="enter your email" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>  

        <div class="input-field">
          <p>your password <sup>*</sup></p>
          <input type="password" name="password" required placeholder="enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>

        <div class="input-field">
          <p>confirm password <sup>*</sup></p>
          <input type="password" name="cpassword" required placeholder="re-enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>

        <input type="submit" name="submit" value="register now" class="btn reg-btn">
        <p class="acc">already have an account?
          <a href="login.php">login now</a>
        </p>
      </form>
    </section>
  </div>
</body>
</html>