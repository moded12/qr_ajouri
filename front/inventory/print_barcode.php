
<?php
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

// الكمية الحالية
$quantity = (int)$item['quantity'];
$barcode = $item['barcode'];
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
      padding: 20px;
      text-align: center;
    }
    .barcode-box {
      display: inline-block;
      border: 1px dashed #ccc;
      margin: 10px;
      padding: 15px;
      width: 260px;
    }
    .qr {
      margin: 10px auto;
    }
    .title {
      font-weight: bold;
      font-size: 18px;
    }
    .info {
      font-size: 14px;
      color: #555;
    }
    @media print {
      .no-print { display: none; }
    }
  </style>
</head>
<body>

<h2>طباعة QR - <?= htmlspecialchars($item['name']) ?></h2>
<p class="no-print"><button onclick="window.print()">🖨️ طباعة</button></p>

<?php for ($i = 1; $i <= $quantity; $i++): ?>
  <div class="barcode-box">
    <div class="title"><?= htmlspecialchars($item['name']) ?></div>
    <div class="info">موديل: <?= htmlspecialchars($item['model']) ?></div>
    <div class="qr" id="qr-<?= $i ?>"></div>
    <div class="info">السيريال: <?= htmlspecialchars($item['serial_number']) ?></div>
    <div class="info">السعر: <?= htmlspecialchars($item['price']) ?> د.أ</div>
    <div class="info"><?= $i ?> من <?= $quantity ?></div>
  </div>
  <script>
    QRCode.toDataURL("<?= $barcode ?>").then(url => {
      const img = document.createElement('img');
      img.src = url;
      document.getElementById("qr-<?= $i ?>").appendChild(img);
    });
  </script>
<?php endfor; ?>

</body>
</html>
