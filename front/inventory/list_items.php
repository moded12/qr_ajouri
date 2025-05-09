
<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$items = $pdo->query("SELECT items.*, 
                             units.name AS unit_name, 
                             categories.name AS category_name, 
                             locations.name AS location_name
                      FROM items
                      LEFT JOIN units ON items.unit_id = units.id
                      LEFT JOIN categories ON items.category_id = categories.id
                      LEFT JOIN locations ON items.location_id = locations.id
                      ORDER BY items.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>قائمة الأصناف - QR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f4f4f4; padding: 20px; }
    h2 { text-align: center; color: #004D40; }
    input[type="text"] {
      width: 100%; max-width: 500px; padding: 10px; margin: 15px auto; display: block;
      font-size: 18px; border-radius: 8px; border: 1px solid #ccc;
    }
    table {
      width: 100%; border-collapse: collapse; margin-top: 20px; background: white;
      box-shadow: 0 0 10px #ccc; border-radius: 10px; overflow: hidden;
    }
    th, td {
      border: 1px solid #ccc; padding: 10px; text-align: center;
    }
    th { background: #00796b; color: white; }
    .btn { padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; color: white; }
    .edit { background: orange; }
    .delete { background: red; }
    .print { background: green; }
  </style>
</head>
<body>

<h2>📋 قائمة الأصناف (QR)</h2>

<input type="text" id="searchInput" placeholder="🔍 اكتب للبحث...">

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
<table id="itemsTable">
  <thead>
    <tr>
      <th>QR</th>
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
    <?php foreach ($items as $index => $item): ?>
      <tr>
        <td>
          <div id="qr-<?= $index ?>"></div>
          <script>
            QRCode.toCanvas(document.getElementById("qr-<?= $index ?>"), "<?= $item['barcode'] ?>");
          </script>
          <br>
          <a href="print_barcode.php?id=<?= $item['id'] ?>" target="_blank" class="btn print">🖨️ طباعة</a>
        </td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= htmlspecialchars($item['model']) ?></td>
        <td><?= htmlspecialchars($item['serial_number']) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= htmlspecialchars($item['unit_name']) ?></td>
        <td><?= number_format($item['price'], 2) ?> د.أ</td>
        <td><?= htmlspecialchars($item['category_name']) ?></td>
        <td><?= htmlspecialchars($item['location_name']) ?></td>
        <td>
          <a href="edit_item.php?id=<?= $item['id'] ?>"><button class="btn edit">✏️</button></a>
        </td>
        <td>
          <a href="delete_item.php?id=<?= $item['id'] ?>" onclick="return confirm('هل أنت متأكد؟')">
            <button class="btn delete">🗑️</button>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
document.getElementById("searchInput").addEventListener("input", function() {
  const value = this.value.toLowerCase();
  document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
    const text = row.innerText.toLowerCase();
    row.style.display = text.includes(value) ? "" : "none";
  });
});
</script>

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