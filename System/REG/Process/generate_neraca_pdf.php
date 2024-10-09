<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('../fpdf186/rotation.php');
include "../DBConnection.php";

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

date_default_timezone_set("Asia/Jakarta");

$startdate = isset($_GET['startdate']) ? $_GET['startdate'] : '';
$enddate = isset($_GET['enddate']) ? $_GET['enddate'] : '';

$queryDetail = "
    SELECT 
        JournalDate, AccountCD, AccountName, Debit, Credit
    FROM Journaldata
";

if (!empty($startdate) && !empty($enddate)) {
    $queryDetail .= " WHERE JournalDate BETWEEN '$startdate' AND '$enddate'";
}
$queryDetail .= " ORDER BY JournalDate DESC";

$resultDetail = mysqli_query($conn, $queryDetail);

if (!$resultDetail) {
    die("Error: " . mysqli_error($conn));
}

$pdf = new PDF_Rotate();
$pdf->AddPage('L');
//box
$pdf->Line(5, 5, 290, 5);
$pdf->Line(5, 25, 290, 25);
$pdf->Line(5, 5, 5, 165);
$pdf->Line(290, 5, 290, 165);
$pdf->Line(5, 165, 290, 165);
$pdf->Line(145, 25, 145, 165);
//lines kiri
$pdf->Line(90, 82, 126, 82);
$pdf->Line(90, 127, 126, 127);
$pdf->Line(90, 153, 126, 153);
$pdf->Line(90, 158, 126, 158);
$pdf->Line(90, 159, 126, 159);
//lines kanan
$pdf->Line(240, 82, 275, 82);
$pdf->Line(240, 140, 275, 140);
$pdf->Line(240, 147, 275, 147);
$pdf->Line(240, 159, 275, 159);
$pdf->Line(240, 160, 275, 160);

$pdf->SetXY(10,7);
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 5, 'PT INDOPACK MULTI PERKASA', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, 'NERACA', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, ' ' . $startdate . ' To ' . $enddate, 0, 1, 'C');
$pdf->Ln(2);

$columnWidth = 35;
$columnWidthright = 195;

//aktiva lancar
$pdf->SetXY(10, 30);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($columnWidth, 8, 'AKTIVA', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 8, 'AKTIVA LANCAR', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, 'Kas & Setara Kas', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, 'Bank', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, 'Piutang Usaha', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, 'Piutang Karyawan', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, 'Persediaan', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, 'PPN Masukan', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 8, 'TOTAL AKTIVA LANCAR', 0, 1, 'L');
$pdf->Ln(5);

//Rp aktiva lancar
$pdf->SetXY(80, 46);
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 8, 'Rp', 0, 1, 'L');
$pdf->Ln(5);

//nilai aktiva lancar
$pdf->SetXY(90, 46);
$pdf->SetFont('Arial', '', 10);

//kas
$kas = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-1100' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $kas += $row["Debit"];
    }else{
        $kas -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, number_format($kas, 0, ',', '.'), 0, 1, 'R');

//bank
$bank = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-1210' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $bank += $row["Debit"];
    }else{
        $bank -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, number_format($bank, 0, ',', '.'), 0, 1, 'R');

//piutang usaha
$piutangusaha = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-1300' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $piutangusaha += $row["Debit"];
    }else{
        $piutangusaha -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, number_format($piutangusaha, 0, ',', '.'), 0, 1, 'R');

//piutang karyawan
$piutangkaryawan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-1410' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $piutangkaryawan += $row["Debit"];
    }else{
        $piutangkaryawan -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, number_format($piutangkaryawan, 0, ',', '.'), 0, 1, 'R');

//persediaan
$persediaan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-1800' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $persediaan += $row["Debit"];
    }else{
        $persediaan -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, number_format($persediaan, 0, ',', '.'), 0, 1, 'R');

//ppn masukan
$ppnmasukan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-1560' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $ppnmasukan += $row["Debit"];
    }else{
        $ppnmasukan -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, number_format($ppnmasukan, 0, ',', '.'), 0, 1, 'R');
$totalaktivalancar = $kas+$bank+$piutangusaha+$piutangkaryawan+$persediaan+$ppnmasukan;
$pdf->SetX(90);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 8, number_format($totalaktivalancar, 0, ',', '.'), 0, 1, 'R');
$pdf->Ln(5);

//aktiva tetap
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 8, 'AKTIVA TETAP', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, 'Mesin & Perlengkapan', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, 'Mebel & Perlkp Kantor', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, 'Kendaraan', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell($columnWidth, 6, '(-) Akumulasi Penyusutan', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 8, 'TOTAL AKTIVA TETAP', 0, 1, 'L');
$pdf->Ln(18);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($columnWidth, 8, 'TOTAL AKTIVA', 0, 1, 'L');

//Rp aktiva tetap
$pdf->SetXY(80, 103);
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(80);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 7, 'Rp', 0, 1, 'L');
$pdf->Ln(19);
$pdf->SetX(80);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');

//nilai aktiva tetap
$pdf->SetXY(90, 103);
$pdf->SetFont('Arial', '', 10);

//mesin & pelengkapan
$mesindanperlengkapan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-2400' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $mesindanperlengkapan += $row["Debit"];
    }else{
        $mesindanperlengkapan -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, number_format($mesindanperlengkapan, 0, ',', '.'), 0, 1, 'R');

//mebel & perlkp kantor
$mebeldanperlengkapan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-2500' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $mebeldanperlengkapan += $row["Debit"];
    }else{
        $mebeldanperlengkapan -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, number_format($mebeldanperlengkapan, 0, ',', '.'), 0, 1, 'R');

//kendaraan
$kendaraan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-2300' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $kendaraan += $row["Debit"];
    }else{
        $kendaraan -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, number_format($kendaraan, 0, ',', '.'), 0, 1, 'R');

//akumulasi penyusutan
$akumulasipenyusutan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='1-2900' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $akumulasipenyusutan += $row["Debit"];
    }else{
        $akumulasipenyusutan -= $row["Credit"];
    }
}
$pdf->SetX(90);
$pdf->Cell($columnWidth, 6, '('.number_format($akumulasipenyusutan, 0, ',', '.').')', 0, 1, 'R');
$totalaktivatetap = $mesindanperlengkapan+$mebeldanperlengkapan+$kendaraan+$akumulasipenyusutan;
$pdf->SetX(90);
$pdf->Cell($columnWidth, 7, number_format($totalaktivatetap, 0, ',', '.'), 0, 1, 'R');
$pdf->Ln(19);
$pdf->SetX(90);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($columnWidth, 6, number_format($totalaktivalancar+$totalaktivatetap, 0, ',', '.'), 0, 1, 'R');

//KEWAJIBAN & MODAL

//KEWAJIBAN LANCAR
$pdf->SetXY(150, 30);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($columnWidth, 8, 'KEWAJIBAN & MODAL', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->SetX(150);
$pdf->Cell($columnWidth, 8, 'KEWAJIBAN LANCAR', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'Utang Usaha', 0, 1, 'L');
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'Utang Lain-Lain', 0, 1, 'L');
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'Utang Pajak (PPh 25/29)', 0, 1, 'L');
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'PPN Kurang Bayar', 0, 1, 'L');
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'Utang Bank (KIK)', 0, 1, 'L');
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'Uang muka Pelanggan', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->SetX(150);
$pdf->Cell($columnWidth, 8, 'TOTAL KEWAJIBAN LANCAR', 0, 1, 'L');
$pdf->Ln(5);

//Rp KEWAJIBAN LANCAR
$pdf->SetXY(230, 46);
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(230);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(230);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(230);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(230);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(230);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(230);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(230);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 8, 'Rp', 0, 1, 'L');
$pdf->Ln(5);

//nilai KEWAJIBAN LANCAR
$pdf->SetXY(240, 46);
$pdf->SetFont('Arial', '', 10);

//utang usaha
$utangusaha = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='2-1100' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $utangusaha += $row["Debit"];
    }else{
        $utangusaha -= $row["Credit"];
    }
}
$pdf->SetX(240);
$pdf->Cell($columnWidth, 6, number_format($utangusaha, 0, ',', '.'), 0, 1, 'R');

//utang lain lain
$utanglainlain = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='2-1200' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $utanglainlain += $row["Debit"];
    }else{
        $utanglainlain -= $row["Credit"];
    }
}
$pdf->SetX(240);
$pdf->Cell($columnWidth, 6, number_format($utanglainlain, 0, ',', '.'), 0, 1, 'R');

//utang pajak pph 25&29
$utangpajakpph = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='2-1540' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $utangpajakpph += $row["Debit"];
    }else{
        $utangpajakpph -= $row["Credit"];
    }
}
$pdf->SetX(240);
$pdf->Cell($columnWidth, 6, number_format($utangpajakpph, 0, ',', '.'), 0, 1, 'R');

//ppn kurang bayar
$ppnkurangbayar = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='2-1540' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $ppnkurangbayar += $row["Debit"];
    }else{
        $ppnkurangbayar -= $row["Credit"];
    }
}
$pdf->SetX(240);
$pdf->Cell($columnWidth, 6, number_format($ppnkurangbayar, 0, ',', '.'), 0, 1, 'R');

//utang bank kik
$utangbankkik = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='2-1540' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $utangbankkik += $row["Debit"];
    }else{
        $utangbankkik -= $row["Credit"];
    }
}
$pdf->SetX(240);
$pdf->Cell($columnWidth, 6, number_format($utangbankkik, 0, ',', '.'), 0, 1, 'R');

//uang muka pelanggan
$uangmukapelanggan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='2-1300' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $uangmukapelanggan += $row["Debit"];
    }else{
        $uangmukapelanggan -= $row["Credit"];
    }
}
$pdf->SetX(240);
$pdf->Cell($columnWidth, 6, number_format($uangmukapelanggan, 0, ',', '.'), 0, 1, 'R');
$totalkewajibanlancar = $utangusaha+$utanglainlain+$utangpajakpph+$ppnkurangbayar+$utangbankkik+$uangmukapelanggan;
$pdf->SetX(240);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell($columnWidth, 8, number_format($totalkewajibanlancar, 0, ',', '.'), 0, 1, 'R');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->SetX(150);
$pdf->Cell($columnWidth, 8, 'KEWAJIBAN JK PANJANG', 0, 1, 'L');
$pdf->Ln(5);

//modal dan laba ditahan
$pdf->SetFont('Arial', '', 12);
$pdf->SetX(150);
$pdf->Cell($columnWidth, 8, 'MODAL & LABA DITAHAN', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'Modal Disetor', 0, 1, 'L');
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'Laba/Rugi Ditahan', 0, 1, 'L');
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'Laba/Rugi Tahun Berjalan', 0, 1, 'L');
$pdf->SetX(155);
$pdf->Cell($columnWidth, 6, 'PPh Tahun Berjalan', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->SetX(150);
$pdf->Cell($columnWidth, 8, 'TOTAL MODAL & LABA DITAHAN', 0, 1, 'L');
$pdf->Ln(5);

//Rp modal dan laba ditahan
$pdf->SetXY(230,116);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(230);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(230);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetX(230);
$pdf->Cell($columnWidth, 6, 'Rp', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->SetX(230);
$pdf->Cell($columnWidth, 8, 'Rp', 0, 1, 'L');
$pdf->Ln(5);
$pdf->SetX(230);
$pdf->Cell($columnWidth, 8, 'Rp', 0, 1, 'L');

//nilai modal dan laba ditahan
$pdf->SetXY(240,116);
$pdf->SetFont('Arial', '', 10);

//modal disetor
$modaldisetor = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='3-1000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $modaldisetor += $row["Debit"];
    }else{
        $modaldisetor -= $row["Credit"];
    }
}
$pdf->Cell($columnWidth, 6, number_format($modaldisetor, 0, ',', '.'), 0, 1, 'R');

//laba rugi ditahan
$labarugiditahan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='3-2000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $labarugiditahan += $row["Debit"];
    }else{
        $labarugiditahan -= $row["Credit"];
    }
}
$pdf->SetX(240);
$pdf->Cell($columnWidth, 6, number_format($labarugiditahan, 0, ',', '.'), 0, 1, 'R');

//laba rugi tahun berjalan
$labarugitahunberjalan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='3-3300' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $labarugitahunberjalan += $row["Debit"];
    }else{
        $labarugitahunberjalan -= $row["Credit"];
    }
}
$pdf->SetX(240);
$pdf->Cell($columnWidth, 6, number_format($labarugitahunberjalan, 0, ',', '.'), 0, 1, 'R');

//pph tahun berjalan
$pphtahunberjalan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='3-4000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $pphtahunberjalan += $row["Debit"];
    }else{
        $pphtahunberjalan -= $row["Credit"];
    }
}
$pdf->SetX(240);
$pdf->Cell($columnWidth, 6, '('.number_format($pphtahunberjalan, 0, ',', '.').')', 0, 1, 'R');
$totalmodallabaditahan = $modaldisetor+$labarugiditahan+$labarugitahunberjalan+$pphtahunberjalan;
$pdf->SetFont('Arial', '', 12);
$pdf->SetX(240);
$pdf->Cell($columnWidth, 8, number_format($totalmodallabaditahan, 0, ',', '.'), 0, 1, 'R');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX(150);
$pdf->Cell($columnWidth, 8, 'TOTAL KEWAJIBAN & MODAL', 0, 1, 'L');
$pdf->Ln(5);


$pdf->SetXY(240,153);
$pdf->Cell($columnWidth, 8, number_format($totalkewajibanlancar+$totalmodallabaditahan, 0, ',', '.'), 0, 1, 'R');

$pdf->SetXY(235,165);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($columnWidth, 8, "PT. Indopack Multi Perkasa", 0, 1, 'L');


$pdf->Output();
?>