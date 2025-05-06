<?php
require_once('../../api/db.php');

if (!isset($_GET['id'])) {
  die("رقم الصنف غير محدد.");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
  die("الصنف غير موجود.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $quantity = $_POST['quantity'];
  $unit = $_POST['unit'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $location = $_POST['location'];
  $notes = $_POST['notes'];

  $update = $pdo->prepare("UPDATE items SET name=?, quantity=?, unit=?, category=?, price=?, location=?, notes=? WHERE id=?");
  $update->execute([$name, $quantity, $unit, $category, $price, $location, $notes, $id]);

  echo "<p style='color: green; text-align: center;'>✅ تم تعديل الصنف بنجاح</p>";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تعديل صنف</title>
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 30px;
    }
    form {
      max-width: 600px;
      margin: auto;
      background: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
      border: 1px solid #ccc;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      box-sizing: border-box;
    }
    button {
      background-color: #004D40;
      color: white;
      padding: 10px 20px;
      border: none;
      font-size: 16px;
      cursor: pointer;
      border-radius: 8px;
    }
    button:hover {
      background-color: #00695C;
    }
  </style>
</head>
<body>

<h2 style="text-align: center;">✏️ تعديل صنف</h2>

<form method="post">
  <label>الاسم</label>
  <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>

  <label>الكمية</label>
  <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" required>

  <label>الوحدة</label>
  <input type="text" name="unit" value="<?= htmlspecialchars($item['unit']) ?>">

  <label>التصنيف</label>
  <input type="text" name="category" value="<?= htmlspecialchars($item['category']) ?>">

  <label>السعر</label>
  <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($item['price']) ?>">

  <label>الموقع</label>
  <input type="text" name="location" value="<?= htmlspecialchars($item['location']) ?>">

  <label>ملاحظات</label>
  <textarea name="notes"><?= htmlspecialchars($item['notes']) ?></textarea>

  <button type="submit">💾 حفظ التعديلات</button>
</form>

</body>
</html>
