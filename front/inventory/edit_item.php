
<?php
// FILE: edit_item.php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$id = $_GET['id'] ?? null;

if (!$id) {
  die("معرف الصنف غير موجود.");
}

$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
  die("الصنف غير موجود.");
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$locations = $pdo->query("SELECT * FROM locations")->fetchAll();
$units = $pdo->query("SELECT * FROM units")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $model = $_POST['model'];
  $category_id = $_POST['category_id'];
  $unit_id = $_POST['unit_id'];
  $location_id = $_POST['location_id'];
  $quantity = $_POST['quantity'];
  $price = $_POST['price'];
  $barcode = $_POST['barcode'];
  $serial = $_POST['serial_number'];

  $update = $pdo->prepare("UPDATE items SET name=?, model=?, category_id=?, unit_id=?, location_id=?, quantity=?, price=?, barcode=?, serial_number=? WHERE id=?");
  $success = $update->execute([$name, $model, $category_id, $unit_id, $location_id, $quantity, $price, $barcode, $serial, $id]);

  if ($success) {
    echo "<script>alert('✅ تم تحديث الصنف بنجاح'); window.location.href='list_items.php';</script>";
    exit;
  } else {
    echo "<script>alert('❌ فشل التحديث');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تعديل صنف</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: #f4f4f4;
      padding: 20px;
    }
    form {
      background: white;
      padding: 20px;
      border-radius: 10px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 0 10px #ccc;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 6px;
      font-size: 16px;
      border: 1px solid #ccc;
    }
    button {
      background: #00796b;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 6px;
    }
    button:hover {
      background: #00695c;
    }
  </style>
</head>
<body>

<h2 style="text-align:center;">✏️ تعديل صنف</h2>

<form method="POST">
  <label>الاسم:</label>
  <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>

  <label>الموديل:</label>
  <input type="text" name="model" value="<?= htmlspecialchars($item['model']) ?>">

  <label>التصنيف:</label>
  <select name="category_id" required>
    <?php foreach ($categories as $cat): ?>
      <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $item['category_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($cat['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>الوحدة:</label>
  <select name="unit_id" required>
    <?php foreach ($units as $u): ?>
      <option value="<?= $u['id'] ?>" <?= $u['id'] == $item['unit_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($u['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>الموقع:</label>
  <select name="location_id" required>
    <?php foreach ($locations as $l): ?>
      <option value="<?= $l['id'] ?>" <?= $l['id'] == $item['location_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($l['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>الكمية:</label>
  <input type="number" name="quantity" value="<?= $item['quantity'] ?>" required>

  <label>السعر:</label>
  <input type="number" step="0.01" name="price" value="<?= $item['price'] ?>" required>

  <label>الباركود:</label>
  <input type="text" name="barcode" value="<?= htmlspecialchars($item['barcode']) ?>" required>

  <label>السيريال:</label>
  <input type="text" name="serial_number" value="<?= htmlspecialchars($item['serial_number']) ?>">

  <button type="submit">💾 حفظ التعديل</button>
</form>

</body>
</html>
