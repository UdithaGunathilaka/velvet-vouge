<header>
  <div class="flex">
    <a href="dashboard.php">
      <h2 class="logo">velvet.vogue</h2>
    </a>

    <nav class="navbar">
      <a href="createProduct.php">create</a>
    </nav>

    <div class="icons">
      <i class="bx bxs-user" id="user-btn"></i>
      <?php
        $countWishlistItem = $conn->prepare("SELECT * FROM `wishlist` WHERE userID = ?");
        $countWishlistItem->execute([$userID]);
        $totalWishlistItems = $countWishlistItem->rowCount();
      ?>
      <?php
        $countCartItem = $conn->prepare("SELECT SUM(qty) AS totalQty FROM cart WHERE userID = ?");
        $countCartItem->execute([$userID]);
        $totalCartItems = $countCartItem->fetchColumn() ?? 0;
      ?>
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