<?php
require_once __DIR__ . '/../config/db.php';
require_login();

$action = isset($_POST['action']) ? $_POST['action'] : '';
$pid    = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($pid > 0) {
    if ($action === 'add') {
$_SESSION['cart'][$pid] = (isset($_SESSION['cart'][$pid]) ? $_SESSION['cart'][$pid] : 0) + 1;
    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$pid]);
    }
}

header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/digimarket/shop.php'));
exit;
