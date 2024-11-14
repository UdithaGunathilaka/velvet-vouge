<header>
  <div class="flex">
    <a href="home.php">
      <h2 class="logo">velvet.vogue</h2>
    </a>

    <nav class="navbar">
      <a href="home.php">home</a>
      <a href="viewProducts.php">products</a>
      <a href="order.php">orders</a>
      <a href="about.php">about us</a>
      <a href="contact.php">contact us</a>
    </nav>

    <div class="icons">
      <i class="bx bxs-user" id="user-btn"></i>
      <?php
        $countWishlistItem = $conn->prepare("SELECT * FROM `wishlist` WHERE userID = ?");
        $countWishlistItem->execute([$userID]);
        $totalWishlistItems = $countWishlistItem->rowCount();
      ?>
      <a href="wishlist.php" class="cart-btn">
        <i class="bx bx-heart"></i><sup><?=$totalWishlistItems?></sup>
      </a>
      <?php
        $countCartItem = $conn->prepare("SELECT SUM(qty) AS totalQty FROM cart WHERE userID = ?");
        $countCartItem->execute([$userID]);
        $totalCartItems = $countCartItem->fetchColumn() ?? 0;
      ?>
      <a href="cart.php" class="cart-btn">
        <i class="bx bx-cart-download"></i><sup><?=$totalCartItems?></sup>
      </a>
      <i class="bx bx-list-plus" id="menu-btn" style="font-size: 2rem;"></i>
    </div>

    <div class="user-box">
      <p>Username: <span><?php echo $_SESSION["userName"] ?? ""; ?></span></p>
      
      <p>Email: <span><?php echo $_SESSION["userEmail"] ?? ""; ?></span></p>
      
      <a href="login.php" class="btn">login</a>
      <a href="register.php" class="btn">register</a>

      <form method="post">
        <button type="submit" name="logout" class="user-box-btn">log out</button>
      </form>
    </div>
  </div>
</header>