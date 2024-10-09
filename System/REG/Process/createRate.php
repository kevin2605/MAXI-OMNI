<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$kodepajak = $_POST["kodepajak"];
$rate = $_POST["rate"];
$keterangan = $_POST["keterangan"];
$rateStatus = $_POST["rateStatus"];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO `taxrate` (`RateCD`, `RateNum`, `Description`, `Status`, `CreatedBy`, `CreatedOn`, `LastEdit`)
          VALUES ('$kodepajak', '$rate', '$keterangan', '$rateStatus', '$creator', '$createdOn', '$createdOn')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan kode pajak', 0, $kodepajak);
    header("Location:../Pajak/pajak.php?status=successRate");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan kode pajak', 1, $kodepajak);
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