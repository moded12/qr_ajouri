<?php
// FILE: front/inventory/print_logs.php
require_once('../../api/db.php');
header('Content-Type: text/html; charset=utf-8');

$stmt = $pdo->query("SELECT stock_log.*, items.name 
                     FROM stock_log 
                     JOIN items ON stock_log.item_id = items.id 
                     ORDER BY stock_log.id DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>طباعة سجل الحركات</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 20px;
      background-color: white;
    }

    h2 {
      text-align: center;
      color: #004D40;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    th, td {
      border: 1px solid #aaa;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #004D40;
      color: white;
    }

    button {
      display: block;
      margin: 20px auto;
      padding: 8px 25px;
      font-size: 16px;
      background-color: #0d6efd;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    @media print {
      button {
        display: none;
      }

      th {
        background-color: #004D40 !important;
        -webkit-print-color-adjust: exact;
      }
    }
  </style>
</head>
<body>

<h2>📜 سجل الحركات</h2>
<button onclick="window.print()">🖨️ طباعة</button>

<table>
  <thead>
    <tr>
      <th>م</th>
      <th>اسم الصنف</th>
      <th>النوع</th>
      <th>الكمية</th>
      <th>التاريخ</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($logs): foreach ($logs as $i => $log): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= htmlspecialchars($log['name']) ?></td>
        <td><?= $log['type'] == 'in' ? 'إدخال' : 'إخراج' ?></td>
        <td><?= htmlspecialchars($log['quantity']) ?></td>
        <td><?= htmlspecialchars($log['created_at']) ?></td>
      </tr>
    <?php endforeach; else: ?>
      <tr>
        <td colspan="5" class="text-danger">لا توجد حركات</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>