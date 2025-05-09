<?php
// FILE: get_item_by_barcode.php (clean JSON output)
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');
ob_clean(); // remove any unexpected output (spaces, BOM, etc.)

$barcode = $_GET['barcode'] ?? '';
if (!$barcode) {
    echo json_encode(['status' => 'error', 'message' => 'لم يتم إرسال رقم الباركود.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM items WHERE barcode = ?");
$stmt->execute([$barcode]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {
    echo json_encode(['status' => 'success', 'item' => $item], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['status' => 'error', 'message' => 'الصنف غير موجود في قاعدة البيانات.']);
}
?>
