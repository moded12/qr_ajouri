<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

try {
    $units = $pdo->query("SELECT id, name FROM units")->fetchAll();
    $locations = $pdo->query("SELECT id, name FROM locations")->fetchAll();
    $categories = $pdo->query("SELECT id, name FROM categories")->fetchAll();
    echo json_encode([
        'units' => $units,
        'locations' => $locations,
        'categories' => $categories
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
