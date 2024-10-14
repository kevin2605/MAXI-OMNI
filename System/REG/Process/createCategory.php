<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$kodekategori = $_POST["kodekategori"];
$namakategori = $_POST["namakategori"];
$status = $_POST["kategoriStatus"];
$creator = $_COOKIE["UserID"];
$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO category (CategoryCD, CategoryName, Status, CreatedBy, CreatedOn, LastEdit)
            VALUES ('$kodekategori', '$namakategori', '$status', '$creator', '$createdOn', '$createdOn')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'membuat kategori', 0, $kodekategori);
    header("Location:../Category/category.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'Add Category Failed', 1, $kodekategori);
    header("Location:../Category/category.php?status=error");
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