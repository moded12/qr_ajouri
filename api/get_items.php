<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT items.id, items.name, items.quantity, items.barcode,
                         units.name AS unit, locations.name AS location
                         FROM items
                         LEFT JOIN units ON items.unit_id = units.id
                         LEFT JOIN locations ON items.location_id = locations.id
                         ORDER BY items.id DESC");
    $items = $stmt->fetchAll();
    echo json_encode($items, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
