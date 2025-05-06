<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

// جلب الأصناف من قاعدة البيانات
$items = $pdo->query("SELECT id, name, quantity FROM items")->fetchAll();
$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $amount = (int)$_POST['amount'];
    $notes = $_POST['notes'];

    // جلب الكمية الحالية
    $stmt = $pdo->prepare("SELECT quantity FROM items WHERE id = ?");
    $stmt->execute([$item_id]);
    $current = $stmt->fetchColumn();

    if ($current !== false && $amount > 0 && $amount <= $current) {
        // طرح الكمية
        $stmt = $pdo->prepare("UPDATE items SET quantity = quantity - ? WHERE id = ?");
        $stmt->execute([$amount, $item_id]);

        // تسجيل الحركة
        $stmt = $pdo->prepare("INSERT INTO stock_log (item_id, change_type, amount, notes) VALUES (?, 'out', ?, ?)");
        $stmt->execute([$item_id, $amount, $notes]);

        $success = true;
    } else {
        $error = "❌ الكمية غير صالحة أو غير كافية.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>إخراج صنف</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: #f2f2f2;
      margin: 0;
      padding: 20px;
    }
    form {
      background: #fff;
      padding: 20px;
      max-width: 600px;
      margin: auto;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
    }
    h2 { color: #B71C1C; text-align: center; }
    input, select, textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    button {
      width: 100%;
      background: #B71C1C;
      color: white;
      padding: 12px;
      font-size: 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    .success-message, .error-message {
      max-width: 600px;
      margin: 10px auto;
      text-align: center;
      padding: 15px;
      font-weight: bold;
      border-radius: 8px;
    }
    .success-message {
      background: #FFEBEE;
      color: #C62828;
    }
    .error-message {
      background: #FFF3E0;
      color: #EF6C00;
    }
  </style>
</head>
<body>
  <h2>➖ إخراج صنف من المخزون</h2>
  <?php if ($success): ?>
    <div class="success-message">✔️ تم إخراج الكمية بنجاح</div>
  <?php elseif ($error): ?>
    <div class="error-message"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <label>اختر الصنف:</label>
    <select name="item_id" required>
      <option value="">اختر...</option>
      <?php foreach ($items as $item): ?>
        <option value="<?= $item['id'] ?>">
          <?= htmlspecialchars($item['name']) ?> (الكمية: <?= $item['quantity'] ?>)
        </option>
      <?php endforeach; ?>
    </select>

    <label>الكمية المراد إخراجها:</label>
    <input type="number" name="amount" required min="1">

    <label>ملاحظات:</label>
    <textarea name="notes" placeholder="سبب الإخراج أو تفاصيل إضافية"></textarea>

    <button type="submit">➖ تنفيذ الإخراج</button>
  </form>
</body>
</html>
