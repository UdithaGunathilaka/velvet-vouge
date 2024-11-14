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
  
  // adding products to the cart
  if (isset($_POST["addToCart"])) {
    $id = uniqid();
    $productID = $_POST["productID"];

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
      $selectPrice = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
      $selectPrice->execute([$productID]);
      $fetchPrice = $selectPrice->fetch(PDO::FETCH_ASSOC);

      $insertCart = $conn->prepare("INSERT into `cart` (id, userID, productID, price, qty) VALUES(?, ?, ?, ?, ?)");
      $insertCart->execute([$id, $userID, $productID, $fetchPrice["price"], 1]);
      $successMsg[] = "product added to the cart successfully";
      echo "product added to the cart successfully";
    }
  }

  // delete products from wishlist
  if (isset($_POST["deleteItem"])) {
    $wishlistID = $_POST["wishlistID"];
    $wishlistID = filter_var($wishlistID, FILTER_SANITIZE_STRING);

    $verifyDeleteItem = $conn->prepare("SELECT * FROM `wishlist` WHERE id = ?");
    $verifyDeleteItem->execute([$wishlistID]);

    if ($verifyDeleteItem->rowCount() > 0) {
      $deleteItem = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
      $deleteItem->execute([$wishlistID]);

      $successMsg[] = "wishlist item delete successfully";
      echo "wishlist item delete successfully";
    } else {
      $warningMsg[] = "wishlist item already deleted";
      echo "wishlist item already deleted";
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

  <title>wishlist page</title>
</head>
<body>
  <?php include("components/header.php"); ?>

  <div class="main">
    <section class="products">
      <h1 class="title">your wishlist</h1>
      <div class="box-container">
        <?php
        $grandTotal = 0;
        $selectWishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE userID = ?");
        $selectWishlist->execute([$userID]);

        if ($selectWishlist->rowCount() > 0) {
          while($fetchWishlist = $selectWishlist->fetch(PDO::FETCH_ASSOC)) {
            $selectProducts = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
            $selectProducts->execute([$fetchWishlist["productID"]]);

            if ($selectProducts->rowCount() > 0) {
              $fetchProducts = $selectProducts->fetch(PDO::FETCH_ASSOC);

        ?>
        <form action="" method="post" class="box">
          <input type="hidden" name="wishlistID" value="<?=$fetchWishlist["id"]; ?>">
          <img src="img/products/<?=$fetchProducts["image"]; ?>.png" class="product-img">

          <input type="hidden" name="productID" value="<?=$fetchProducts["id"]; ?>">

          <div class="flex">
            <h3 class="name"><?=$fetchProducts["name"]; ?></h3>
            <p class="price">$<?=$fetchProducts["price"]; ?></p>
          </div>

          <div class="buttons">
            <button type="submit" name="addToCart">
              <i class="bx bx-cart"></i>
            </button>
            <a href="viewPage.php?pid=<?php echo $fetchProducts["id"]; ?>" class="bx bxs-show"></a>
            <button type="submit" name="deleteItem" onclick="return confirm('delete this item');">
              <i class="bx bx-x"></i>
            </button>
          </div>

          <a href="checkout.php?getID=<?=$fetchProducts["id"]; ?>" class="btn buy-btn">buy now</a>
        </form>
        <?php
              $grandTotal += $fetchWishlist["price"];
              }
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