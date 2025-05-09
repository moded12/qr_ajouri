<?php
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->query("SELECT id FROM notification_recipients");
  $all_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
  foreach ($all_ids as $id) {
    $active = in_array($id, $_POST['recipients'] ?? []) ? 1 : 0;
    $update = $pdo->prepare("UPDATE notification_recipients SET active = ? WHERE id = ?");
    $update->execute([$active, $id]);
  }
  $success = true;
}

$recipients = $pdo->query("SELECT * FROM notification_recipients")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إعدادات إرسال التنبيهات</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: #f0f0f0;
      padding: 20px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="text-center text-primary mb-4">💬 إعدادات إرسال التنبيهات عبر واتساب</h2>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success text-center">✅ تم حفظ التغييرات بنجاح</div>
  <?php endif; ?>

  <form method="post" class="bg-white p-4 rounded shadow-sm">
    <?php foreach ($recipients as $r): ?>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="recipients[]" value="<?= $r['id'] ?>" <?= $r['active'] ? 'checked' : '' ?> id="chk<?= $r['id'] ?>">
        <label class="form-check-label" for="chk<?= $r['id'] ?>">
          <?= htmlspecialchars($r['name']) ?> - <?= htmlspecialchars($r['phone']) ?>
        </label>
      </div>
    <?php endforeach; ?>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary">💾 حفظ التغييرات</button>
    </div>
  </form>
</div>

</body>
</html>