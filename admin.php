<?php
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/config/db.php';
require_admin();

$users    = $pdo->query("SELECT id,username,email,role,created_at FROM users ORDER BY id DESC")->fetchAll();
$products = $pdo->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC")->fetchAll();
$orders   = $pdo->query("SELECT o.*, u.username, p.title FROM orders o JOIN users u ON u.id=o.user_id JOIN products p ON p.id=o.product_id ORDER BY o.id DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<section>
  <h1>Admin Dashboard</h1>
  <p class="muted">Manage products, users and orders.</p>

  <?php if (isset($_GET['msg'])): ?>
    <div class="alert success"><?= e($_GET['msg']) ?></div>
  <?php endif; ?>

  <!-- ✅ Tabs (DOM manipulation interactive feature) -->
  <div class="tabs" id="adminTabs">
    <button class="tab-btn active" data-tab="products">Products (<?= count($products) ?>)</button>
    <button class="tab-btn" data-tab="users">Users (<?= count($users) ?>)</button>
    <button class="tab-btn" data-tab="orders">Orders (<?= count($orders) ?>)</button>
  </div>

  <!-- PRODUCTS -->
  <section class="tab-panel active" data-panel="products">
    <h2>Add a new product</h2>
    <form method="POST" action="actions/admin_actions.php" class="grid-form">
      <input type="hidden" name="action" value="add_product">
      <input type="text" name="title" placeholder="Title" required maxlength="150">
      <select name="category_id" required>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <input type="number" name="price" placeholder="Price" min="0" step="0.01" required>
      <input type="number" name="stock" placeholder="Stock" min="0" value="1" required>
      <textarea name="description" placeholder="Description" required></textarea>
      <button class="btn btn-primary">Create product</button>
    </form>

    <h2>All products</h2>
    <table class="data-table">
      <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($products as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= e($p['title']) ?></td>
          <td><?= e($p['category_name']) ?></td>
          <td>$<?= number_format($p['price'],2) ?></td>
          <td><?= $p['stock'] ?></td>
          <td>
            <form method="POST" action="actions/admin_actions.php" class="inline" onsubmit="return confirm('Delete this product?');">
              <input type="hidden" name="action" value="delete_product">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button class="btn btn-sm btn-danger">Delete</button>
            </form>
            <form method="POST" action="actions/admin_actions.php" class="inline">
              <input type="hidden" name="action" value="update_price">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" style="width:90px">
              <button class="btn btn-sm">Update</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </section>

  <!-- USERS -->
  <section class="tab-panel" data-panel="users">
    <h2>Users</h2>
    <table class="data-table">
      <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= e($u['username']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><span class="role-tag role-<?= e($u['role']) ?>"><?= e($u['role']) ?></span></td>
          <td><?= e($u['created_at']) ?></td>
          <td>
            <?php if ($u['id'] != $_SESSION['user_id']): ?>
              <form method="POST" action="actions/admin_actions.php" class="inline" onsubmit="return confirm('Delete this user?');">
                <input type="hidden" name="action" value="delete_user">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button class="btn btn-sm btn-danger">Delete</button>
              </form>
              <form method="POST" action="actions/admin_actions.php" class="inline">
                <input type="hidden" name="action" value="toggle_role">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button class="btn btn-sm">Toggle role</button>
              </form>
            <?php else: ?>
              <em class="muted">you</em>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </section>

  <!-- ORDERS -->
  <section class="tab-panel" data-panel="orders">
    <h2>Orders</h2>
    <table class="data-table">
      <thead><tr><th>ID</th><th>User</th><th>Product</th><th>Qty</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
      <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?= $o['id'] ?></td>
          <td><?= e($o['username']) ?></td>
          <td><?= e($o['title']) ?></td>
          <td><?= $o['quantity'] ?></td>
          <td>$<?= number_format($o['total'],2) ?></td>
          <td><span class="status status-<?= e($o['status']) ?>"><?= e($o['status']) ?></span></td>
          <td><?= e($o['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$orders): ?><tr><td colspan="7" class="muted">No orders yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </section>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
