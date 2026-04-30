<?php require_once __DIR__ . '/../config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' — DigiMarket' : 'DigiMarket — Buy & Sell Digital Assets' ?></title>
<link rel="stylesheet" href="/digimarket/assets/css/style.css">
</head>
<body>
<!-- ✅ Part 4: Semantic HTML5 + shared navigation -->
<header class="site-header">
  <div class="container nav-wrap">
    <a href="/digimarket/index.php" class="logo">Digi<span>Market</span></a>
    <nav class="main-nav">
      <ul>
        <li><a href="/digimarket/index.php">Home</a></li>
        <li><a href="/digimarket/shop.php">Shop</a></li>
        <?php if (is_logged_in()): ?>
          <li><a href="/digimarket/cart.php">Cart
            <?php
              $count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
              if ($count) echo '<span class="badge">'.$count.'</span>';
            ?>
          </a></li>
          <?php if (is_admin()): ?>
            <li><a href="/digimarket/admin.php" class="admin-link">Admin</a></li>
          <?php endif; ?>
          <li><a href="/digimarket/logout.php">Logout (<?= e($_SESSION['username']) ?>)</a></li>
        <?php else: ?>
          <li><a href="/digimarket/login.php">Login</a></li>
          <li><a href="/digimarket/register.php" class="btn-nav">Sign up</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>
<main class="container">
