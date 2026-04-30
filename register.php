<?php
$pageTitle = 'Register';
require_once __DIR__ . '/config/db.php';
if (is_logged_in()) {
  header('Location: index.php?msg=already_have_account'); exit;
}
$errors = [];
$old = ['username'=>'', 'email'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm  = isset($_POST['confirm']) ? $_POST['confirm'] : '';
    $old = compact('username','email');

    // ✅ Part 7 (server side mirror of client validation)
    if (strlen($username) < 3 || strlen($username) > 50) $errors[] = "Username must be 3-50 chars.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))      $errors[] = "Invalid email.";
    if (strlen($password) < 6)                           $errors[] = "Password must be at least 6 chars.";
    if ($password !== $confirm)                          $errors[] = "Passwords do not match.";

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $errors[] = "Username or email already used.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?, 'user')");
            $stmt->execute([$username,$email,$hash]);
            $_SESSION['user_id']  = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['role']     = 'user';
            header('Location: index.php'); exit;
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="auth-card">
  <h1>Create your account</h1>
  <?php if ($errors): ?>
    <div class="alert error"><ul><?php foreach ($errors as $err) echo '<li>'.e($err).'</li>'; ?></ul></div>
  <?php endif; ?>

  <!-- ✅ Part 7: Form validated client-side by JS, server-side by PHP -->
  <form method="POST" id="registerForm" novalidate>
    <label>Username
      <input type="text" name="username" id="username" value="<?= e($old['username']) ?>" required minlength="3" maxlength="50">
    </label>
    <label>Email
      <input type="email" name="email" id="email" value="<?= e($old['email']) ?>" required>
    </label>
    <label>Password
      <input type="password" name="password" id="password" required minlength="6">
    </label>
    <label>Confirm password
      <input type="password" name="confirm" id="confirm" required minlength="6">
    </label>
    <p id="formErrors" class="result-msg error-text" role="alert"></p>
    <button type="submit" class="btn btn-primary">Sign up</button>
  </form>
  <p class="muted">Already have an account? <a href="login.php">Login</a></p>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
