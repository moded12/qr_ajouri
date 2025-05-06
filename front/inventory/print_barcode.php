<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$id = $_GET['id'] ?? '';
if (!$id) {
  die("رقم الصنف غير موجود.");
}

$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
  die("الصنف غير موجود.");
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>طباعة الباركود</title>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      text-align: center;
      margin: 40px;
    }
    .barcode-container {
      display: inline-block;
      border: 2px dashed #ccc;
      padding: 30px;
    }
    .item-name {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .serial {
      margin-top: 10px;
      font-size: 14px;
      color: #444;
    }
    @media print {
      body {
        margin: 0;
      }
    }
  </style>
</head>
<body onload="window.print()">

<div class="barcode-container">
  <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
  <svg id="barcode"></svg>
  <div class="serial">السيريال: <?= htmlspecialchars($item['serial_number']) ?></div>
</div>

<script>
  JsBarcode("#barcode", "<?= $item['barcode'] ?>", {
    format: "CODE128",
    width: 2,
    height: 70,
    displayValue: false
  });
</script>

</body>
</html>