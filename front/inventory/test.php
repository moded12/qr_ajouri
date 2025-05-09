<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "✅ PHP works. Now trying DB...<br>";

require_once '../../api/db.php';
echo "✅ Connected to DB<br>";

$stmt = $pdo->prepare("SELECT COUNT(*) FROM items");
$stmt->execute();
$count = $stmt->fetchColumn();

echo "✅ Total items in DB: " . $count;
?>

