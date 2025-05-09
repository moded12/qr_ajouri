<?php
// FILE: receipt.php
require_once '../../api/db.php';
require_once '../../vendor/autoload.php'; // TCPDF via Composer

use TCPDF;

$item_id = $_GET['item_id'] ?? '';
$quantity = $_GET['qty'] ?? '';

if (!$item_id || !$quantity) {
  exit("Invalid parameters.");
}

$stmt = $pdo->prepare("SELECT items.*, categories.name AS category, locations.name AS location
                       FROM items
                       LEFT JOIN categories ON items.category_id = categories.id
                       LEFT JOIN locations ON items.location_id = locations.id
                       WHERE items.id = ?");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

if (!$item) {
  exit("Item not found.");
}

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Inventory System');
$pdf->SetAuthor('محمد العجوري');
$pdf->SetTitle('إيصال إخراج صنف');
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE, 20);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

$pdf->SetFont('aealarabiya', '', 16);

// عنوان الإيصال
$pdf->Write(0, 'إيصال إخراج صنف من المخزون', '', 0, 'C', true, 0, false, false, 0);
$pdf->Ln(6);

// محتوى الإيصال
$html = '
<table border="0" cellpadding="6">
  <tr><td><strong>الاسم:</strong></td><td>' . htmlspecialchars($item['name']) . '</td></tr>
  <tr><td><strong>الموديل:</strong></td><td>' . htmlspecialchars($item['model']) . '</td></tr>
  <tr><td><strong>السيريال:</strong></td><td>' . htmlspecialchars($item['serial_number']) . '</td></tr>
  <tr><td><strong>الكمية المُخرجة:</strong></td><td>' . $quantity . '</td></tr>
  <tr><td><strong>السعر للوحدة:</strong></td><td>' . $item['price'] . ' د.أ</td></tr>
  <tr><td><strong>الموقع:</strong></td><td>' . $item['location'] . '</td></tr>
  <tr><td><strong>التصنيف:</strong></td><td>' . $item['category'] . '</td></tr>
  <tr><td><strong>تاريخ الإخراج:</strong></td><td>' . date('Y-m-d H:i:s') . '</td></tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(10);
$pdf->Cell(0, 0, 'المستخدم: ____________________', 0, 1, 'L');
$pdf->Cell(0, 0, 'التوقيع: ____________________', 0, 1, 'L');

$pdf->Output('receipt.pdf', 'I');
?>
