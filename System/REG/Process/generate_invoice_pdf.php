<?php
require('../fpdf186/rotation.php');
include "../DBConnection.php";

date_default_timezone_set("Asia/Jakarta");

if (isset($_GET['InvoiceID'])) {
    $invoiceID = $_GET['InvoiceID'];

    $queryHeader = "SELECT * FROM invoiceheader WHERE InvoiceID = '$invoiceID'";
    $resultHeader = mysqli_query($conn, $queryHeader);
    $header = mysqli_fetch_array($resultHeader);

    $custID = $header['CustID'];
    $queryCustomer = "SELECT CustName, ShipmentAddress, CityName FROM customer WHERE CustID = '$custID'";
    $resultCustomer = mysqli_query($conn, $queryCustomer);
    $customer = mysqli_fetch_array($resultCustomer);

    $salesOrderID = $header['SalesOrderID'];
    $querySalesOrder = "SELECT Marketing, Description FROM salesorderheader WHERE SalesOrderID = '$salesOrderID'";
    $resultSalesOrder = mysqli_query($conn, $querySalesOrder);
    $salesOrder = mysqli_fetch_array($resultSalesOrder);

    $marketingUserID = $salesOrder['Marketing'];
    $querySalesman = "SELECT Username FROM systemuser WHERE UserID = '$marketingUserID'";
    $resultSalesman = mysqli_query($conn, $querySalesman);
    $salesman = mysqli_fetch_array($resultSalesman);


    $query = "SELECT JournalCD, JournalName, JournalType FROM journal";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {

        $journalData = $result->fetch_assoc();
        $journalCD = $journalData['JournalCD'];
        $journalName = $journalData['JournalName'];
        $journalType = $journalData['JournalType'];
    } else {
        // Jika tidak ada data
        $journalCD = 'N/A';
        $journalName = 'N/A';
        $journalType = 'N/A';
    }

    $currentDate = date('d-m-Y');

    $queryDetail = "SELECT ivd.ProductCD, p.ProductName, ivd.Quantity, ivd.Price, ivd.Discount, ivd.Subtotal 
    FROM invoicedetail ivd 
    JOIN product p ON ivd.ProductCD = p.ProductCD 
    WHERE ivd.InvoiceID = '$invoiceID'";
    $resultDetail = mysqli_query($conn, $queryDetail);

    $pdf = new PDF_Rotate();
    $pdf->AddPage();

    $printCount = $header['PrintCount'];
    $watermarkText = 'ORIGINAL';

    if ($printCount >= 2) {
        $watermarkText = 'COPY';
    }

    ob_start();

    $pdf->SetFont('Arial', 'B', 70);
    $pdf->SetTextColor(230, 230, 230);
    $pdf->Rotate(45, 105, 200);
    $pdf->SetXY(100, 60);
    $pdf->Cell(105, 170, $watermarkText, 0, 1, 'C');
    $pdf->Rotate(0);

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(0, 15);
    $pdf->Cell(0, 10, 'PT. INDOPACK MULTI PERKASA                    Nota Penjualan            ', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetXY(10, 25);
    $pdf->Cell(100, 7, 'Pergudangan SAFE N LOCK, Blok K 1707 - 1708', 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'Jl Lingkar timur KM 5,5', 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'Telp : +623158259871 , Fax, +623158259872', 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'Wechat / Skype / Line : papercupindonesia', 0, 1);
    $pdf->SetX(10);
    $pdf->Ln(2);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'No. Nota    : ' . $header['InvoiceID'], 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'No SP       : ', 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'Informasi   : ' . $header['CreatedOn'], 0, 1);

    $pdf->SetXY(90, 25);
    $pdf->Cell(0, 0, '', 0, 1);
    $pdf->SetX(130);
    $pdf->Cell(0, 7, 'Kepada Yth : ' . $customer['CustName'], 0, 1);
    $pdf->SetX(130);
    $pdf->Cell(0, 7, 'Alamat        : ' . $customer['ShipmentAddress'], 0, 1);
    $pdf->SetX(130);
    $pdf->Cell(0, 7, 'Kota            : ' . $customer['CityName'], 0, 1);
    $pdf->SetX(130);
    $pdf->Ln(4);
    $pdf->SetX(140);
    $pdf->Ln(6);
    $pdf->SetX(130);
    $pdf->Cell(0, 7, 'Tanggal       : ' . $currentDate, 0, 1);
    $pdf->SetX(130);
    $pdf->Cell(0, 7, 'Salesman    : ' . $salesman['Username'], 0, 1);
    $pdf->SetX(130);
    $pdf->Cell(0, 7, 'Keterangan : ' . $salesOrder['Description'], 0, 1);
    $pdf->Ln(10);

    $lineHeight = 8; // Tinggi per baris
    $totalRows = mysqli_num_rows($resultDetail); // Jumlah total baris data
    $totalHeight = $totalRows * $lineHeight; // Total tinggi semua baris

    // Header
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 10, 'No', 'T,B', 0, 'C');
    $pdf->Cell(28, 10, 'Nama Barang', 'T,B', 0, 'C');
    $pdf->Cell(30, 10, 'Price', 'T,B', 0, 'R');
    $pdf->Cell(30, 10, 'Discount', 'T,B', 0, 'R');
    $pdf->Cell(30, 10, 'QTY', 'T,B', 0, 'R');
    $pdf->Cell(35, 10, 'Satuan', 'T,B', 0, 'C');
    $pdf->Cell(25, 10, 'Subtotal  ', 'T,B', 0, 'R');
    $pdf->Ln();

    // Detail
    $subtotal = 0;
    $totalexec = 0;
    $no = 1;
    $lineHeight = 8; // Tinggi per baris

    while ($rowd = mysqli_fetch_array($resultDetail)) {
        // Penghitungan subtotal dan total
        $subtotal += $rowd["Subtotal"];
        $totalexec += $rowd["Price"] * $rowd["Quantity"];

        // Menampilkan data di PDF
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(10, $lineHeight, $no++, 0, 0, 'C');
        $pdf->Cell(28, $lineHeight, $rowd['ProductName'], 0, 0, 'L');
        $pdf->Cell(30, $lineHeight, 'Rp.' . number_format($rowd['Price'], 0), 0, 0, 'R');
        $pdf->Cell(25, $lineHeight, number_format($rowd['Discount'], 0), 0, 0, 'R');
        $pdf->Cell(35, $lineHeight, $rowd['Quantity'], 0, 0, 'R');
        $pdf->Cell(35, $lineHeight, $unit, 0, 0, 'C');
        $pdf->Cell(25, $lineHeight, 'Rp.' . number_format($rowd['Subtotal'], 0), 0, 1, 'R');
    }
    $totalRows = mysqli_num_rows($resultDetail);
    $totalHeight = $totalRows * $lineHeight;


    $pdf->Cell(0, 0, '', 'B');
    $pdf->Ln();

    $pdf->SetXY(0, 105);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Ln(1);
    function terbilang($angka)
    {
        $angka = (int) $angka;
        $baca = array(
            0 => 'Nol',
            1 => 'Satu',
            2 => 'Dua',
            3 => 'Tiga',
            4 => 'Empat',
            5 => 'Lima',
            6 => 'Enam',
            7 => 'Tujuh',
            8 => 'Delapan',
            9 => 'Sembilan',
            10 => 'Sepuluh',
            11 => 'Sebelas',
            12 => 'Dua belas',
            13 => 'Tiga belas',
            14 => 'Empat belas',
            15 => 'Lima belas',
            16 => 'Enam belas',
            17 => 'Tujuh belas',
            18 => 'Delapan belas',
            19 => 'Sembilan belas',
            20 => 'Dua puluh',
            30 => 'Tiga puluh',
            40 => 'Empat puluh',
            50 => 'Lima puluh',
            60 => 'Enam puluh',
            70 => 'Tujuh puluh',
            80 => 'Delapan puluh',
            90 => 'Sembilan puluh',
            100 => 'Seratus',
            1000 => 'Seribu',
            1000000 => 'Juta'
        );

        if ($angka < 20) {
            return $baca[$angka];
        } else if ($angka < 100) {
            $puluhan = floor($angka / 10) * 10;
            $satuan = $angka % 10;
            return $baca[$puluhan] . ($satuan ? ' ' . $baca[$satuan] : '');
        } else if ($angka < 1000) {
            $ratusan = floor($angka / 100) * 100;
            $sisa = $angka % 100;
            return $baca[$ratusan / 100] . ' Ratus' . ($sisa ? ' ' . terbilang($sisa) : '');
        } else if ($angka < 1000000) {
            $ribuan = floor($angka / 1000);
            $sisa = $angka % 1000;
            return terbilang($ribuan) . ' Ribu' . ($sisa ? ' ' . terbilang($sisa) : '');
        } else if ($angka < 1000000000) {
            $jutaan = floor($angka / 1000000);
            $sisa = $angka % 1000000;
            return terbilang($jutaan) . ' Juta' . ($sisa ? ' ' . terbilang($sisa) : '');
        } else {
            return 'Angka terlalu besar';
        }
    }
    $pdf->SetY($pdf->GetY() + $totalHeight + 10);

    $diskon = $totalexec - $subtotal;
    $beforetax = $subtotal / 1.11;
    $tax = $beforetax * 0.11;

    $queryHeader = "SELECT DPAmount FROM invoiceheader WHERE InvoiceID='$invoiceID'";
    $resultHeader = mysqli_query($conn, $queryHeader);
    $rowHeader = mysqli_fetch_array($resultHeader);
    $dpAmount = $rowHeader['DPAmount'];

    $totalNet = $subtotal - $dpAmount - $diskon;

    $terbilang = terbilang($totalNet) . ' Rupiah';

    $pdf->SetX(10);
    $pdf->Cell(25, 8, 'Jenis Bank  :', 0, 0, 'L');
    $pdf->Cell($valueWidth, 8, $journalCD, 0, 1, 'L');

    $pdf->SetX(10);
    $pdf->Cell(25, 8, 'Nama Bank :', 0, 0, 'L');
    $pdf->Cell($valueWidth, 8, $journalName, 0, 1, 'L');



    $pdf->SetFont('Arial', '', 10);
    $pdf->SetXY(90, 105);
    $pdf->Ln(2);
    $pdf->SetY($pdf->GetY() + $totalHeight + 10);

    // Total Invoice
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(130, 8, 'Total Invoice:', 0, 0, 'R');
    $pdf->Cell(58, 8, 'Rp.' . number_format($subtotal, 0), 0, 1, 'R');

    $pdf->Cell(130, 8, '(                       ) DP:', 0, 0, 'R');
    $pdf->Cell(58, 8, 'Rp.' . number_format($dpAmount, 0), 0, 1, 'R');

    $pdf->Cell(130, 8, 'Diskon:', 0, 0, 'R');
    $pdf->Cell(58, 8, number_format($diskon, 0), 0, 1, 'R');

    $pdf->Cell(130, 8, 'DPP:', 0, 0, 'R');
    $pdf->Cell(58, 8, 'Rp.' . number_format($beforetax, 0), 0, 1, 'R');

    $pdf->Cell(130, 8, 'PPN:', 0, 0, 'R');
    $pdf->Cell(58, 8, 'Rp.' . number_format($tax, 0), 0, 1, 'R');

    $pdf->Cell(188, 0, '', 'T');
    $pdf->Ln(2);

    $pdf->Cell(130, 8, 'Total (NET):', 0, 0, 'R');
    $pdf->Cell(58, 8, 'Rp.' . number_format($totalNet, 0), 0, 1, 'R');
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'I', 10, );
    $terbilangText = 'Terbilang: ' . $terbilang;
    $pdf->Cell($labelWidth + $valueWidth, 8, $terbilangText, 0, 1, 'R');

    $pdf->Ln(5);

    $pdf->Ln(2);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(1);
    $pdf->Cell(25, 8, 'Kasir', 0, 0, 'C');
    $pdf->Cell(50, 8, 'Pelanggan', 0, 0, 'C');
    $pdf->Cell(25, 8, 'Driver', 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->Cell(1);
    $pdf->Cell(25, 8, '(.......................)', 0, 0, 'C');
    $pdf->Cell(50, 8, '(.......................)', 0, 0, 'C');
    $pdf->Cell(25, 8, '(.......................)', 0, 1, 'C');

    ob_end_clean();

    $pdf->Output('I', 'Invoice_' . $header['InvoiceID'] . '.pdf');

    $updatePrintCount = "UPDATE invoiceheader SET PrintCount = PrintCount + 1 WHERE InvoiceID = '$invoiceID'";
    mysqli_query($conn, $updatePrintCount);
}
?>