<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

// استلام البيانات من النموذج
$name        = $_POST['name'] ?? '';
$category_id = $_POST['category_id'] ?? null;
$unit_id     = $_POST['unit_id'] ?? null;
$location_id = $_POST['location_id'] ?? null;
$quantity    = $_POST['quantity'] ?? 0;
$price       = $_POST['price'] ?? 0.00;
$notes       = $_POST['notes'] ?? '';

// تحقق من القيم الأساسية
if (empty($name)) {
  die("يجب إدخال اسم الصنف.");
}

// توليد باركود (12 رقم) وسيريال فريد
$barcode = rand(100000000000, 999999999999);
$serial_number = uniqid();

// تنفيذ الإدخال
$sql = "INSERT INTO items (name, category_id, unit_id, location_id, quantity, price, notes, barcode, serial_number)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$success = $stmt->execute([
  $name,
  $category_id,
  $unit_id,
  $location_id,
  $quantity,
  $price,
  $notes,
  $barcode,
  $serial_number
]);

if ($success) {
  header("Location: list_items.php?success=1");
  exit;
} else {
  echo "⚠️ حدث خطأ أثناء إضافة الصنف.";
}
?>