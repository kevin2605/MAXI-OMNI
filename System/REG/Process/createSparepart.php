<?php

include "../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$urutanreport = $_POST["urutanreport"];
$kodebarang = $_POST["kodebarang"];
$namabarang = $_POST["namabarang"];
$satuan = $_POST["satuan"];
$kategori = $_POST["kategori"];
$group = $_POST["group"];
$supplier = $_POST["supplier"];
$keterangan1 = $_POST["keterangan1"];
$keterangan2 = $_POST["keterangan2"];
$keterangan3 = $_POST["keterangan3"];
$buyprice = $_POST["buyprice"];
$tax = $_POST["tax"];
$status = $_POST["Status"];
$creator = $_COOKIE["UserID"];
$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO `sparepart`(`PartCD`, `PartName`, `Sequence`, `StockQty`, `UnitCD`, `CategoryCD`, `GroupCD`, `SupplierNum`, `BuyPrice`, `Tax`, `Desc_1`, `Desc_2`, `Desc_3`, `LastEdit`, `Status`) 
VALUES ('$kodebarang','$namabarang','$urutanreport','0','$satuan','$kategori','$group','$supplier','$buyprice','$tax','$keterangan1','$keterangan2','$keterangan3','$createdOn','$status')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan sparepart', 0, $kodebarang);
    header("Location:../Other/sparepart.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan sparepart', 1, $kodebarang);
    header("Location:../Other/sparepart.php?status=error");
}

function logAction($conn, $userID, $actionDone, $actionMSG, $actionStatus, $recordID)
{
    $timestamp = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO systemlog (Timestamp, UserID, ActionDone, ActionMSG, ActionStatus, RecordID) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssss", $timestamp, $userID, $actionDone, $actionMSG, $actionStatus, $recordID);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
}

?>