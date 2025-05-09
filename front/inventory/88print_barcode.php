
<?php
// FILE: print_barcode.php (QR via JavaScript)
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$id = $_GET['id'] ?? '';
if (!$id) {
  die("رقم الصنف غير موجود.");
}

// جلب بيانات الصنف
$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
  die("الصنف غير موجود.");
}

// جلب الوحدات
$stmtUnits = $pdo->prepare("SELECT * FROM item_units WHERE item_id = ? ORDER BY id ASC");
$stmtUnits->execute([$id]);
$units = $stmtUnits->fetchAll();
$count = count($units);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>طباعة QR للصنف</title>
  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      padding: 30px;
      text-align: center;
    }
    .barcode-box {
      display: inline-block;
      border: 2px dashed #ccc;
      padding: 20px;
      margin: 10px;
      width: 280px;
    }
    .item-name {
      font-size: 18px;
      font-weight: bold;
    }
    .model, .serial, .price {
      font-size: 14px;
      margin-top: 5px;
    }
    .qr {
      margin: 10px 0;
    }
    @media print {
      body { margin: 0; }
    }
  </style>
</head>
<body>

<?php if ($count > 0): ?>
  <?php foreach ($units as $i => $unit): ?>
    <?php $qrText = $item['barcode']; ?>
    <div class="barcode-box">
      <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
      <div class="model">موديل: <?= htmlspecialchars($item['model']) ?></div>
      <div class="qr" id="qr-<?= $i ?>"></div>
      <div class="serial">السيريال: <?= htmlspecialchars($item['serial_number']) ?></div>
      <div class="price"><?= htmlspecialchars($item['price']) ?> د.أ</div>
      <div class="serial"><?= ($i + 1) ?> من <?= $count ?></div>
    </div>
    <script>
      QRCode.toDataURL("<?= $qrText ?>")
        .then(url => {
          const img = document.createElement('img');
          img.src = url;
          img.alt = "QR Code";
          document.getElementById("qr-<?= $i ?>").appendChild(img);
        });
    </script>
  <?php endforeach; ?>
<?php else: ?>
  <p class="text-danger">لا يوجد وحدات مسجلة لهذا الصنف.</p>
<?php endif; ?>

</body>
</html>
