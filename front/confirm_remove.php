
<?php
// FILE: confirm_remove.php (with error reporting)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$item_id = $_POST['item_id'] ?? '';
$remove_quantity = $_POST['remove_quantity'] ?? '';

if (!$item_id || !$remove_quantity || $remove_quantity <= 0) {
  die("بيانات غير صالحة.");
}

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

$new_qty = $current_qty - $remove_qty;
$update = $pdo->prepare("UPDATE items SET quantity = ? WHERE id = ?");
$update->execute([$new_qty, $item_id]);

// تأكد من وجود جدول logs لتجنب أي fatal error
try {
  $log = $pdo->prepare("INSERT INTO logs (item_id, action, quantity, timestamp) VALUES (?, 'إخراج', ?, NOW())");
  $log->execute([$item_id, $remove_qty]);
} catch (PDOException $e) {
  die("خطأ أثناء تسجيل العملية في السجل: " . $e->getMessage());
}

// صفحة النجاح
echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
  <meta charset='UTF-8'>
  <title>تم الإخراج</title>
  <script>
    setTimeout(() => {
      window.location.href = 'remove_item_smart.php';
    }, 3000);
  </script>
  <style>
    body { font-family: 'Cairo', sans-serif; text-align: center; padding: 40px; background: #f4f4f4; }
    .success { font-size: 22px; color: green; margin-top: 30px; }
    .loading { margin-top: 20px; font-size: 16px; color: #666; }
  </style>
</head>
<body>
  <audio autoplay>
    <source src='https://cdn.pixabay.com/audio/2022/03/15/audio_d420eab5b2.mp3' type='audio/mpeg'>
  </audio>
  <div class='success'>✔️ تم إخراج <strong>{$remove_qty}</strong> من <strong>{$item['name']}</strong> بنجاح.</div>
  <div class='loading'>جارٍ العودة للمسح...</div>
</body>
</html>";
?>
