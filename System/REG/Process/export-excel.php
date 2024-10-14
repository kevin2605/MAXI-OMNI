<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include "../../DBConnection.php";

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST["btnSearch"])) {
    $startDate = $_POST['startdate'];
    $endDate = $_POST['enddate'];

    $query = "SELECT 
        i.InvoiceID AS FK,
        '1' AS KD_JENIS_TRANSAKSI,
        '0' AS FG_PENGGANTI,
        i.TaxInvoiceNumber AS NOMOR_FAKTUR,
        MONTH(i.CreatedOn) AS MASA_PAJAK,
        YEAR(i.CreatedOn) AS TAHUN_PAJAK,
        i.TaxInvoiceDate AS TANGGAL_FAKTUR,
        c.CustID,
        c.NPWPNum AS NPWP,
        c.NPWPName AS NAMA,
        c.NPWPAddress AS ALAMAT_LENGKAP,
        i.TotalInvoice AS JUMLAH_DPP,
        i.DPAmount,
        (i.TotalInvoice - i.DPAmount) * 0.1 AS JUMLAH_PPN,
        0 AS JUMLAH_PPNBM,
        0 AS ID_KETERANGAN_TAMBAHAN,
        0 AS FG_UANG_MUKA,
        i.DPAmount AS UANG_MUKA_DPP,
        (i.DPAmount * 0.1) AS UANG_MUKA_PPN,
        0 AS UANG_MUKA_PPNBM,
        0 AS REFERENSI,
        0 AS KODE_DOKUMEN_PENDUKUNG
        FROM invoiceheader i
        JOIN customer c ON i.CustID = c.CustID
        WHERE i.CreatedOn BETWEEN ? AND ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    $spreadsheet = new Spreadsheet();

    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A1', 'FK, KD_JENIS_TRANSAKSI, FG_PENGGANTI, NOMOR_FAKTUR, MASA_PAJAK, TAHUN_PAJAK, TANGGAL_FAKTUR, NPWP, NAMA, ALAMAT_LENGKAP, JUMLAH_DPP, JUMLAH_PPN, JUMLAH_PPNBM, ID_KETERANGAN_TAMBAHAN, FG_UANG_MUKA, UANG_MUKA_DPP, UANG_MUKA_PPN, UANG_MUKA_PPNBM, REFERENSI, KODE_DOKUMEN_PENDUKUNG');

    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A2', 'LT, NPWP, NAMA, JALAN, BLOK, NOMOR, RT, RW, KECAMATAN, KELURAHAN, KABUPATEN, PROVINSI, KODE_POS, TELEPON');

    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A3', 'OF, KODE_OBJEK, NAMA, HARGA_SATUAN, JUMLAH_BARANG, HARGA_TOTAL, DISKON, DPP, PPN, TARIF PPNBM, PPNBM');

    $rowNumber = 4;
    $invoiceIDs = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $totalInvoice = $row['JUMLAH_DPP'];
            $dpAmount = $row['DPAmount'];
            $remainingDPP = $totalInvoice - $dpAmount;

            if ($dpAmount > 0) {
                $dppAfterDP = $remainingDPP / 1.1;
                $ppnAfterDP = $remainingDPP - $dppAfterDP;
            } else {
                $dppAfterDP = $totalInvoice / 1.1;
                $ppnAfterDP = $totalInvoice - $dppAfterDP;
            }

            // Membulatkan hasil perhitungan ke bilangan bulat
            $dppAfterDP = round($dppAfterDP);
            $ppnAfterDP = round($ppnAfterDP);
            $dpAmount = round($dpAmount);
            $dpAmountPPN = round($dpAmount * 0.1);

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue(
                    'A' . $rowNumber,
                    "FK," .
                    $row['KD_JENIS_TRANSAKSI'] . ',' .
                    $row['FG_PENGGANTI'] . ',' .
                    str_replace(['.', '-'], '', $row['NOMOR_FAKTUR']) . ',' .
                    $row['MASA_PAJAK'] . ',' .
                    $row['TAHUN_PAJAK'] . ',' .
                    DateTime::createFromFormat('Y-m-d', $row['TANGGAL_FAKTUR'])->format('d/m/Y') . ',' .
                    $row['NPWP'] . ',' .
                    $row['NAMA'] . ',' .
                    str_replace(',', '', $row['ALAMAT_LENGKAP']) . ',' .  // Menghapus koma
                    $dppAfterDP . ',' .
                    $ppnAfterDP . ',' .
                    $row['JUMLAH_PPNBM'] . ',' .
                    $row['ID_KETERANGAN_TAMBAHAN'] . ',' .
                    $row['FG_UANG_MUKA'] . ',' .
                    $dpAmount . ',' .
                    $dpAmountPPN . ',' .
                    '0,' .
                    $row['FK'] . ',' .
                    $row['KODE_DOKUMEN_PENDUKUNG']
                );
            $rowNumber++;
            $query3 = "SELECT 
              invoicedetail.InvoiceID, 
              invoicedetail.ProductCD, 
              invoicedetail.Quantity, 
              invoicedetail.Price, 
              invoicedetail.Discount, 
              (invoicedetail.Quantity * invoicedetail.Price) AS Subtotal,
              product.ProductName
           FROM 
              invoicedetail
           INNER JOIN 
              product ON invoicedetail.ProductCD = product.ProductCD
           WHERE 
              invoicedetail.InvoiceID = ?";

            $stmt3 = $conn->prepare($query3);
            $stmt3->bind_param("s", $row['FK']);
            $stmt3->execute();
            $result3 = $stmt3->get_result();

            if ($result3->num_rows > 0) {
                while ($row3 = $result3->fetch_assoc()) {
                    $ppn = $row3['Subtotal'] * 0.1;
                    $dpp = $row3['Subtotal'] - $row3['Discount'];
                    $ppnbm = 0;
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue(
                            'A' . $rowNumber,
                            'OF,' .
                            $row3['ProductCD'] . ',' .
                            $row3['ProductName'] . ',' .  // Menambahkan ProductName
                            $row3['Price'] . ',' .
                            $row3['Quantity'] . ',' .
                            $row3['Subtotal'] . ',' .
                            $row3['Discount'] . ',' .
                            $dpp . ',' .
                            $ppn . ',' .
                            $ppnbm . ',' .
                            '0'
                        );
                    $rowNumber++;
                }
            } else {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $rowNumber, "No detail data found for invoice " . $row['FK']);
                $rowNumber++;
            }

            $stmt3->close();
        }
    } else {
        echo "<tr><td colspan='20'>No results found.</td></tr>";
    }


    //     if ($result->num_rows > 0) {
    //     while ($row = $result->fetch_assoc()) {
    //         $totalInvoice = $row['JUMLAH_DPP'];
    //         $dpAmount = $row['DPAmount'];
    //         $remainingDPP = $totalInvoice - $dpAmount;

    //         if ($dpAmount > 0) {
    //             $dppAfterDP = $remainingDPP / 1.1;
    //             $ppnAfterDP = $remainingDPP - $dppAfterDP;
    //         } else {
    //             $dppAfterDP = $totalInvoice / 1.1;
    //             $ppnAfterDP = $totalInvoice - $dppAfterDP;
    //         }
    //         $invoiceIDs[] = $row['FK']; 
    //         $spreadsheet->setActiveSheetIndex(0)
    //             ->setCellValue(
    //                 'A' . $rowNumber, 
    //                 "FK " . ',"' . $row['KD_JENIS_TRANSAKSI'] . '"' .
    //                 ',"' . $row['FG_PENGGANTI'] . '"' . ',"' . $row['NOMOR_FAKTUR'] . '"' .
    //                 ',"' . $row['MASA_PAJAK'] . '"' . ',"' . $row['TAHUN_PAJAK'] . '"' .
    //                 ',"' . $row['TANGGAL_FAKTUR'] . '"' . ',"' . $row['NPWP'] . '"' .
    //                 ',"' . $row['NAMA'] . '"' . ',"' . $row['ALAMAT_LENGKAP'] . '"' .
    //                 ',"' . number_format($dppAfterDP, 0) . '"' . ',"' . number_format($ppnAfterDP, 0) . '"' .
    //                 ',"' . $row['JUMLAH_PPNBM'] . ',"' . $row['ID_KETERANGAN_TAMBAHAN'] . '"' .
    //                 ',"' . $row['FG_UANG_MUKA'] . '"' . ',"' . number_format($dpAmount, 0) . '"' .
    //                 ',"' . number_format($dpAmount * 0.1, 0) . ',"' . " 0" . '"' .
    //                 ',"' . $row['FK'] . '"' . ',"' . $row['KODE_DOKUMEN_PENDUKUNG'] . '"'
    //             );
    //         $rowNumber++;
    //     }
    // } else {
    //     echo "<tr><td colspan='20'>No results found.</td></tr>";
    // }
    //     $stmt->close();
    // if (isset($invoiceIDs) && count($invoiceIDs) > 0) {
    //     foreach ($invoiceIDs as $invoiceID) {
    //         $query3 = "SELECT InvoiceID, ProductCD,  Quantity, Price, Discount, 
    //     (Quantity * Price) AS Subtotal 
    //     FROM invoicedetail 
    //     WHERE InvoiceID = ?";
    //         $stmt3 = $conn->prepare($query3);
    //         $stmt3->bind_param("s", $invoiceID);
    //         $stmt3->execute();
    //         $result3 = $stmt3->get_result();

    //             if ($result3->num_rows > 0) {
    //             while ($row3 = $result3->fetch_assoc()) {
    //                 $ppn = $row3['Subtotal'] * 0.1;
    //                 $dpp = $row3['Subtotal'] - $row3['Discount']; 
    //                 $ppnbm = 0; 

    //                 if ($row3) { 
    //                     $spreadsheet->setActiveSheetIndex(0)
    //                         ->setCellValue(
    //                             'A' . $rowNumber, 
    //                             'OF,' .
    //                             '"' . $row3['ProductCD'] . '",' .
    //                             '"' . $row3['Price'] . '",' .
    //                             '"' . $row3['Quantity'] . '",' .
    //                             '"' . $row3['Subtotal'] . '",' .
    //                             '"' . $row3['Discount'] . '",' .
    //                             '"' . $dpp . '",' .
    //                             '"' . $ppn . '",' .
    //                             '"' . $ppnbm . '",' .
    //                             "0" . '"' 
    //                         );
    //                     $rowNumber++;
    //                 } else {
    //                     $spreadsheet->setActiveSheetIndex(0)
    //                         ->setCellValue('A' . $rowNumber, "No product data found.");
    //                 }

    //                 }
    //         }
    //         $stmt3->close();
    //     }
    // }

    // $queryCustomer = "SELECT 
    // c.NPWPNum AS NPWP,
    // c.NPWPName AS NPWPName,
    // c.NPWPAddress AS NPWPAddress,
    // c.CustName AS NAMA,
    // c.KTPAddress AS JALAN,
    // c.PhoneNumOne AS TELEPON
    // FROM invoiceheader i
    // JOIN customer c ON i.CustID = c.CustID
    // WHERE i.InvoiceID = ?"; 

    //     $displayedCustomers = []; 
    // if ($result->num_rows > 0) {
    //     $result->data_seek(0); 

    //         while ($row = $result->fetch_assoc()) {
    //         $invoiceID = $row['FK'];
    //         $stmtCustomer = $conn->prepare($queryCustomer);
    //         $stmtCustomer->bind_param("s", $invoiceID);
    //         $stmtCustomer->execute();
    //         $resultCustomer = $stmtCustomer->get_result();

    //             if ($resultCustomer->num_rows > 0) {
    //             $customerData = $resultCustomer->fetch_assoc();

    //             $alamatLengkap = $customerData['NPWPAddress'];
    //             $parts = explode(", ", $alamatLengkap);

    //                 $kelurahan = isset($parts[0]) ? $parts[0] : '';
    //             $jalan = isset($parts[1]) ? $parts[1] : '';
    //             $kecamatan = isset($parts[2]) ? str_replace('Kec. ', '', $parts[2]) : '';
    //             $kabupaten = isset($parts[3]) ? $parts[3] : '';
    //             $lastPart = isset($parts[4]) ? $parts[4] : '';
    //             $pos = strrpos($lastPart, ' ');
    //             if ($pos !== false) {
    //                 $provinsi = substr($lastPart, 0, $pos);
    //                 $kodePos = substr($lastPart, $pos + 1);
    //             } else {
    //                 $provinsi = $lastPart;
    //                 $kodePos = '';
    //             }


    //             if (!in_array($customerData['NPWP'], $displayedCustomers)) {
    //                 $spreadsheet->setActiveSheetIndex(0)
    //                     ->setCellValue(
    //                         'A' . $rowNumber, 
    //                         '"' . $customerData['NPWP'] . '",' .
    //                         '"' . $customerData['NPWPName'] . '",' .
    //                         '"' . $jalan . '",' .
    //                         '"' . '",' .
    //                         '"' . '",' .
    //                         '"' . '",' .
    //                         '"' . '",' .
    //                         '"' . $kecamatan . '",' .
    //                         '"' . $kelurahan . '",' .
    //                         '"' . $kabupaten . '",' .
    //                         '"' . $provinsi . '",' .
    //                         '"' . $kodePos . '",' .
    //                         '"' . $customerData['TELEPON'] . '",'
    //                     );


    //                 $displayedCustomers[] = $customerData['NPWP'];

    //                 $rowNumber++;
    //             }
    //         }

    //             $stmtCustomer->close(); 
    //     }
    // } else {
    //     $spreadsheet->setActiveSheetIndex(0)
    //         ->setCellValue('A' . $rowNumber, "No invoice data found.");
    // }



    $spreadsheet->getActiveSheet()->setTitle('Invoice');

    $writer = new Xlsx($spreadsheet);
    $filename = "Pajak_" . date('d/m/Y') . ".xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;

    $conn->close();
}
?>