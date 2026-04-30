<?php
require_once __DIR__ . '/../config/db.php';
require_admin();

$action = isset($_POST['action']) ? $_POST['action'] : '';

try {
    switch ($action) {
        case 'add_product':
            $stmt = $pdo->prepare("INSERT INTO products (category_id,seller_id,title,description,price,stock)
                                   VALUES (?,?,?,?,?,?)");
            $stmt->execute([
                (int)$_POST['category_id'],
                $_SESSION['user_id'],
                trim($_POST['title']),
                trim($_POST['description']),
                (float)$_POST['price'],
                (int)$_POST['stock'],
            ]);
            $msg = 'Product created.';
            break;

        case 'delete_product':
            $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([(int)$_POST['id']]);
            $msg = 'Product deleted.';
            break;

        case 'update_price':
            $pdo->prepare("UPDATE products SET price = ? WHERE id = ?")
                ->execute([(float)$_POST['price'], (int)$_POST['id']]);
            $msg = 'Price updated.';
            break;

        case 'delete_user':
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([(int)$_POST['id']]);
            $msg = 'User deleted.';
            break;

        case 'toggle_role':
            $pdo->prepare("UPDATE users
                           SET role = CASE WHEN role='admin' THEN 'user' ELSE 'admin' END
                           WHERE id = ?")->execute([(int)$_POST['id']]);
            $msg = 'Role toggled.';
            break;

        default:
            $msg = 'Unknown action.';
    }
} catch (Exception $e) {
    $msg = 'Error: ' . $e->getMessage();
}

header('Location: /digimarket/admin.php?msg=' . urlencode($msg));
exit;
