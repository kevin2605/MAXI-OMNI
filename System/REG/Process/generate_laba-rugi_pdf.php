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
$pdf->AddPage();
$pdf->SetXY(10,5);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 4, 'PT INDOPACK MULTI PERKASA', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 3, 'LAPORAN LABA / (RUGI)', 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, ' ' . $startdate . ' To ' . $enddate, 0, 1, 'C');
$pdf->Line(8, 19, 202, 19);
$columnWidth = 95;
$columnWidthright = 195;
// kiri
$pdf->SetXY(10, 21);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell($columnWidth, 4, 'PENDAPATAN', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell($columnWidth, 4, '   Penjualan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Retur & Potongan Pendapatan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Pendapatan Bersih', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell($columnWidth, 4, 'HARGA POKOK', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell($columnWidth, 4, '   Bi Bahan Baku', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '       Persediaan Awal Bahan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '       Pembelian Bahan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '       Persediaan Akhir Bahan', 0, 1, 'L');
$pdf->Ln(4);
$pdf->Cell($columnWidth, 4, '   Bi Upah Langsung', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi Pabrikasi & Penyusutan Mesin', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Jumlah Harga Pokok Produksi', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '(+) Persd Awal Brg Dlm Proses', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '(-) Persd Akhir Brg Dlm Proses', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '(+) Persd Awal Brg Jadi', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '(-) Persd Akhir Brg Jadi', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, 'HARGA POKOK PENJUALAN', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, 'LABA/(RUGI) KOTOR', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, 'BIAYA OPERASI:', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Penjualan:', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Bongkar/Muat', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Kendaraan Niaga', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Penyusutan Kendaaran Niaga', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Exim', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Operasional Penjualan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Promosi', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Jumlah Biaya Penjualan................................................', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Umum & Administrasi:', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Upah Karyawan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Tunjangan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.ATK/Percetakan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Listrik/Telepon', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.luran/Retribusi', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Makan/Minum', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Entertain/Sumbangan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Keperluan Kantor', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Pnyst Mebel & Perlkp Kantor', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Jumlah Biaya Umum & Administrasi.............................', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Jumlah Biaya Operasi', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, 'BIAYA/PENDAPATAN NON OPERASI', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Administrasi/Provisi Bank', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Bunga Pinjaman Bank', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.PPh Bunga Jasa Giro', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.PPh Pihak Lain Yg Ditanggung', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Sewa', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Bi.Asuransi', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Pembulatan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Pendapatan Jasa Giro', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Pendapatan Rupa-2', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '   Jumlah Biaya/Pendpt Non Operasi', 0, 1, 'L');
$pdf->Ln(1);
$pdf->Cell($columnWidth, 4, 'LABA BERSIH SBLM PPh (komersial)', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, 'Koreksi Fiskal Positip:', 0, 1, 'L');
$pdf->Ln(1);
$pdf->Line(10, 234, 50, 234);
$pdf->Cell($columnWidth, 4, '1) luran BPJS yg seluruhnya ditanggung perusahaan', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '2) Bi.makan-minum bersifat natura (non deductible)', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '3) Tidak ada daftar nominatif', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '4) Bi.PPh dari bunga jasa giro (non deductible)', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, '5) PPh Pihak Lain yg Ditanggung (Idk diakui sbg penghasilan ybs)', 0, 1, 'L');
$pdf->Cell($columnWidth, 4, 'Koreksi Fiskal Negatip:', 0, 1, 'L');
$pdf->Ln(1);
$pdf->Line(10, 259, 50, 259);
$pdf->Cell($columnWidth, 4, '6) Pendapatan bunga rekening koran bank (telah dipotong PPh bersifat final)', 0, 1, 'L');
$pdf->Ln(2);
$pdf->Cell($columnWidth, 4, 'LABA BERSIH SBLM PPh (fiskal)', 0, 1, 'L');

// kanan
$columnAmount = 35;

//pendapatan
$pdf->SetXY(157, 25);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(157);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->Line(165, 33, 198, 33);
$pdf->SetX(157);
$pdf->Cell(0, 5, 'Rp', 0, 1, 'L');

//nilai pendapatan
$pdf->SetXY(163, 25);
$pdf->SetFont('Arial', '', 10);
//Penjualan
$penjualan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='4-1000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $penjualan += $row["Debit"];
    }else{
        $penjualan -= $row["Credit"];
    }
}
$pdf->Cell($columnAmount, 4, number_format($penjualan, 0, ',', '.'), 0, 1, 'R');
//Retur
$retur = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='4-2000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $retur += $row["Debit"];
    }else{
        $retur -= $row["Credit"];
    }
}
$pdf->SetX(163);
$pdf->Cell($columnAmount, 4, number_format($retur, 0, ',', '.'), 0, 1, 'R');
$pendapatanbersih = $penjualan + $retur;
$pdf->SetX(163);
$pdf->Cell($columnAmount, 5, number_format($pendapatanbersih, 0, ',', '.'), 0, 1, 'R');

//harga pokok
$pdf->SetXY(75, 45);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai harga pokok
$pdf->SetXY(81, 45);
$pdf->SetFont('Arial', '', 10);
//persediaan awal bahan
$persawalbahan = 0;
$query = "SELECT Pers_Akhir_Bahan FROM datapersediaan WHERE Tanggal= '$startdate'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$persawalbahan = $row["Pers_Akhir_Bahan"];
$pdf->Cell($columnAmount, 4, number_format($persawalbahan, 0, ',', '.'), 0, 1, 'R');
//pembelian bahan
$pembelianbahan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='5-1100' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $pembelianbahan += $row["Debit"];
    }else{
        $pembelianbahan -= $row["Credit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($pembelianbahan, 0, ',', '.'), 0, 1, 'R');
//persediaan akhir bahan
$query = "SELECT Pers_Akhir_Bahan FROM datapersediaan WHERE Tanggal= '$enddate'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$persakhirbahan = $row["Pers_Akhir_Bahan"];
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, '('.number_format($persakhirbahan, 0, ',', '.').')', 0, 1, 'R');
$pdf->Line(83, 57, 116, 57);

//harga pokok
$pdf->SetXY(116, 57);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai harga pokok
$pdf->SetXY(122, 57);
$pdf->SetFont('Arial', '', 10);
$biayabahanbaku = $persawalbahan + $pembelianbahan - $persakhirbahan;
$pdf->Cell($columnAmount, 4, number_format($biayabahanbaku, 0, ',', '.'), 0, 1, 'R');
//biaya upah langsung
$upahlangsung = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='5-2000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $upahlangsung += $row["Credit"];
    }else{
        $upahlangsung -= $row["Debit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($upahlangsung, 0, ',', '.'), 0, 1, 'R');
//pabrikasipenyusutanmesin
$pabrikasipenyusutanmesin = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='5-3700' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $pabrikasipenyusutanmesin += $row["Credit"];
    }else{
        $pabrikasipenyusutanmesin -= $row["Debit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($pabrikasipenyusutanmesin, 0, ',', '.'), 0, 1, 'R');
$pdf->Line(125, 69, 157, 69);
$jumlahHPP = $biayabahanbaku + $upahlangsung + $pabrikasipenyusutanmesin;
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($jumlahHPP, 0, ',', '.'), 0, 1, 'R');
//pers awal brg dalam proses
$persawalbrgdlmproses = 0;
$query = "SELECT Pers_Akhir_Brg_Dlm_Proses FROM datapersediaan WHERE Tanggal= '$startdate'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$persawalbrgdlmproses = $row["Pers_Akhir_Brg_Dlm_Proses"];
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($persawalbrgdlmproses, 0, ',', '.'), 0, 1, 'R');
//pers akhir brg dalam proses
$persakhirbrgdlmproses = 0;
$query = "SELECT Pers_Akhir_Brg_Dlm_Proses FROM datapersediaan WHERE Tanggal= '$enddate'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$persakhirbrgdlmproses = $row["Pers_Akhir_Brg_Dlm_Proses"];
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, '('.number_format($persakhirbrgdlmproses, 0, ',', '.').')', 0, 1, 'R');
//pers awal brg jadi
$persawalbrgjadi = 0;
$query = "SELECT Pers_Akhir_Brg_Jadi FROM datapersediaan WHERE Tanggal= '$startdate'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$persawalbrgjadi = $row["Pers_Akhir_Brg_Jadi"];
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($persawalbrgjadi, 0, ',', '.'), 0, 1, 'R');
//pers akhir brg jadi
$persakhirbrgjadi = 0;
$query = "SELECT Pers_Akhir_Brg_Jadi FROM datapersediaan WHERE Tanggal= '$enddate'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$persakhirbrgjadi = $row["Pers_Akhir_Brg_Jadi"];
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, '('.number_format($persakhirbrgjadi, 0, ',', '.').')', 0, 1, 'R');
$pdf->Line(125, 89, 157, 89);

//harga pokok penjualan
$pdf->SetXY(157, 89);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(157);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai harga pokok penjualan
$pdf->SetXY(163, 89);
$pdf->SetFont('Arial', '', 10);
$hargaHPP = $jumlahHPP + $persawalbrgdlmproses - $persakhirbrgdlmproses + $persawalbrgjadi - $persakhirbrgjadi;
$pdf->Cell($columnAmount, 4, number_format($hargaHPP, 0, ',', '.'), 0, 1, 'R');
$pdf->Line(165, 93, 198, 93);
$labarugikotor = $pendapatanbersih - $hargaHPP;
$pdf->SetX(163);
$pdf->Cell($columnAmount, 4, number_format($labarugikotor, 0, ',', '.'), 0, 1, 'R');

//// BIAYA OPERASI

//Bi. Penjualan
$pdf->SetXY(75, 105);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai Bi. Penjualan
$pdf->SetXY(81, 105);
$pdf->SetFont('Arial', '', 10);
//bongkar muat
$bongkarmuat = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-1100' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $bongkarmuat += $row["Credit"];
    }else{
        $bongkarmuat -= $row["Debit"];
    }
}
$pdf->Cell($columnAmount, 4, number_format($bongkarmuat, 0, ',', '.'), 0, 1, 'R');
//kendaraanniaga
$kendaraanniaga = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-1200' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $kendaraanniaga += $row["Credit"];
    }else{
        $kendaraanniaga -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($kendaraanniaga, 0, ',', '.'), 0, 1, 'R');
//penyusutankendaraanniaga
$penyusutankendaraanniaga = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-1210' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $penyusutankendaraanniaga += $row["Credit"];
    }else{
        $penyusutankendaraanniaga -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($penyusutankendaraanniaga, 0, ',', '.'), 0, 1, 'R');
//exim
$exim = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-1600' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $exim += $row["Credit"];
    }else{
        $exim -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($exim, 0, ',', '.'), 0, 1, 'R');
//operasional penjualan
$operasionalpenjualan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-1400' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $operasionalpenjualan += $row["Credit"];
    }else{
        $operasionalpenjualan -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($operasionalpenjualan, 0, ',', '.'), 0, 1, 'R');
//promosi
$promosi = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-1500' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $promosi += $row["Credit"];
    }else{
        $promosi -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($promosi, 0, ',', '.'), 0, 1, 'R');
$pdf->Line(83, 129, 116, 129);

//jumlah biaya penjualan
$pdf->SetXY(116, 129);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai jumlah biaya penjualan
$pdf->SetXY(122, 129);
$pdf->SetFont('Arial', '', 10);
$jumlahbiayapenjualan = $bongkarmuat + $kendaraanniaga + $penyusutankendaraanniaga + $exim + $operasionalpenjualan + $promosi;
$pdf->Cell($columnAmount, 4, number_format($jumlahbiayapenjualan, 0, ',', '.'), 0, 1, 'R');

//Bi. Umum & Administrasi
$pdf->SetXY(75, 137);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(75);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai Bi. Umum & Administrasi
$pdf->SetXY(81, 137);
$pdf->SetFont('Arial', '', 10);
//biaya upah karyawan
$upahdanthr = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-2110' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $upahdanthr += $row["Credit"];
    }else{
        $upahdanthr -= $row["Debit"];
    }
}
$pdf->Cell($columnAmount, 4, number_format($upahdanthr, 0, ',', '.'), 0, 1, 'R');
//biaya tunjangan
$tunjangan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-2120' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $tunjangan += $row["Credit"];
    }else{
        $tunjangan -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($tunjangan, 0, ',', '.'), 0, 1, 'R');
//biaya atk & percetakan
$atkdanpercetakan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-2200' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $atkdanpercetakan += $row["Credit"];
    }else{
        $atkdanpercetakan -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($atkdanpercetakan, 0, ',', '.'), 0, 1, 'R');
//biaya listrik dan telepon
$listrikdantelepon = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-2400' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $listrikdantelepon += $row["Credit"];
    }else{
        $listrikdantelepon -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($listrikdantelepon, 0, ',', '.'), 0, 1, 'R');
//biaya iuran atau retribusi
$iurandanretribusi = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-2600' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $iurandanretribusi += $row["Credit"];
    }else{
        $iurandanretribusi -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($iurandanretribusi, 0, ',', '.'), 0, 1, 'R');
//biaya makan dan minum
$makandanminum = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-2720' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $makandanminum += $row["Credit"];
    }else{
        $makandanminum -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($makandanminum, 0, ',', '.'), 0, 1, 'R');
//biaya entertain atau sumbangan
$entertainatausumbangan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-2730' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $entertainatausumbangan += $row["Credit"];
    }else{
        $entertainatausumbangan -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($entertainatausumbangan, 0, ',', '.'), 0, 1, 'R');
//biaya keperluan kantor
$keperluankantor = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-2710' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $keperluankantor += $row["Credit"];
    }else{
        $keperluankantor -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($keperluankantor, 0, ',', '.'), 0, 1, 'R');
//biaya penyusutan mebel dan perlengkapan kantor
$penyusutanmebel = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='6-2910' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $penyusutanmebel += $row["Credit"];
    }else{
        $penyusutanmebel -= $row["Debit"];
    }
}
$pdf->SetX(81);
$pdf->Cell($columnAmount, 4, number_format($penyusutanmebel, 0, ',', '.'), 0, 1, 'R');
$pdf->Line(83, 173, 116, 173);

//jumlah biaya umum & administrasi
$pdf->SetXY(116, 173);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai jumlah biaya umum & administrasi
$pdf->SetXY(122, 173);
$pdf->SetFont('Arial', '', 10);
$jumlahbiayaumumdanadmin = $upahdanthr + $tunjangan + $atkdanpercetakan + $listrikdantelepon + $iurandanretribusi + $makandanminum + $entertainatausumbangan + $keperluankantor + $penyusutanmebel;
$pdf->Cell($columnAmount, 4, number_format($jumlahbiayaumumdanadmin, 0, ',', '.'), 0, 1, 'R');
$pdf->Line(125, 177, 157, 177);

//jumlah biaya operasi
$pdf->SetXY(157, 177);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai jumlah biaya operasi
$pdf->SetXY(163, 177);
$pdf->SetFont('Arial', '', 10);
$jumlahbiayaoperasi = $jumlahbiayapenjualan + $jumlahbiayaumumdanadmin;
$pdf->Cell($columnAmount, 4, number_format($jumlahbiayaoperasi, 0, ',', '.'), 0, 1, 'R');

//// BIAYA / PENDAPATAN NON OPERASI

//BIAYA / PENDAPATAN NON OPERASI
$pdf->SetXY(116, 185);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->SetX(116);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai BIAYA / PENDAPATAN NON OPERASI
$pdf->SetXY(122, 185);
$pdf->SetFont('Arial', '', 10);
//biaya admin atau provisi bank
$adminatauprovisi = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='9-1000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $adminatauprovisi += $row["Credit"];
    }else{
        $adminatauprovisi -= $row["Debit"];
    }
}
$pdf->Cell($columnAmount, 4, number_format($adminatauprovisi, 0, ',', '.'), 0, 1, 'R');
//biaya bunga pinjaman bank
$bungapinjamanbank = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='9-4100' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $bungapinjamanbank += $row["Credit"];
    }else{
        $bungapinjamanbank -= $row["Debit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($bungapinjamanbank, 0, ',', '.'), 0, 1, 'R');
//biaya pph bunga jasa giro
$pphbungajasagiro = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='9-6100' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $pphbungajasagiro += $row["Credit"];
    }else{
        $pphbungajasagiro -= $row["Debit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($pphbungajasagiro, 0, ',', '.'), 0, 1, 'R');
//biaya pph pihak lain ditanggung
$pphpihaklain = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='9-5000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $pphpihaklain += $row["Credit"];
    }else{
        $pphpihaklain -= $row["Debit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($pphpihaklain, 0, ',', '.'), 0, 1, 'R');
//biaya sewa
$sewa = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='9-2000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $sewa += $row["Credit"];
    }else{
        $sewa -= $row["Debit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($sewa, 0, ',', '.'), 0, 1, 'R');
//biaya asuransi
$asuransi = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='9-3000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Credit"] > 0){
        $asuransi += $row["Credit"];
    }else{
        $asuransi -= $row["Debit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, number_format($asuransi, 0, ',', '.'), 0, 1, 'R');
//pembulatan
$pembulatan = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='9-9000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $pembulatan += $row["Debit"];
    }else{
        $pembulatan -= $row["Credit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, '('.number_format($pembulatan, 0, ',', '.').')', 0, 1, 'R');
//pendapatan jasa giro
$pendapatanjasagiro = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='8-1500' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $pendapatanjasagiro += $row["Debit"];
    }else{
        $pendapatanjasagiro -= $row["Credit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, '('.number_format($pendapatanjasagiro, 0, ',', '.').')', 0, 1, 'R');
//pendapatan rupa rupa
$pendapatanruparupa = 0;
$query = "SELECT * FROM journaldata WHERE AccountCD='8-2000' AND JournalDate >= '$startdate' AND JournalDate <= '$enddate'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    if($row["Debit"] > 0){
        $pendapatanruparupa += $row["Debit"];
    }else{
        $pendapatanruparupa -= $row["Credit"];
    }
}
$pdf->SetX(122);
$pdf->Cell($columnAmount, 4, '('.number_format($pendapatanruparupa, 0, ',', '.').')', 0, 1, 'R');
$pdf->Line(125, 221, 157, 221);

//Jumlah Biaya/Pendpt Non Operasi
$pdf->SetXY(157, 221);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');
$pdf->Ln(1);
$pdf->SetX(157);
$pdf->Cell(0, 4, 'Rp', 0, 1, 'L');

//nilai Jumlah Biaya/Pendpt Non Operasi
$pdf->SetXY(163, 221);
$pdf->SetFont('Arial', '', 10);
$jumlahpendapatannonoperasi = $adminatauprovisi + $bungapinjamanbank + $pphbungajasagiro + $pphpihaklain + $sewa + $asuransi - $pembulatan - $pendapatanjasagiro - $pendapatanruparupa;
$pdf->Cell($columnAmount, 4, '('.number_format($jumlahpendapatannonoperasi, 0, ',', '.').')', 0, 1, 'R');
$pdf->Ln(1);
$pdf->Line(165, 225, 198, 225);
$pdf->SetX(163);
$lababersihsblmpphkomersial = $labarugikotor - $jumlahbiayaoperasi + $jumlahpendapatannonoperasi;
$pdf->Cell($columnAmount, 4, number_format($lababersihsblmpphkomersial, 0, ',', '.'), 0, 1, 'R');
$pdf->Line(165, 230, 198, 230);
$pdf->Line(165, 231, 198, 231);

////KOREKSI FISKAL POSITIP

//KOREKSI FISKAL POSITIP
$pdf->SetXY(163, 235);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell($columnAmount, 4, number_format($tunjangan, 0, ',', '.'), 0, 1, 'R');
$pdf->SetX(163);
$pdf->Cell($columnAmount, 4, number_format($makandanminum, 0, ',', '.'), 0, 1, 'R');
$pdf->SetX(163);
$pdf->Cell($columnAmount, 4, number_format($entertainatausumbangan, 0, ',', '.'), 0, 1, 'R');
$pdf->SetX(163);
$pdf->Cell($columnAmount, 4, number_format($pphbungajasagiro, 0, ',', '.'), 0, 1, 'R');
$pdf->SetX(163);
$pdf->Cell($columnAmount, 4, number_format($pphpihaklain, 0, ',', '.'), 0, 1, 'R');


////KOREKSI FISKAL NEGATIP

//KOREKSI FISKAL NEGATIP
$pdf->SetXY(163, 259);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell($columnAmount, 4, number_format($pendapatanjasagiro, 0, ',', '.'), 0, 1, 'R');
$pdf->Line(165, 263, 198, 263);

//LABA BERSIH SEBELUM PPH
$pdf->SetXY(163, 265);
$pdf->SetFont('Arial', '', 10);
$lababersihsblmpphfiskal = $lababersihsblmpphkomersial + $tunjangan + $makandanminum + $entertainatausumbangan + $pphbungajasagiro + $pphpihaklain - $pendapatanjasagiro;
$pdf->Cell($columnAmount, 4, number_format($lababersihsblmpphfiskal, 0, ',', '.'), 0, 1, 'R');
$pdf->Line(165, 269, 198, 269);
$pdf->Line(165, 270, 198, 270);

//// TTD DIREKTUR

$pdf->SetXY(165, 273);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell($columnAmount, 0, "PT. Indopack Multi Perkasa", 0, 1, 'R');





$pdf->Output();
?>