
<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$search = $_GET['search'] ?? '';

$where = "";
$params = [];
if (!empty($search)) {
  $where = "WHERE items.name LIKE ? OR items.model LIKE ? OR items.barcode LIKE ? OR items.serial_number LIKE ? OR categories.name LIKE ? OR locations.name LIKE ?";
  for ($i = 0; $i < 6; $i++) $params[] = "%$search%";
}

$stmt = $pdo->prepare("SELECT items.*, 
                              units.name AS unit_name, 
                              categories.name AS category_name, 
                              locations.name AS location_name
                       FROM items
                       LEFT JOIN units ON items.unit_id = units.id
                       LEFT JOIN categories ON items.category_id = categories.id
                       LEFT JOIN locations ON items.location_id = locations.id
                       $where
                       ORDER BY items.id DESC");
$stmt->execute($params);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>قائمة الأصناف</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f4f4f4; padding: 20px; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    th { background: #0288d1; color: white; }
    input[type="text"] {
      width: 100%; max-width: 400px; padding: 10px; font-size: 16px;
      margin: 15px 0; border-radius: 5px; border: 1px solid #ccc;
    }
    .barcode-img { display: block; margin: auto; }
    .btn { background: #00796b; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
    .btn:hover { background: #004d40; }
  </style>
</head>
<body>

<h2 style="text-align:center;">📋 قائمة الأصناف</h2>

<form method="get" style="text-align:center;">
  <input type="text" name="search" placeholder="اكتب للبحث بالاسم، الموديل، التصنيف، الموقع، الباركود..." value="<?= htmlspecialchars($search) ?>">
</form>

<?php if (!empty($items)): ?>
<table>
  <thead>
    <tr>
      <th>الباركود</th>
      <th>الاسم</th>
      <th>الموديل</th>
      <th>السيريال</th>
      <th>الكمية</th>
      <th>الوحدة</th>
      <th>السعر</th>
      <th>التصنيف</th>
      <th>الموقع</th>
      <th>تعديل</th>
      <th>حذف</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $item): ?>
      <tr>
        <td>
          <img src="https://chart.googleapis.com/chart?cht=ean13&chs=150x70&chl=<?= urlencode($item['barcode']) ?>" class="barcode-img" alt="barcode"><br>
          <?= htmlspecialchars($item['barcode']) ?><br>
          <a class="btn" href="print_barcode.php?id=<?= $item['id'] ?>" target="_blank">🖨️ طباعة</a>
        </td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= htmlspecialchars($item['model']) ?></td>
        <td><?= htmlspecialchars($item['serial_number']) ?></td>
        <td><?= htmlspecialchars($item['quantity']) ?></td>
        <td><?= htmlspecialchars($item['unit_name']) ?></td>
        <td><?= htmlspecialchars($item['price']) ?> د.أ</td>
        <td><?= htmlspecialchars($item['category_name']) ?></td>
        <td><?= htmlspecialchars($item['location_name']) ?></td>
        <td><a href="edit_item.php?id=<?= $item['id'] ?>" class="btn">✏️</a></td>
        <td><a href="delete_item.php?id=<?= $item['id'] ?>" class="btn" onclick="return confirm('هل أنت متأكد من الحذف؟')">🗑️</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
  <p style="text-align:center; color:#C62828;">⚠️ لا توجد أصناف.</p>
<?php endif; ?>

</body>
</html>
