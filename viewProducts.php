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
  
  // adding products to the wishlist
  if (isset($_POST["addToWishlist"])) {
    $id = uniqid();
    $productID = $_POST["productID"];

    $verifyWishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE userID = ? AND productID = ?");
    $verifyWishlist->execute([$userID, $productID]);

    $cartNum = $conn->prepare("SELECT * FROM `cart` WHERE userID = ? AND productID = ?");
    $cartNum->execute([$userID, $productID]);

    if ($verifyWishlist->rowCount() > 0) {
      $warningMsg[] = "product already exist in your wislist";
      echo "product already exist in your wislist";
    } else if ($cartNum->rowCount() > 0) {
      $warningMsg[] = "product already exist in your cart";
      echo "product already exist in your cart";
    } else {
      $selectProduct = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
      $selectProduct->execute([$productID]);
      $fetchProduct = $selectProduct->fetch(PDO::FETCH_ASSOC);

      $insertWishlist = $conn->prepare("INSERT into `wishlist` (id, userID, productID, price) VALUES(?, ?, ?, ?)");
      $insertWishlist->execute([$id, $userID, $productID, $fetchProduct["price"]]);
      $successMsg[] = "product added to the wishlist successfully";
      echo "product added to the wishlist successfully";
    }
  }

  // adding products to the cart
  if (isset($_POST["addToCart"])) {
    $id = uniqid();
    $productID = $_POST["productID"];

    $qty = $_POST["qty"];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    $verifyCart = $conn->prepare("SELECT * FROM `cart` WHERE userID = ? AND productID = ?");
    $verifyCart->execute([$userID, $productID]);

    $maxCartItems = $conn->prepare("SELECT * FROM `cart` WHERE userID = ?");
    $maxCartItems->execute(["userID"]);

    if ($verifyCart->rowCount() > 0) {
      $warningMsg[] = "product already exist in your cart";
      echo "product already exist in your cart";
    } else if ($maxCartItems->rowCount() > 20) {
      $warningMsg[] = "cart is full";
      echo "cart is full";
    } else {
      $selectProduct = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
      $selectProduct->execute([$productID]);
      $fetchProduct = $selectProduct->fetch(PDO::FETCH_ASSOC);

      $insertCart = $conn->prepare("INSERT into `cart` (id, userID, productID, price, qty) VALUES(?, ?, ?, ?, ?)");
      $insertCart->execute([$id, $userID, $productID, $fetchProduct["price"], $qty]);
      $successMsg[] = "product added to the cart successfully";
      echo "product added to the cart successfully";
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

  <title>products page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <section class="products">
      <div class="box-container">
        <?php
        $selectProducts = $conn->prepare("SELECT * FROM `products`");
        $selectProducts->execute();

        if ($selectProducts->rowCount() > 0) {
          while($fetchProducts = $selectProducts->fetch(PDO::FETCH_ASSOC)) {

        ?>
        <form action="" method="post" class="box">
          <img src="img/products/<?=$fetchProducts["image"]; ?>.png" class="product-img">

          <h3 class="name"><?=$fetchProducts["name"];?></h3>
          <input type="hidden" name="productID" value="<?=$fetchProducts["id"];?>" >

          <div class="flex">
            <img src="img/rate/star-<?=$fetchProducts["rate"];?>.png" class="img">
            <p class="rate"><?=$fetchProducts["rate"] / 1;?>/5</p>
          </div>

          <div class="flex">
            <p class="price">$<?=$fetchProducts["price"];?></p>
            <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
          </div>

          <div class="buttons">
            <button type="submit" name="addToCart">
              <i class="bx bx-cart"></i>
            </button>
            <button type="submit" name="addToWishlist">
              <i class="bx bx-heart"></i>
            </button>
            <a href="viewPage.php?pid=<?php echo $fetchProducts["id"]; ?>" class="bx bxs-show"></a>
          </div>

          <a href="checkout.php?getID=<?=$fetchProducts["id"]; ?>" class="btn buy-btn">buy now</a>
          
        </form>
        <?php
            }
          } else {
            echo "<p class='empty'>no products added yet!</p>";
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