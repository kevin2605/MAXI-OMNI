<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$kode = $_POST["kode"];
$pembayaran = $_POST["pembayaran"];
$keterangan = $_POST["keterangan"];
$status = $_POST["payStatus"];
$createdOn = date('Y-m-d H:i:s');

$query = "UPDATE `payment` SET 
            `PaymentName`='$pembayaran', `Description`='$keterangan', `Status`='$status', `LastEdit`='$createdOn' 
          WHERE `PaymentCD`='$kode'";
$result = mysqli_query($conn, $query);

// Log the action
if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui payment', 0, $kode);
    header("Location:../Payment/payment.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui payment', 1, $kode);
    header("Location:../Payment/payment.php?status=error-edit");
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