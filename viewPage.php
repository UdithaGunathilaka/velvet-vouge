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
      $selectPrice = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
      $selectPrice->execute([$productID]);
      $fetchPrice = $selectPrice->fetch(PDO::FETCH_ASSOC);

      $insertWishlist = $conn->prepare("INSERT into `wishlist` (id, userID, productID, price) VALUES(?, ?, ?, ?)");
      $insertWishlist->execute([$id, $userID, $productID, $fetchPrice["price"]]);
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

  <title>view product page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <section class="view-page">
      <?php
        if (isset($_GET["pid"])) {
          $pid = $_GET["pid"];
          $selectProduct = $conn->prepare("SELECT * FROM `products` WHERE id = '$pid'");
          $selectProduct->execute();

          if ($selectProduct->rowCount() > 0) {
            while($fetchProduct = $selectProduct->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <form method="post">
        <img src="img/products/<?php echo $fetchProduct["image"]; ?>.png">
        <div class="detail">
          <div class="name-price-detail">
            <div class="name"><?php echo $fetchProduct["name"]; ?></div>
            <div class="price">$<?php echo $fetchProduct["price"]; ?>/-</div>
          </div>

          <p class="product-detail">
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Maxime libero, sit adipisci nemo porro alias suscipit obcaecati repudiandae. Quas harum aspernatur praesentium labore eaque repellendus ex dolore sequi ad autem! <?php echo $fetchProduct["productDetail"]; ?>
          </p>

          <input type="hidden" name="productID" value="<?php echo $fetchProduct["id"]; ?>">

          <div class="buttons">
            <button type="submit" name="addToWishlist" class="btn">add to wishlist
              <i class="bx bx-heart"></i>
            </button>
            <input type="hidden" name="qty" value="1" min="0" class="quantity">
            <button type="submit" name="addToCart" class="btn">add to cart
              <i class="bx bx-cart"></i>
            </button>
          </div>
        </div>
      </form>
      <?php
            }
          }
        }
      ?>
    </section>

    <?php include("components/footer.php"); ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script src="script.js"></script>

  <?php include("components/alert.php"); ?>
</body>
</html>