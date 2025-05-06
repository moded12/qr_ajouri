<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>نظام إدارة المخزون والمحاسبة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #004D40;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      text-align: center;
    }
    h1 {
      margin-bottom: 40px;
      font-size: 28px;
    }
    .btn {
      display: block;
      width: 250px;
      margin: 10px auto;
      padding: 15px;
      font-size: 18px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      text-decoration: none;
      color: #004D40;
    }
    .btn.inventory {
      background-color: #ffffff;
    }
    .btn.inventory:hover {
      background-color: #e0f2f1;
    }
    .btn.accounting {
      background-color: #b2dfdb;
    }
    .btn.accounting:hover {
      background-color: #80cbc4;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>نظام إدارة المخزون والمحاسبة</h1>
    <a href="/qr_ajouri/front/inventory/index.php" class="btn inventory">دخول إلى قسم المخزون</a>
    <a href="/qr_ajouri/accounting/index.php" class="btn accounting">دخول إلى قسم المحاسبة</a>
  </div>
</body>
</html>
