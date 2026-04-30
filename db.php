<?php
// =====================================================
// Secure DB connection using PDO + prepared statements
// ✅ Part 9: PHP backend — central config
// =====================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'digimarket');
define('DB_USER', 'root');     // default WAMP/XAMPP
define('DB_PASS', '');         // default WAMP/XAMPP (empty)
define('DB_CHAR', 'utf8mb4');

try {
    $pdo = new PDO(
"mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

if (!isset($_SESSION)) {
    session_start();
}

// Helper
function e($v) { return htmlspecialchars(isset($v) ? $v : '', ENT_QUOTES, 'UTF-8'); }
function is_logged_in() { return isset($_SESSION['user_id']); }
function is_admin() { return isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; }
function require_login() {
    if (!is_logged_in()) { header('Location: /digimarket/login.php'); exit; }
}
function require_admin() {
    if (!is_admin()) { header('Location: /digimarket/index.php'); exit; }
}
