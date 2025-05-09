
<?php
// FILE: manual_remove.php (advanced version)
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$locations = $pdo->query("SELECT * FROM locations")->fetchAll();
$units = $pdo->query("SELECT * FROM units")->fetchAll();
$models = $pdo->query("SELECT DISTINCT model FROM items WHERE model IS NOT NULL AND model != '' ORDER BY model")->fetchAll();
$names = $pdo->query("SELECT DISTINCT name FROM items WHERE name IS NOT NULL AND name != '' ORDER BY name")->fetchAll();

$items = $pdo->query("SELECT items.*, 
                             units.name AS unit_name,
                             categories.name AS category_name,
                             locations.name AS location_name
                      FROM items
                      LEFT JOIN units ON items.unit_id = units.id
                      LEFT JOIN categories ON items.category_id = categories.id
                      LEFT JOIN locations ON items.location_id = locations.id
                      ORDER BY items.name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إخراج صنف يدويًا - متقدم</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: #f4f4f4;
      padding: 20px;
    }
    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
      justify-content: center;
    }
    .filters select, .filters input {
      padding: 10px;
      border-radius: 6px;
      font-size: 15px;
      border: 1px solid #ccc;
      min-width: 150px;
    }
    .box {
      background: white;
      border-radius: 10px;
      padding: 10px;
      margin: 10px auto;
      max-width: 600px;
      box-shadow: 0 0 8px #ccc;
    }
    .box h4 {
      margin: 0;
      color: #004D40;
    }
    .box small {
      color: #666;
    }
    .box form {
      margin-top: 10px;
    }
    .box input[type='number'] {
      width: 80px;
      padding: 5px;
    }
    .box button {
      background: #00796b;
      color: white;
      padding: 8px 14px;
      border: none;
      border-radius: 6px;
    }
    .box button:hover {
      background: #00695c;
    }
    .hidden { display: none; }
  </style>
</head>
<body>

<h2 style="text-align:center;">📤 إخراج صنف يدويًا - متقدم</h2>

<div class="filters">
  <select id="filterCategory">
    <option value="">كل التصنيفات</option>
    <?php foreach ($categories as $c): ?>
      <option value="<?= $c['name'] ?>"><?= htmlspecialchars($c['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <select id="filterLocation">
    <option value="">كل المواقع</option>
    <?php foreach ($locations as $l): ?>
      <option value="<?= $l['name'] ?>"><?= htmlspecialchars($l['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <select id="filterUnit">
    <option value="">كل الوحدات</option>
    <?php foreach ($units as $u): ?>
      <option value="<?= $u['name'] ?>"><?= htmlspecialchars($u['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <select id="filterModel">
    <option value="">كل الموديلات</option>
    <?php foreach ($models as $m): ?>
      <option value="<?= $m['model'] ?>"><?= htmlspecialchars($m['model']) ?></option>
    <?php endforeach; ?>
  </select>

  <select id="filterName">
    <option value="">كل الأسماء</option>
    <?php foreach ($names as $n): ?>
      <option value="<?= $n['name'] ?>"><?= htmlspecialchars($n['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <input type="text" id="searchBox" placeholder="اكتب للبحث الفوري...">
</div>

<div id="itemsContainer">
  <?php foreach ($items as $item): ?>
    <div class="box" 
         data-name="<?= strtolower($item['name']) ?>" 
         data-model="<?= strtolower($item['model']) ?>" 
         data-category="<?= strtolower($item['category_name']) ?>"
         data-location="<?= strtolower($item['location_name']) ?>"
         data-unit="<?= strtolower($item['unit_name']) ?>">
      <h4><?= htmlspecialchars($item['name']) ?> - <?= htmlspecialchars($item['model']) ?></h4>
      <small>باركود: <?= $item['barcode'] ?> | الكمية: <?= $item['quantity'] ?> | <?= $item['category_name'] ?> | <?= $item['location_name'] ?></small>
      <form method="POST" action="confirm_remove.php" style="margin-top:10px;">
        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
        <input type="number" name="remove_quantity" min="1" required placeholder="الكمية">
        <button type="submit">تأكيد الإخراج</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>

<script>
  const boxes = document.querySelectorAll('.box');
  const filters = {
    category: document.getElementById('filterCategory'),
    location: document.getElementById('filterLocation'),
    unit: document.getElementById('filterUnit'),
    model: document.getElementById('filterModel'),
    name: document.getElementById('filterName'),
    search: document.getElementById('searchBox')
  };

  Object.values(filters).forEach(input => {
    input.addEventListener('input', filterResults);
  });

  function filterResults() {
    const cat = filters.category.value.toLowerCase();
    const loc = filters.location.value.toLowerCase();
    const unit = filters.unit.value.toLowerCase();
    const model = filters.model.value.toLowerCase();
    const name = filters.name.value.toLowerCase();
    const search = filters.search.value.toLowerCase();

    boxes.forEach(box => {
      const matches = (
        (cat === '' || box.dataset.category.includes(cat)) &&
        (loc === '' || box.dataset.location.includes(loc)) &&
        (unit === '' || box.dataset.unit.includes(unit)) &&
        (model === '' || box.dataset.model.includes(model)) &&
        (name === '' || box.dataset.name.includes(name)) &&
        (search === '' || 
          box.dataset.name.includes(search) || 
          box.dataset.model.includes(search) || 
          box.textContent.toLowerCase().includes(search))
      );
      box.style.display = matches ? 'block' : 'none';
    });
  }
</script>

</body>
</html>
