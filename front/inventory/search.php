<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$results = [];
$search = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $search = trim($_POST['search']);
  $stmt = $pdo->prepare("SELECT * FROM items WHERE barcode LIKE ? OR name LIKE ?");
  $stmt->execute(["%$search%", "%$search%"]);
  $results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>بحث في الأصناف</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 0;
      background: #f2f2f2;
      padding: 20px;
    }
    h2 {
      color: #004D40;
      text-align: center;
    }
    form {
      max-width: 600px;
      margin: 0 auto 20px auto;
      display: flex;
      gap: 10px;
      flex-direction: column;
    }
    input[type="text"] {
      padding: 12px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
      flex: 1;
    }
    button {
      background: #00796B;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }
    table {
      width: 100%;
      background: white;
      border-collapse: collapse;
      margin-top: 20px;
      box-shadow: 0 0 10px #ccc;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }
    th {
      background: #00796B;
      color: white;
    }
    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }
      tr {
        margin-bottom: 10px;
      }
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
        color: #333;
      }
      th { display: none; }
    }
  </style>
</head>
<body>
  <h2>🔍 البحث عن صنف</h2>
  <form method="POST">
    <input type="text" name="search" placeholder="أدخل الباركود أو اسم الصنف" value="<?= htmlspecialchars($search) ?>" required>
    <button type="submit">بحث</button>
  </form>

  <?php if (!empty($results)): ?>
    <table>
      <thead>
        <tr>
          <th>الباركود</th>
          <th>الاسم</th>
          <th>الكمية</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($results as $item): ?>
          <tr>
            <td data-label="الباركود"><?= $item['barcode'] ?></td>
            <td data-label="الاسم"><?= $item['name'] ?></td>
            <td data-label="الكمية"><?= $item['quantity'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p style="text-align:center;color:#C62828;">⚠️ لا يوجد نتائج مطابقة.</p>
  <?php endif; ?>
</body>
</html>
