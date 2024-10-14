<?php

include "../../DBConnection.php";

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
$datetime = date('Y-m-d H:i:s');

$query = "UPDATE `sparepart` SET `PartName`='$namabarang',`Sequence`='$urutanreport',`UnitCD`='$satuan',`CategoryCD`='$kategori',`GroupCD`='$group',
          `SupplierNum`='$supplier',`BuyPrice`='$buyprice', `Tax`='$tax',`Desc_1`='$keterangan1',`Desc_2`='$keterangan2',`Desc_3`='$keterangan3',`LastEdit`='$datetime',
          `Status`='$status' WHERE `PartCD`='$kodebarang'";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Update', 'berhasil memperbaharui sparepart', 0, $kodebarang);
    header("Location:../Other/sparepart.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbaharui sparepart', 1, $kodebarang);
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