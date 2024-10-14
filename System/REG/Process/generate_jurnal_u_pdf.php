<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('../fpdf186/rotation.php');
include "../../DBConnection.php";

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

date_default_timezone_set("Asia/Jakarta");

$startdate = isset($_GET['startdate']) ? $_GET['startdate'] : '';
$enddate = isset($_GET['enddate']) ? $_GET['enddate'] : '';


$queryDetail = "
    SELECT 
        h.GenJourID, h.JournalDate, h.MemoID, h.MemoDesc, h.Description as HeaderDesc,
        d.AccountCD, d.AccountName, d.Debit, d.Credit
    FROM genjournalheader h
    LEFT JOIN genjournaldetail d ON h.GenJourID = d.GenJourID
";

if (!empty($startdate) && !empty($enddate)) {
    $queryDetail .= " WHERE h.JournalDate BETWEEN '$startdate' AND '$enddate'";
}
$queryDetail .= " ORDER BY h.JournalDate DESC";

$resultDetail = mysqli_query($conn, $queryDetail);

if (!$resultDetail) {
    die("Error: " . mysqli_error($conn));
}

$pdf = new PDF_Rotate();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'PT INDOPACK MULTI PERKASA', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 3, 'Sidoarjo', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'General Journal', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, ' ' . $startdate . ' To ' . $enddate, 0, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'ID#', 0);
$pdf->Cell(30, 10, 'Journal Date', 0);
$pdf->Cell(30, 10, 'Account CD', 0);
$pdf->Cell(40, 10, 'Account Name', 0);
$pdf->Cell(30, 10, 'Debit', 0);
$pdf->Cell(30, 10, 'Credit', 0);
$pdf->Ln();

$pdf->Cell(0, 0, '', 'T');
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 10);

$previousGenJourID = '';
while ($row = mysqli_fetch_array($resultDetail)) {
    if ($row['GenJourID'] != $previousGenJourID) {
        if ($previousGenJourID != '') {
            $pdf->Cell(0, 0, '', 'T');
            $pdf->Ln();
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 8, $row['GenJourID'], 0);
        $pdf->Cell(30, 8, $row['JournalDate'], 0);
        $pdf->Cell(100, 8, $row['MemoID'], 0);
        $pdf->Ln();
    }

    $pdf->SetFont('Arial', '', 10);

    $yStart = $pdf->GetY();
    $pdf->Cell(50);
    $pdf->Cell(30, 8, $row['AccountCD'], 0);
    $x = $pdf->GetX();
    $pdf->MultiCell(40, 8, $row['AccountName'], 0);
    $yEnd = $pdf->GetY();
    $pdf->SetXY($x + 40, $yStart);

    $pdf->Cell(30, 8, number_format($row['Debit'], ), 0);
    $pdf->Cell(30, 8, number_format($row['Credit'], ), 0);

    $pdf->Ln();

    $lineHeight = $yEnd - $yStart;
    if ($lineHeight > 8) {
        $pdf->Ln($lineHeight - 8);
    }

    $previousGenJourID = $row['GenJourID'];
}

$pdf->Output('I', 'Laporan_Jurnal_Umum.pdf');

mysqli_close($conn);

?>