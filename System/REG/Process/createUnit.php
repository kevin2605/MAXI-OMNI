<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$kodeSatuan = $_POST["kodesatuan"];
$satuan = $_POST["satuan"];
$keterangan = $_POST["keterangan"];
$status = $_POST["Status"];
$creator = $_COOKIE["UserID"];
$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO unit (UnitCD, UnitName, Description, Status, CreatedBy, CreatedOn, LastEdit)
            VALUES ('$kodeSatuan','$satuan','$keterangan','$status', '$creator', '$createdOn' , '$createdOn')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan unit', 0, $kodeSatuan);
    header("Location:../Unit/satuan.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan unit', 1, $kodeSatuan);
    header("Location:../Unit/satuan.php?status=error");
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