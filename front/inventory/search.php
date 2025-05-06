<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$category = $_GET['category'] ?? '';
$location = $_GET['location'] ?? '';
$unit = $_GET['unit'] ?? '';
$search = $_GET['search'] ?? '';

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
if ($search !== '') {
  $conditions[] = '(items.name LIKE ? OR items.barcode LIKE ?)';
  $params[] = "%$search%";
  $params[] = "%$search%";
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

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

$units = $pdo->query("SELECT * FROM units")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$locations = $pdo->query("SELECT * FROM locations")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>بحث في الأصناف</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
</head>
<body>

<div class="header-wrapper">
  <div class="page-header">
    <h2>🔍 البحث عن صنف</h2>
  </div>
</div>

<form class="filters" method="get">
  <select name="category" onchange="this.form.submit()">
    <option value="">كل التصنيفات</option>
    <?php foreach ($categories as $cat): ?>
      <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category ? 'selected' : '' ?>>
        <?= htmlspecialchars($cat['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <select name="location" onchange="this.form.submit()">
    <option value="">كل المواقع</option>
    <?php foreach ($locations as $loc): ?>
      <option value="<?= $loc['id'] ?>" <?= $loc['id'] == $location ? 'selected' : '' ?>>
        <?= htmlspecialchars($loc['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <select name="unit" onchange="this.form.submit()">
    <option value="">كل الوحدات</option>
    <?php foreach ($units as $uni): ?>
      <option value="<?= $uni['id'] ?>" <?= $uni['id'] == $unit ? 'selected' : '' ?>>
        <?= htmlspecialchars($uni['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <input type="text" name="search" placeholder="ابحث بالاسم أو الباركود" value="<?= htmlspecialchars($search) ?>">
  <button type="submit">بحث</button>
</form>

<?php if (!empty($items)): ?>
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
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td data-label="الباركود"><?= htmlspecialchars($item['barcode']) ?></td>
          <td data-label="الاسم"><?= htmlspecialchars($item['name']) ?></td>
          <td data-label="الكمية"><?= htmlspecialchars($item['quantity']) ?></td>
          <td data-label="الوحدة"><?= htmlspecialchars($item['unit_name']) ?></td>
          <td data-label="التصنيف"><?= htmlspecialchars($item['category_name']) ?></td>
          <td data-label="الموقع"><?= htmlspecialchars($item['location_name']) ?></td>
          <td data-label="السعر"><?= htmlspecialchars($item['price']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php elseif ($_GET): ?>
  <p style="text-align:center;color:#C62828;">⚠️ لا يوجد نتائج مطابقة.</p>
<?php endif; ?>

</body>
</html>