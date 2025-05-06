<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

try {
    $name = $_POST['name'] ?? '';
    $quantity = $_POST['quantity'] ?? 0;
    $barcode = $_POST['barcode'] ?? '';
    $unit_id = $_POST['unit_id'] ?? null;
    $location_id = $_POST['location_id'] ?? null;
    $category_id = $_POST['category_id'] ?? null;

    if (!$name || !$unit_id || !$location_id || !$category_id) {
        echo json_encode(['success' => false, 'message' => 'الرجاء تعبئة كافة الحقول المطلوبة']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO items (name, quantity, barcode, unit_id, location_id, category_id)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $quantity, $barcode, $unit_id, $location_id, $category_id]);

    echo json_encode(['success' => true, 'message' => 'تمت إضافة الصنف بنجاح']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'خطأ: ' . $e->getMessage()]);
}
?>
