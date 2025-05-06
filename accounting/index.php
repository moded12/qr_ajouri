<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>قسم المحاسبة</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 0;
      padding: 0;
      background: #f4f4f4;
    }
    header {
      background-color: #004D40;
      color: white;
      padding: 15px;
      text-align: center;
      font-size: 24px;
    }
    .container {
      padding: 20px;
      max-width: 800px;
      margin: auto;
    }
    .card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 20px;
      margin-bottom: 15px;
      font-size: 18px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: background 0.2s;
    }
    .card:hover {
      background: #e0f2f1;
      cursor: pointer;
    }
    .card span {
      font-weight: bold;
      color: #004D40;
    }
  </style>
</head>
<body>
  <header>💰 قسم المحاسبة</header>
  <div class="container">
    <div class="card"><span>🧾 الفواتير</span> (مبيعات / مشتريات)</div>
    <div class="card"><span>💵 السندات</span> (سند قبض / سند صرف)</div>
    <div class="card"><span>👥 العملاء والموردين</span></div>
    <div class="card"><span>🏦 الحسابات البنكية / الصندوق</span></div>
    <div class="card"><span>💳 الشيكات</span> (من / إلى)</div>
    <div class="card"><span>📑 التقارير المحاسبية</span></div>
    <div class="card" onclick="window.location.href='../index.php'">🔁 العودة إلى قسم المخزون</div>
  </div>
</body>
</html>
