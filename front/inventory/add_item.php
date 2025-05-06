<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

// جلب القوائم المنسدلة
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$units = $pdo->query("SELECT * FROM units")->fetchAll();
$locations = $pdo->query("SELECT * FROM locations")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة صنف جديد</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: #f8f9fa;
      padding: 30px;
    }
    form {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
    }
    input, select, textarea, button {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      font-family: inherit;
    }
    button {
      background: #007BFF;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    h2 {
      text-align: center;
      color: #004085;
    }
  </style>
</head>
<body>

<h2>➕ إضافة صنف جديد</h2>

<form action="insert_item.php" method="POST">
  <label>اسم الصنف:</label>
  <input type="text" name="name" required>

  <label>الكمية:</label>
  <input type="number" name="quantity" required>

  <label>السعر:</label>
  <input type="number" name="price" step="0.01" required>

  <label>التصنيف:</label>
  <select name="category_id" required>
    <option value="">اختر تصنيف</option>
    <?php foreach($categories as $c): ?>
      <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
    <?php endforeach; ?>
  </select>

  <label>الوحدة:</label>
  <select name="unit_id" required>
    <option value="">اختر وحدة</option>
    <?php foreach($units as $u): ?>
      <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
    <?php endforeach; ?>
  </select>

  <label>الموقع:</label>
  <select name="location_id" required>
    <option value="">اختر موقع</option>
    <?php foreach($locations as $l): ?>
      <option value="<?= $l['id'] ?>"><?= $l['name'] ?></option>
    <?php endforeach; ?>
  </select>

  <label>ملاحظات:</label>
  <textarea name="notes"></textarea>

  <button type="submit">إضافة الصنف ✅</button>
</form>

</body>
</html>