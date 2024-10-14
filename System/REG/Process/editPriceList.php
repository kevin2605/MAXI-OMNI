<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Parameter
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$PLcode = $_POST["PLcode"];
$PLname = $_POST["PLname"];
$minorder = $_POST["minorder"];
$startdate = $_POST["startdate"];
$enddate = $_POST["enddate"];
$createdOn = date('Y-m-d H:i:s');

// Update price list data
$query = "UPDATE pricelistheader SET PriceListName='$PLname', MinimalOrder='$minorder', StartDate='$startdate', EndDate='$enddate' WHERE PriceListCD='$PLcode'";
$result = mysqli_query($conn, $query);

// Log the action
if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui price list', 0, $PLcode);
    header("Location:../Product/price-list.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui price list', 1, $PLcode);
    header("Location:../Product/price-list.php?status=error-edit");
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