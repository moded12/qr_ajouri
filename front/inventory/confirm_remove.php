
<?php
// FILE: confirm_remove.php
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

$log = $pdo->prepare("INSERT INTO logs (item_id, action, quantity, timestamp) VALUES (?, 'إخراج', ?, NOW())");
$log->execute([$item_id, $remove_qty]);

echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
  <meta charset='UTF-8'>
  <title>تم الإخراج</title>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <script>
    setTimeout(() => {
      window.location.href = 'manual_remove.php';
    }, 5000);
  </script>
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      text-align: center;
    }
    .box {
      background: #fff;
      padding: 30px 20px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      max-width: 90%;
      width: 400px;
    }
    .success {
      color: #2e7d32;
      font-size: 22px;
      margin-bottom: 15px;
    }
    .loading {
      font-size: 15px;
      color: #666;
      margin-top: 10px;
    }
    .btn-print {
      display: inline-block;
      margin-top: 15px;
      background-color: #00796b;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
    }
    .btn-print:hover {
      background-color: #00695c;
    }
  </style>
</head>
<body>
  <div class='box'>
    <div class='success'>✔️ تم إخراج <strong>{$remove_qty}</strong> من <strong>{$item['name']}</strong> بنجاح.</div>
    <a class='btn-print' href='receipt.php?item_id={$item_id}&qty={$remove_qty}' target='_blank'>🖨️ طباعة إيصال الإخراج</a>
    <div class='loading'>سيتم الرجوع إلى الصفحة خلال 5 ثوانٍ...</div>
  </div>
</body>
</html>";
?>
