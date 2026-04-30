<?php
$pageTitle = 'Home';
require_once __DIR__ . '/config/db.php';

// ✅ Part 9 & 10: dynamic content from DB
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$featured   = $pdo->query("SELECT p.*, c.name AS category_name
                           FROM products p
                           JOIN categories c ON c.id = p.category_id
                           ORDER BY p.created_at DESC LIMIT 6")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="hero-text">
    <h1>Buy &amp; Sell Digital Products with Confidence</h1>
    <p>Gaming accounts, social media pages, dev &amp; design services, marketing and consulting — all in one trusted marketplace.</p>
    <div class="hero-cta">
      <a href="shop.php" class="btn btn-primary">Browse the Shop</a>
      <a href="register.php" class="btn btn-ghost">Become a Seller</a>
    </div>
  </div>
  <div class="hero-card" aria-hidden="true">
    <div class="float-card c1">🎮 Gaming</div>
    <div class="float-card c2">📱 Social</div>
    <div class="float-card c3">💻 Dev</div>
    <div class="float-card c4">🎨 Design</div>
  </div>
</section>

<section class="categories">
  <h2>Explore Categories</h2>
  <div class="grid grid-3">
    <?php foreach ($categories as $cat): ?>
      <article class="cat-card">
        <h3><?= e($cat['name']) ?></h3>
        <p><?= e($cat['description']) ?></p>
        <a href="shop.php?category=<?= (int)$cat['id'] ?>">Browse →</a>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="featured">
  <h2>Featured Products</h2>
  <div class="grid grid-3" id="featuredGrid">
    <?php foreach ($featured as $p): ?>
      <article class="product-card">
        <div class="product-img" style="background:hsl(<?= ($p['id']*47)%360 ?> 60% 85%);">
          <?= strtoupper(substr($p['title'],0,1)) ?>
        </div>
        <span class="tag"><?= e($p['category_name']) ?></span>
        <h3><?= e($p['title']) ?></h3>
        <p class="price">$<?= number_format($p['price'],2) ?></p>
        <a href="shop.php" class="btn btn-sm">View in shop</a>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="newsletter">
  <h2>Stay in the loop</h2>
  <p>Check email availability before signing up — no reload needed.</p>
  <!-- ✅ Part 8: AJAX live email check -->
  <form id="emailCheckForm" class="inline-form" novalidate>
    <input type="email" id="emailCheck" placeholder="you@example.com" required>
    <button type="submit" class="btn btn-primary">Check availability</button>
  </form>
  <p id="emailCheckResult" class="result-msg" role="status"></p>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
