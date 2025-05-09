
<?php
// FILE: confirm_remove.php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

// استلام البيانات
$item_id = $_POST['item_id'] ?? '';
$remove_quantity = $_POST['remove_quantity'] ?? '';

if (!$item_id || !$remove_quantity || $remove_quantity <= 0) {
  die("بيانات غير صالحة.");
}

// جلب بيانات الصنف
$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

if (!$item) {
  die("الصنف غير موجود.");
}

$current_qty = (int)$item['quantity'];
$remove_qty = (int)$remove_quantity;

if ($remove_qty > $current_qty) {
  die("لا يمكن إخراج كمية أكبر من الكمية المتاحة.");
}

// تحديث الكمية
$new_qty = $current_qty - $remove_qty;
$update = $pdo->prepare("UPDATE items SET quantity = ? WHERE id = ?");
$update->execute([$new_qty, $item_id]);

// تسجيل الحركة في log
$log = $pdo->prepare("INSERT INTO logs (item_id, action, quantity, timestamp) VALUES (?, 'إخراج', ?, NOW())");
$log->execute([$item_id, $remove_qty]);

// عرض رسالة نجاح
echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
  <meta charset='UTF-8'>
  <title>تم الإخراج</title>
  <style>
    body { font-family: 'Cairo', sans-serif; text-align: center; padding: 40px; background: #f4f4f4; }
    .success { font-size: 20px; color: green; }
    a { display: inline-block; margin-top: 20px; font-size: 18px; color: #00796b; text-decoration: none; }
  </style>
</head>
<body>
  <div class='success'>تم إخراج <strong>{$remove_qty}</strong> من الصنف <strong>{$item['name']}</strong> بنجاح.</div>
  <a href='remove_item_smart.php'>رجوع للمسح</a>
</body>
</html>";
?>
