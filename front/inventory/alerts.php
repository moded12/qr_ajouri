<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

// استعلام لجلب كل الأصناف وحدودها
$sql = "
SELECT i.name, i.quantity, t.min_quantity, t.max_quantity
FROM items i
JOIN stock_thresholds t ON i.id = t.item_id
WHERE i.quantity <= t.min_quantity OR i.quantity >= t.max_quantity
";
$items = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الأصناف التي تجاوزت الحدود</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #f4f4f4;
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #B71C1C;
      color: white;
    }
    h2 {
      text-align: center;
      color: #B71C1C;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<h2>🚨 الأصناف التي تجاوزت حدود التنبيه أو التشبع</h2>

<?php if (count($items) > 0): ?>
<table>
  <tr>
    <th>اسم الصنف</th>
    <th>الكمية الحالية</th>
    <th>الحد الأدنى</th>
    <th>الحد الأعلى</th>
  </tr>
  <?php foreach ($items as $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['name']) ?></td>
      <td><?= $item['quantity'] ?></td>
      <td><?= $item['min_quantity'] ?></td>
      <td><?= $item['max_quantity'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<?php else: ?>
  <p style="text-align: center; color: green;">✅ لا توجد أصناف خارجة عن الحدود.</p>
<?php endif; ?>

</body>
</html>