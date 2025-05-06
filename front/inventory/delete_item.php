<?php
require_once('../../api/db.php');

if (!isset($_GET['id'])) {
  die("رقم الصنف غير موجود.");
}

$id = $_GET['id'];

// جلب بيانات الصنف قبل الحذف
$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
  die("الصنف غير موجود.");
}

// إضافة إلى سجل الحذف في stock_log بكمية صفرية (أو الكمية الفعلية)
$log = $pdo->prepare("INSERT INTO stock_log (item_id, change_type, amount, notes) VALUES (?, 'out', ?, ?)");
$log->execute([$id, $item['quantity'], 'تم حذف الصنف بالكامل']);

// حذف الصنف
$del = $pdo->prepare("DELETE FROM items WHERE id = ?");
$del->execute([$id]);

echo "<script>alert('✅ تم حذف الصنف بنجاح'); window.location.href='list_items.php';</script>";
?>
