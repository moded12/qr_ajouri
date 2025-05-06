<?php
require_once('../../api/db.php');
header('Content-Type: text/html; charset=utf-8');

// جلب الأصناف مع تفاصيل الوحدة والتصنيف والموقع
$sql = "SELECT items.*, 
               units.name AS unit_name, 
               categories.name AS category_name, 
               locations.name AS location_name
        FROM items
        LEFT JOIN units ON items.unit_id = units.id
        LEFT JOIN categories ON items.category_id = categories.id
        LEFT JOIN locations ON items.location_id = locations.id
        ORDER BY items.id DESC";

$items = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>طباعة الأصناف</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 20px;
      background: white;
    }
    h2 {
      text-align: center;
      color: #004D40;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 0 10px #ccc;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      text-align: center;
      border: 1px solid #ccc;
      font-size: 14px;
    }
    th {
      background-color: #004D40;
      color: white;
    }
    @media print {
      button {
        display: none;
      }
    }
  </style>
</head>
<body>

<h2>🖨️ قائمة الأصناف القابلة للطباعة</h2>
<button onclick="window.print()">🖨️ طباعة</button>

<table>
  <thead>
    <tr>
      <th>الباركود</th>
      <th>الاسم</th>
      <th>الكمية</th>
      <th>الوحدة</th>
      <th>السعر</th>
      <th>التصنيف</th>
      <th>الموقع</th>
      <th>ملاحظات</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['barcode']) ?></td>
      <td><?= htmlspecialchars($item['name']) ?></td>
      <td><?= htmlspecialchars($item['quantity']) ?></td>
      <td><?= htmlspecialchars($item['unit_name']) ?></td>
      <td><?= htmlspecialchars($item['price']) ?></td>
      <td><?= htmlspecialchars($item['category_name']) ?></td>
      <td><?= htmlspecialchars($item['location_name']) ?></td>
      <td><?= htmlspecialchars($item['notes']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>