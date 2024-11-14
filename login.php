<?php
  include("components/db.php");
  session_start();

  if (isset($_SESSION["userID"])) {
    $userID = $_SESSION["userID"];
  } else {
    $userID = "";
  }

  //login user
  if (isset($_POST["submit"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $pw = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

    $selectUser = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
    $selectUser->execute([$email, $pw]);
    $row = $selectUser->fetch(PDO::FETCH_ASSOC);



    if ($selectUser->rowCount() > 0) {
      $_SESSION["userID"] = $row["id"];
      $_SESSION["userName"] = $row["name"];
      $_SESSION["userEmail"] = $row["email"];

      if ($row["isAdmin"] ===  "yes") {
        header("location: dashboard.php");
      } else {
        header("location: home.php");
      }
    } else {
      $message[] = "incorrect username or password";
      echo "incorrect username or password";
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
  <title>login page</title>
</head>
<body>
  <div class="main">
    <section class="form-container">
      <h1 class="title">login now</h1>

      <form action="" method="post">
        <div class="input-field">
          <p>your email <sup>*</sup></p>
          <input type="email" name="email" required placeholder="enter your email" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>  

        <div class="input-field">
          <p>your password <sup>*</sup></p>
          <input type="password" name="password" required placeholder="enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>

        <input type="submit" name="submit" value="login now" class="btn login-btn">
        <p class="acc">do not have an account?
          <a href="register.php">register now</a>
        </p>
      </form>
    </section>
  </div>
</body>
</html>