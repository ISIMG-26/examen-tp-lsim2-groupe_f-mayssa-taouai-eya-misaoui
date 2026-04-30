<?php
$pageTitle = 'Shop';
require_once __DIR__ . '/config/db.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// ✅ Part 9: $_GET filtering
$catFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search    = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT p.*, c.name AS category_name
        FROM products p JOIN categories c ON c.id = p.category_id WHERE 1=1";
$params = [];
if ($catFilter) { $sql .= " AND p.category_id = ?"; $params[] = $catFilter; }
if ($search !== '') { $sql .= " AND p.title LIKE ?"; $params[] = "%$search%"; }
$sql .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<section class="shop-head">
  <h1>Marketplace</h1>
  <p class="muted">Browse all digital products. Filter by category or search live (AJAX).</p>
</section>

<section class="shop-layout">
  <aside class="filters">
    <h3>Categories</h3>
    <ul class="cat-list" id="catList">
      <li><a href="shop.php" class="<?= $catFilter===0?'active':'' ?>" data-cat="0">All</a></li>
      <?php foreach ($categories as $c): ?>
        <li><a href="shop.php?category=<?= $c['id'] ?>"
               class="<?= $catFilter===(int)$c['id']?'active':'' ?>"
               data-cat="<?= $c['id'] ?>"><?= e($c['name']) ?></a></li>
      <?php endforeach; ?>
    </ul>

    <h3>Search</h3>
    <!-- ✅ Part 8: AJAX live search (no page reload) -->
    <input type="search" id="liveSearch" placeholder="Search products..." value="<?= e($search) ?>">
  </aside>

  <div class="shop-results">
    <p id="resultCount" class="muted"><?= count($products) ?> result(s)</p>
    <div id="productGrid" class="grid grid-3">
      <?php if (!$products): ?>
        <p>No products found.</p>
      <?php else: foreach ($products as $p): ?>
        <article class="product-card">
          <div class="product-img" style="background:hsl(<?= ($p['id']*47)%360 ?> 60% 85%);">
            <?= strtoupper(substr($p['title'],0,1)) ?>
          </div>
          <span class="tag"><?= e($p['category_name']) ?></span>
          <h3><?= e($p['title']) ?></h3>
          <p class="desc"><?= e(mb_strimwidth($p['description'],0,90,'…')) ?></p>
          <p class="price">$<?= number_format($p['price'],2) ?></p>
          <?php if (is_logged_in()): ?>
            <form method="POST" action="actions/cart.php" class="inline">
              <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
              <input type="hidden" name="action" value="add">
              <button type="submit" class="btn btn-sm btn-primary">Add to cart</button>
            </form>
          <?php else: ?>
            <a href="login.php" class="btn btn-sm">Login to buy</a>
          <?php endif; ?>
        </article>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
