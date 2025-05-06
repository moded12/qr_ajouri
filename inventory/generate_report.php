<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



require_once $_SERVER['DOCUMENT_ROOT'] . '/qr_ajouri/assets/fonts/fpdf/fpdf.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,iconv('UTF-8','windows-1256','تقرير تنبيهات المخزون'),0,1,'C');
        $this->Ln(5);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$pdf->Cell(0,10,iconv('UTF-8','windows-1256','التاريخ: '.date("Y-m-d H:i")),0,1,'C');
$pdf->Ln(10);

// بيانات وهمية
$lowStock = [
    ['name' => 'ماوس', 'quantity' => 3, 'min' => 5],
    ['name' => 'كيبورد', 'quantity' => 1, 'min' => 10]
];
$overStock = [
    ['name' => 'شاشة', 'quantity' => 230, 'max' => 200],
    ['name' => 'طابعة', 'quantity' => 190, 'max' => 150]
];

$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,iconv('UTF-8','windows-1256','⚠️ أصناف قريبة من النفاد:'),0,1);
$pdf->SetFont('Arial','',12);
foreach ($lowStock as $item) {
    $line = "{$item['name']} - الكمية: {$item['quantity']} / الحد الأدنى: {$item['min']}";
    $pdf->Cell(0,10,iconv('UTF-8','windows-1256',$line),0,1);
}

$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,iconv('UTF-8','windows-1256','📦 أصناف تجاوزت الحد الأعلى:'),0,1);
$pdf->SetFont('Arial','',12);
foreach ($overStock as $item) {
    $line = "{$item['name']} - الكمية: {$item['quantity']} / الحد الأعلى: {$item['max']}";
    $pdf->Cell(0,10,iconv('UTF-8','windows-1256',$line),0,1);
}

$pdf->Output();