<?php
// ✅ Part 8: AJAX endpoint — returns JSON for live search/filtering
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$q   = isset($_GET['q']) ? trim($_GET['q']) : '';
$cat = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$sql = "SELECT p.id,p.title,p.description,p.price,c.name AS category_name
        FROM products p JOIN categories c ON c.id=p.category_id WHERE 1=1";
$params = [];
if ($q !== '') { $sql .= " AND p.title LIKE ?"; $params[] = "%$q%"; }
if ($cat > 0)  { $sql .= " AND p.category_id = ?"; $params[] = $cat; }
$sql .= " ORDER BY p.created_at DESC LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode(['ok'=>true, 'items'=>$stmt->fetchAll()]);
