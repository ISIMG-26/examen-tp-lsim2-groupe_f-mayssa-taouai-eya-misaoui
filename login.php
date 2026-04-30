<?php
$pageTitle = 'Login';
require_once __DIR__ . '/config/db.php';
if (is_logged_in()) {
  header('Location: index.php?msg=already_logged_in'); exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$pass  = isset($_POST['password']) ? $_POST['password'] : '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];
        header('Location: ' . (is_admin() ? 'admin.php' : 'index.php')); exit;
    } else {
        $error = 'Invalid credentials.';
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="auth-card">
  <h1>Welcome back</h1>
  <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
  <form method="POST" id="loginForm" novalidate>
    <label>Email <input type="email" name="email" id="loginEmail" required></label>
    <label>Password <input type="password" name="password" id="loginPassword" required minlength="6"></label>
    <p id="loginErrors" class="result-msg error-text" role="alert"></p>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>
  <p class="muted">No account? <a href="register.php">Register</a></p>
  <p class="muted small">Demo: register a new account, or use the seeded admin once you reset the hash via the README instructions.</p>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
