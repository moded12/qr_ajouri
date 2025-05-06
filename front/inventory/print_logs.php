<?php
require_once('../../api/db.php');

$stmt = $pdo->query("SELECT stock_log.*, items.name FROM stock_log 
                     JOIN items ON stock_log.item_id = items.id 
                     ORDER BY stock_log.id DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>طباعة سجل الحركات</title>
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 20px;
    }
    h2 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
    }
    th, td {
      padding: 10px;
      text-align: center;
      border: 1px solid #ccc;
    }
    th {
      background-color: #004D40;
      color: white;
    }
    @media print {
      button {
        display: none;
      }
    }
  </style>
</head>
<body>

<h2>📜 سجل الحركات</h2>
<button onclick="window.print()">🖨️ طباعة</button>

<table>
  <tr>
    <th>الاسم</th>
    <th>النوع</th>
    <th>الكمية</th>
    <th>ملاحظات</th>
    <th>التاريخ</th>
  </tr>

  <?php foreach ($logs as $log): ?>
  <tr>
    <td><?= htmlspecialchars($log['name']) ?></td>
    <td><?= $log['change_type'] === 'in' ? 'إدخال' : 'إخراج' ?></td>
    <td><?= htmlspecialchars($log['amount']) ?></td>
    <td><?= htmlspecialchars($log['notes']) ?></td>
    <td><?= htmlspecialchars($log['created_at']) ?></td>
  </tr>
  <?php endforeach; ?>

</table>

</body>
</html>
