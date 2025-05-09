<?php
// FILE: front/inventory/insert_item.php (with unit barcodes)

require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$logFile = __DIR__ . '/log.txt';
$log = fopen($logFile, 'a');

// استقبال البيانات من النموذج
$name          = $_POST['name'] ?? '';
$model         = $_POST['model'] ?? '';
$category_id   = $_POST['category_id'] ?? null;
$unit_id       = $_POST['unit_id'] ?? null;
$location_id   = $_POST['location_id'] ?? null;
$quantity      = (int) ($_POST['quantity'] ?? 0);
$price         = $_POST['price'] ?? 0.00;
$notes         = $_POST['notes'] ?? '';
$barcode       = $_POST['barcode'] ?? '';
$serial_number = $_POST['serial_number'] ?? '';

fwrite($log, "RECEIVED: " . json_encode($_POST) . "\n");

// تحقق أساسي
if (empty($name) || empty($model) || $quantity <= 0) {
  fwrite($log, "❌ ERROR: Missing name/model/quantity\n");
  echo "❌ اسم الصنف والموديل والكمية مطلوبة.";
  fclose($log);
  exit;
}

// توليد باركود وسيريال عام إن لم يُدخل
if (empty($barcode)) {
  $barcode = rand(100000000000, 999999999999);
}
if (empty($serial_number)) {
  $serial_number = bin2hex(random_bytes(6));
}

try {
  // حفظ الصنف الأساسي
  $sql = "INSERT INTO items (name, model, category_id, unit_id, location_id, quantity, price, notes, barcode, serial_number)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $success = $stmt->execute([
    $name,
    $model,
    $category_id,
    $unit_id,
    $location_id,
    $quantity,
    $price,
    $notes,
    $barcode,
    $serial_number
  ]);

  if ($success) {
    $item_id = $pdo->lastInsertId();

    // إنشاء باركود لكل وحدة
    for ($i = 0; $i < $quantity; $i++) {
      $unit_barcode = rand(100000000000, 999999999999) . $i;
      $stmtUnit = $pdo->prepare("INSERT INTO item_units (item_id, barcode) VALUES (?, ?)");
      $stmtUnit->execute([$item_id, $unit_barcode]);
    }

    fwrite($log, "✅ SUCCESS: Item and units inserted\n");
    echo "success";
  } else {
    $error = $stmt->errorInfo();
    fwrite($log, "❌ SQL FAILURE: " . json_encode($error) . "\n");
    echo "❌ خطأ أثناء الحفظ.";
  }

} catch (Throwable $e) {
  fwrite($log, "❌ EXCEPTION: " . $e->getMessage() . "\n");
  echo "❌ حدث استثناء.";
}

fclose($log);
?>