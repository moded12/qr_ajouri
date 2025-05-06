<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$category = $_GET['category'] ?? '';
$location = $_GET['location'] ?? '';
$unit = $_GET['unit'] ?? '';
$min_quantity = $_GET['min_quantity'] ?? '';

$conditions = [];
$params = [];

if ($category !== '') {
  $conditions[] = 'items.category_id = ?';
  $params[] = $category;
}
if ($location !== '') {
  $conditions[] = 'items.location_id = ?';
  $params[] = $location;
}
if ($unit !== '') {
  $conditions[] = 'items.unit_id = ?';
  $params[] = $unit;
}
if ($min_quantity !== '') {
  $conditions[] = 'items.quantity <= ?';
  $params[] = $min_quantity;
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$stmt = $pdo->prepare("SELECT items.*, units.name AS unit_name, categories.name AS category_name, locations.name AS location_name
                       FROM items
                       LEFT JOIN units ON items.unit_id = units.id
                       LEFT JOIN categories ON items.category_id = categories.id
                       LEFT JOIN locations ON items.location_id = locations.id
                       $where
                       ORDER BY items.id DESC");
$stmt->execute($params);
$items = $stmt->fetchAll();

$units = $pdo->query("SELECT * FROM units")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$locations = $pdo->query("SELECT * FROM locations")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>عرض الأصناف</title>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: #f2f2f2;
      margin: 0;
      padding: 0;
    }
    .header-wrapper {
      background: #e0f2f1;
      border-bottom: 1px solid #ccc;
    }
    .page-header {
      max-width: 1000px;
      margin: auto;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .page-header h2 {
      margin: 0;
      color: #004D40;
      font-size: 22px;
    }
    .print-button {
      background-color: #004D40;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
    }
    table {
      width: 100%;
      background: white;
      border-collapse: collapse;
      box-shadow: 0 0 10px #ccc;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
      word-break: break-word;
    }
    th {
      background: #00796B;
      color: white;
    }
    .actions a {
      margin: 0 5px;
      text-decoration: none;
      font-size: 18px;
    }
    .edit { color: #1565C0; }
    .delete { color: #C62828; }
    svg {
      max-width: 100%;
      height: auto;
      display: block;
      margin: auto;
    }
    .barcode-label {
      font-weight: bold;
      margin-bottom: 5px;
    }
    .serial-label {
      font-size: 13px;
      color: #555;
      margin-top: 5px;
    }
    @media (max-width: 768px) {
      .page-header {
        flex-direction: column;
        align-items: center;
      }
      table, thead, tbody, th, td, tr {
        display: block;
      }
      tr { margin-bottom: 10px; }
      td {
        text-align: right;
        padding-right: 50%;
        position: relative;
      }
      td::before {
        content: attr(data-label);
        position: absolute;
        right: 10px;
        font-weight: bold;
      }
      th { display: none; }
    }
    @media print {
      body * { visibility: hidden; }
      table, table * { visibility: visible; }
      table {
        position: absolute;
        top: 0;
        left: 0;
      }
      .header-wrapper {
        display: none !important;
      }
    }
  </style>
</head>
<body>

<div class="header-wrapper">
  <div class="page-header">
    <h2>📦 قائمة الأصناف</h2>
    <button onclick="window.print()" class="print-button">🖨️ طباعة</button>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>الباركود</th>
      <th>الاسم</th>
      <th>الكمية</th>
      <th>الوحدة</th>
      <th>التصنيف</th>
      <th>الموقع</th>
      <th>السعر</th>
      <th>إجراءات</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $item): ?>
      <tr>
        <td data-label="الباركود">
          <div class="barcode-label"><?= htmlspecialchars($item['name']) ?></div>
          <svg id="barcode-<?= $item['id'] ?>"></svg>
          <div class="serial-label">السيريال: <?= htmlspecialchars($item['serial_number'] ?? '') ?></div>
        </td>
        <td data-label="الاسم"><?= htmlspecialchars($item['name']) ?></td>
        <td data-label="الكمية"><?= htmlspecialchars($item['quantity']) ?></td>
        <td data-label="الوحدة"><?= htmlspecialchars($item['unit_name']) ?></td>
        <td data-label="التصنيف"><?= htmlspecialchars($item['category_name']) ?></td>
        <td data-label="الموقع"><?= htmlspecialchars($item['location_name']) ?></td>
        <td data-label="السعر"><?= htmlspecialchars($item['price']) ?></td>
        <td class="actions" data-label="إجراءات">
          <a href="edit_item.php?id=<?= $item['id'] ?>" class="edit">🖊</a>
          <a href="delete_item.php?id=<?= $item['id'] ?>" class="delete" onclick="return confirm('هل أنت متأكد من حذف هذا الصنف؟')">🗑</a>
          <a href="print_barcode.php?id=<?= $item['id'] ?>" target="_blank" title="طباعة الباركود">🖨️</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
<?php foreach ($items as $item): ?>
  JsBarcode("#barcode-<?= $item['id'] ?>", "<?= $item['barcode'] ?>", {
    format: "CODE128",
    lineColor: "#000",
    width: 2,
    height: 40,
    displayValue: false
  });
<?php endforeach; ?>
</script>

</body>
</html>