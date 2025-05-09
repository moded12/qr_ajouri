<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$stmt = $pdo->query("SELECT stock_log.*, items.name AS item_name
                     FROM stock_log
                     JOIN items ON stock_log.item_id = items.id
                     ORDER BY stock_log.created_at DESC");
$logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>سجل الحركات</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: #f2f2f2;
      margin: 0;
      padding: 0;
    }

    .page-header {
      background-color: #e0f2f1;
      padding: 10px;
      border-bottom: 1px solid #ccc;
      display: flex;
      justify-content: center; /* المركزية للحاوية */
      align-items: center;
      overflow: hidden;
    }

    .page-header .header-container {
      display: flex;
      justify-content: space-between; /* توزيع العناصر داخل الإطار */
      align-items: center;
      width: 100%; /* عرض الحاوية بالكامل */
      max-width: 1200px; /* تحديد الحد الأقصى للعرض */
      padding: 0 20px; /* مسافة داخلية على الجوانب */
      box-sizing: border-box; /* تشمل الحواف في الحساب */
    }

    .page-header h2 {
      margin: 0;
      color: #004D40;
      font-size: 22px;
      white-space: nowrap; /* منع الانتقال إلى سطر جديد */
    }

    .page-header button {
      background-color: #004D40;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      white-space: nowrap; /* منع النص داخل الزر من الانتقال إلى سطر جديد */
    }

    table {
      width: 100%;
      background: white;
      border-collapse: collapse;
      margin: 0;
      box-shadow: 0 0 10px #ccc;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background: #00796B;
      color: white;
    }

    .in { color: green; font-weight: bold; }
    .out { color: red; font-weight: bold; }

    @media (max-width: 768px) {
      .page-header .header-container {
        flex-direction: column; /* العناصر تصبح عمودية على الشاشات الصغيرة */
        align-items: center;
      }

      .page-header h2 {
        margin-bottom: 10px;
      }

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

      th {
        display: none;
      }
    }

    @media print {
      body * {
        visibility: hidden;
      }

      table, table * {
        visibility: visible;
      }

      table {
        position: absolute;
        top: 0;
        left: 0;
      }

      .page-header {
        display: none !important;
      }
    }
  </style>
</head>
<body>

<div class="page-header">
  <div class="header-container">
    <h2>📜 سجل حركات المخزون</h2>
    <button onclick="window.print()">🖨️ طباعة</button>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>الصنف</th>
      <th>النوع</th>
      <th>الكمية</th>
      <th>ملاحظات</th>
      <th>التاريخ</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($logs as $log): ?>
      <tr>
        <td data-label="الصنف"><?= htmlspecialchars($log['item_name']) ?></td>
        <td data-label="النوع" class="<?= $log['change_type'] ?>">
          <?= $log['change_type'] === 'in' ? 'إدخال' : 'إخراج' ?>
        </td>
        <td data-label="الكمية"><?= htmlspecialchars($log['amount']) ?></td>
        <td data-label="ملاحظات"><?= htmlspecialchars($log['notes']) ?></td>
        <td data-label="التاريخ"><?= htmlspecialchars($log['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>