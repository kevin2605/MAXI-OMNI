<?php
require('../fpdf186/rotation.php');
include "../DBConnection.php";

date_default_timezone_set("Asia/Jakarta");

if (isset($_GET['PurchaseOrderID'])) {
    $purchaseOrderID = $_GET['PurchaseOrderID'];

    $queryHeader = "SELECT * FROM purchaseorderheader WHERE PurchaseOrderID = '$purchaseOrderID'";
    $resultHeader = mysqli_query($conn, $queryHeader);
    $header = mysqli_fetch_array($resultHeader);

    $supplierNum = $header['SupplierNum'];
    $querySupplier = "SELECT * FROM supplier WHERE SupplierNum = '$supplierNum'";
    $resultSupplier = mysqli_query($conn, $querySupplier);
    $supplier = mysqli_fetch_array($resultSupplier);
    $queryDetail = "SELECT pod.ItemCD, pod.Quantity, pod.UnitCD, pod.Price, pod.Subtotal, sg.GoodsName 
                FROM purchaseorderdetail pod 
                JOIN supportinggoods sg ON pod.ItemCD = sg.GoodsCD 
                WHERE pod.PurchaseOrderID = '$purchaseOrderID'";
    $resultDetail = mysqli_query($conn, $queryDetail);


    $currentDate = date('d-m-Y');

    // Memulai buffer output
    ob_start();

    // Membuat PDF
    $pdf = new PDF_Rotate();
    $pdf->AddPage();

    $printCount = $header['PrintCount'];
    $watermarkText = 'ORIGINAL';

    if ($printCount >= 2) {
        $watermarkText = 'COPY';
    }

    $pdf->SetFont('Arial', 'B', 70);
    $pdf->SetTextColor(230, 230, 230); // Warna merah muda (pink)
    $pdf->Rotate(45, 105, 200); // Rotasi teks
    $pdf->SetXY(0, 1); // Mengatur posisi watermark
    $pdf->Text(105, 170, $watermarkText);
    $pdf->Rotate(0); // Reset rotasi teks

    // Header PO
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(15, 15);
    $pdf->Cell(0, 10, 'PT. INDOPACK MULTI PERKASA                    Nota Purchase Order PPSP ', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetXY(10, 25);
    $pdf->Cell(100, 7, 'Pergudangan SAFE N LOCK, Blok K 1707 - 1708', 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'Jl Lingkar timur KM 5,5', 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'Telp : +623158259871 , Fax, +623158259872', 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'Wechat / Skype / Line : papercupindonesia', 0, 1);
    $pdf->Ln(2);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'Supplier      : ' . $supplier['SupplierName'], 0, 1);
    $pdf->SetX(10);
    $pdf->Cell(100, 7, 'Alamat        : ' . $supplier['SupplierAdd'], 0, 1);
    $pdf->SetXY(90, 25);
    $pdf->Cell(0, 0, '', 0, 1);
    $pdf->SetX(130);
    $pdf->Cell(0, 7, 'No. PO        : ' . $header['PurchaseOrderID'], 0, 1);
    $pdf->SetX(130);
    $pdf->Cell(0, 7, 'Tanggal       : ' . $header['CreatedOn'], 0, 1);

    $pdf->SetX(130);
    $pdf->Cell(0, 7, 'Telepon       : ' . $supplier['Telepon'], 0, 1);
    $pdf->SetX(130);

    $pdf->Cell(0, 7, 'Keterangan  : ' . $header['Description'], 0, 1, 'L');
    $pdf->Ln(10);
    $pdf->Ln(10);
    $pdf->Ln(10);

    // Header tabel detail PO
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 10, 'No', 'T,B', 0, 'C');
    $pdf->Cell(35, 10, 'Nama Bahan Baku', 'T,B', 0, 'C');
    $pdf->Cell(45, 10, 'Jumlah', 'T,B', 0, 'R');
    $pdf->Cell(35, 10, 'Satuan', 'T,B', 0, 'C');
    $pdf->Cell(25, 10, 'Harga Satuan', 'T,B', 0, 'R');
    $pdf->Cell(32, 10, 'Subtotal', 'T,B', 0, 'R');
    $pdf->Cell(8, 10, '', 'T,B', 0, 'R');
    $pdf->Ln();

    // Isi tabel detail PO
    $no = 1;
    $total = 0;
    while ($row = mysqli_fetch_array($resultDetail)) {
        $total += $row['Subtotal'];

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(11, 8, $no++, 0, 0, 'C');
        $pdf->Cell(50, 8, $row['GoodsName'], 0, 0, 'L');
        $pdf->Cell(27, 8, number_format($row['Quantity'], 0), 0, 0, 'R');
        $pdf->Cell(32, 8, $row['UnitCD'], 0, 0, 'C');
        $pdf->Cell(25, 8, 'Rp.' . number_format($row['Price'], 0), 0, 0, 'R');
        $pdf->Cell(45, 8, 'Rp.' . number_format($row['Subtotal'], 0), 0, 1, 'R');
    }

    $pdf->Cell(0, 0, '', 'B');
    $pdf->Ln();

    // Total
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(150, 8, 'Total', 0, 0, 'R');
    $pdf->Cell(40, 8, 'Rp.' . number_format($total, 0), 0, 1, 'R');

    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(190, 8, 'Terbilang: ' . terbilang($total) . ' Rupiah', 0, 1, 'R');

    // Keterangan dan Tanda Tangan
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

    $pdf->Output('I', 'Purchase_Order_' . $header['PurchaseOrderID'] . '.pdf');

    $updatePrintCount = "UPDATE purchaseorderheader SET PrintCount = PrintCount + 1 WHERE PurchaseOrderID = '$purchaseOrderID'";
    mysqli_query($conn, $updatePrintCount);
}

function terbilang($x)
{
    $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    if ($x < 12)
        return " " . $abil[$x];
    elseif ($x < 20)
        return Terbilang($x - 10) . " Belas";
    elseif ($x < 100)
        return Terbilang($x / 10) . " Puluh" . Terbilang($x % 10);
    elseif ($x < 200)
        return " Seratus" . Terbilang($x - 100);
    elseif ($x < 1000)
        return Terbilang($x / 100) . " Ratus" . Terbilang($x % 100);
    elseif ($x < 2000)
        return " Seribu" . Terbilang($x - 1000);
    elseif ($x < 1000000)
        return Terbilang($x / 1000) . " Ribu" . Terbilang($x % 1000);
    elseif ($x < 1000000000)
        return Terbilang($x / 1000000) . " Juta" . Terbilang($x % 1000000);
}
?>