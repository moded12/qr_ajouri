<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['min'] as $item_id => $min) {
        $max = $_POST['max'][$item_id] ?? 0;

        $check = $pdo->prepare("SELECT COUNT(*) FROM stock_thresholds WHERE item_id = ?");
        $check->execute([$item_id]);
        $exists = $check->fetchColumn();

        if ($exists) {
            $update = $pdo->prepare("UPDATE stock_thresholds SET min_threshold = ?, max_threshold = ? WHERE item_id = ?");
            $update->execute([$min, $max, $item_id]);
        } else {
            $insert = $pdo->prepare("INSERT INTO stock_thresholds (item_id, min_threshold, max_threshold) VALUES (?, ?, ?)");
            $insert->execute([$item_id, $min, $max]);
        }
    }

    echo "<p style='color: green; text-align: center;'>✅ تم الحفظ بنجاح</p>";
}

$items = $pdo->query("SELECT * FROM items")->fetchAll(PDO::FETCH_ASSOC);

$thresholds = [];
$stmt = $pdo->query("SELECT * FROM stock_thresholds");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $thresholds[$row['item_id']] = $row;
}

$alerts = $pdo->query("
    SELECT i.name, i.quantity, t.min_threshold, t.max_threshold
    FROM items i
    JOIN stock_thresholds t ON i.id = t.item_id
    WHERE i.quantity <= t.min_threshold OR i.quantity >= t.max_threshold
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تحديد حدود التنبيه والتشبع</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #f4f4f4;
      padding: 20px;
    }
    h2, h3 {
      text-align: center;
      color: #004D40;
      margin: 20px 0;
    }
    h3 {
      color: #B71C1C;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      margin-bottom: 30px;
      box-shadow: 0 0 10px #ccc;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #004D40;
      color: white;
    }
    .alert-table th {
      background-color: #B71C1C;
    }
    button {
      background-color: #004D40;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      display: block;
      margin: 20px auto;
    }
    #print-btn {
      position: fixed;
      top: 20px;
      left: 20px;
      background-color: #004D40;
      color: white;
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      z-index: 9999;
      box-shadow: 0 0 10px #999;
    }

    @media print {
      #print-btn, form, h2, h3, button {
        display: none !important;
      }
      body {
        background: white;
      }
    }
  </style>
</head>
<body>

<h2>⚙️ تحديد حدود التنبيه والتشبع لكل صنف</h2>

<!-- زر الطباعة -->
<button onclick="window.print()" id="print-btn">🖨️ طباعة</button>

<form method="POST">
  <table>
    <tr>
      <th>اسم الصنف</th>
      <th>الحد الأدنى (تنبيه)</th>
      <th>الحد الأعلى (تشبع)</th>
    </tr>
    <?php foreach ($items as $item): ?>
      <?php
        $item_id = $item['id'];
        $min = $thresholds[$item_id]['min_threshold'] ?? 0;
        $max = $thresholds[$item_id]['max_threshold'] ?? 0;
      ?>
      <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><input type="number" name="min[<?= $item_id ?>]" value="<?= $min ?>" /></td>
        <td><input type="number" name="max[<?= $item_id ?>]" value="<?= $max ?>" /></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <button type="submit">💾 حفظ التعديلات</button>
</form>

<?php if (count($alerts) > 0): ?>
  <h3>🚨 أصناف تجاوزت حدود التنبيه أو التشبع</h3>
  <table class="alert-table">
    <tr>
      <th>اسم الصنف</th>
      <th>الكمية الحالية</th>
      <th>الحد الأدنى</th>
      <th>الحد الأعلى</th>
    </tr>
    <?php foreach ($alerts as $alert): ?>
      <tr>
        <td><?= htmlspecialchars($alert['name']) ?></td>
        <td><?= $alert['quantity'] ?></td>
        <td><?= $alert['min_threshold'] ?></td>
        <td><?= $alert['max_threshold'] ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p style="text-align: center; color: green;">✅ لا توجد أصناف تجاوزت الحدود حاليًا.</p>
<?php endif; ?>

</body>
</html>