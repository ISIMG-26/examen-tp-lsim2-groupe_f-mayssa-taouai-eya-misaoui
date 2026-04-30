<?php
// ✅ Part 8: AJAX email availability check
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$email = isset($_GET['email']) ? trim($_GET['email']) : '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok'=>false, 'reason'=>'invalid']); exit;
}
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
echo json_encode(['ok'=>true, 'available'=> !$stmt->fetch()]);
