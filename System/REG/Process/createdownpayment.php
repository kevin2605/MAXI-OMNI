<?php
include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Ambil data dari form
$salesOrderID = $_POST["salesorder"];
$amount = $_POST["amount"]; // Ambil nilai amount dari form
$description = $_POST["desc"];

// Ambil data user dari cookie
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');
$date = date('Y-m-d');

// Generate unique DPID
$dpidPrefix = 'DPINV-' . date('ym') . '-';

// Ambil nomor urut terakhir
$query = "
    SELECT DPID 
    FROM downpaymentheader 
    WHERE DPID LIKE '$dpidPrefix%'
    ORDER BY DPID DESC
    LIMIT 1
";
$res = mysqli_query($conn, $query);
if (!$res) {
    die('Query Error: ' . mysqli_error($conn));
}
$row = mysqli_fetch_assoc($res);

if ($row) {
    // Jika ada ID sebelumnya, ambil nomor urutnya
    $lastDPID = $row['DPID'];
    $lastNum = intval(substr($lastDPID, -4));
    $nextID = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
} else {
    // Jika tidak ada ID sebelumnya, mulai dari 1
    $nextID = str_pad(1, 4, '0', STR_PAD_LEFT);
}

$dpid = $dpidPrefix . $nextID;

// Check if the generated DPID already exists
$checkQuery = "SELECT COUNT(*) AS dup FROM downpaymentheader WHERE DPID='$dpid'";
$checkRes = mysqli_query($conn, $checkQuery);
if (!$checkRes) {
    die('Query Error: ' . mysqli_error($conn));
}
$checkRow = mysqli_fetch_assoc($checkRes);

if ($checkRow['dup'] == 0) {
    // DPID is unique, proceed with insertion
    // Format amount untuk memastikan sesuai dengan tipe data FLOAT
    $amount = str_replace(['Rp.', '.'], ['', ''], $amount); // Menghapus simbol dan titik
    $amount = (float) $amount; // Mengonversi string menjadi float

    // Masukkan data ke tabel downpaymentheader
    $queryHeader = "INSERT INTO downpaymentheader (DPID, CreatedOn, CreatedBy, SalesOrderID, Description) 
                    VALUES ('$dpid', '$createdOn', '$creator', '$salesOrderID', '$description')";
    $resultHeader = mysqli_query($conn, $queryHeader);

    if ($resultHeader) {
        // Masukkan data ke tabel downpaymentdetail
        $queryDetail = "INSERT INTO downpaymentdetail (DPID, CreatedOn, Amount) 
                        VALUES ('$dpid', '$createdOn', '$amount')";
        $resultDetail = mysqli_query($conn, $queryDetail);

        if ($resultDetail) {
            // Log sukses
            logAction($conn, $creator, 'Create', 'menambahkan down payment', 0, $dpid);
            header("Location:../Payment/downpayment.php?status=success");
        } else {
            // Log error pada insert ke downpaymentdetail
            error_log("MySQL Error on downpaymentdetail: " . mysqli_error($conn));
            logAction($conn, $creator, 'Create', 'gagal menambahkan down payment detail', 1, $dpid);
            header("Location:../Payment/downpayment.php?status=error");
        }
    } else {
        // Log error pada insert ke downpaymentheader
        error_log("MySQL Error on downpaymentheader: " . mysqli_error($conn));
        logAction($conn, $creator, 'Create', 'gagal menambahkan down payment header', 1, $dpid);
        header("Location:../Payment/downpayment.php?status=error");
    }

    //insert journal data
    $queryjd = "INSERT INTO `journaldata`(`JournalDate`, `AccountCD`, `AccountName`, `Debit`, `Credit`, `Notes`) 
    VALUES ('$date','2-1300','Uang Muka Pelanggan','$amount','0','$dpid')";
    $resultjd = mysqli_query($conn, $queryjd);
} else {
    // Jika DPID sudah ada, tangani sesuai kebutuhan
    error_log("DPID already exists: " . $dpid);
    header("Location:../Payment/downpayment.php?status=error&message=DPID already exists");
}

// Fungsi untuk mencatat log
function logAction($conn, $userID, $actionDone, $actionMSG, $actionStatus, $recordID)
{
    $timestamp = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO systemlog (Timestamp, UserID, ActionDone, ActionMSG, ActionStatus, RecordID) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssss", $timestamp, $userID, $actionDone, $actionMSG, $actionStatus, $recordID);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
}
?>