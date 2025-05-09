<?php
// FILE: front/inventory/print_list.php
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
  <title>طباعة قائمة الأصناف</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&family=Libre+Barcode+39&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 20px;
      background: white;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #004D40;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    th, td {
      border: 1px solid #aaa;
      padding: 8px;
      text-align: center;
    }

    th {
      background-color: #004D40;
      color: white;
    }

    .barcode {
      font-family: 'Libre Barcode 39', cursive;
      font-size: 32px;
      display: block;
      margin: 5px 0;
    }

    .serial {
      font-size: 12px;
      color: #555;
    }

    @media print {
      body {
        margin: 0;
      }

      th {
        background-color: #004D40 !important;
        -webkit-print-color-adjust: exact;
      }
    }
  </style>
</head>
<body>
  <h2>قائمة الأصناف - للطباعة</h2>

<table>
  <thead>
    <tr>
      <th>م</th>
      <th>الاسم</th>
      <th>الموديل</th>
      <th>الباركود</th>
      <th>السيريال</th>
      <th>الكمية</th>
      <th>الوحدة</th>
      <th>التصنيف</th>
      <th>الموقع</th>
      <th>السعر</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($items): foreach ($items as $i => $item): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= htmlspecialchars($item['model']) ?></td>
        <td>
          <div class="barcode">*<?= htmlspecialchars($item['barcode']) ?>*</div>
        </td>
        <td class="serial"><?= htmlspecialchars($item['serial_number']) ?></td>
        <td><?= htmlspecialchars($item['quantity']) ?></td>
        <td><?= htmlspecialchars($item['unit_name']) ?></td>
        <td><?= htmlspecialchars($item['category_name']) ?></td>
        <td><?= htmlspecialchars($item['location_name']) ?></td>
        <td><?= htmlspecialchars($item['price']) ?> د.أ</td>
      </tr>
    <?php endforeach; else: ?>
      <tr>
        <td colspan="10" class="text-danger text-center">لا توجد بيانات</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>