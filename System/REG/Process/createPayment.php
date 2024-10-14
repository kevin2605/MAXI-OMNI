<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$kode = $_POST["kode"];
$pembayaran = $_POST["pembayaran"];
$keterangan = $_POST["keterangan"];
$status = $_POST["payStatus"];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO `payment`(`PaymentCD`, `PaymentName`, `Description`, `Status`, `CreatedBy`, `CreatedOn`, `LastEdit`) 
          VALUES ('$kode','$pembayaran','$keterangan','$status','$creator','$createdOn','$createdOn')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan tipe transaksi', 0, $kode);
    header("Location:../Payment/payment.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'Add Payment Failed', 1, $kode);
    echo "Error: " . mysqli_error($conn) . "<br>";

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