<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$nonpwp = $_POST["nonpwp"];
$npwpname = $_POST["npwpname"];
$npwpadd = $_POST["npwpadd"];
$status = $_POST["npwpStatus"];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO `taxdetail` (`NPWPNum`, `NPWPName`, `NPWPAddress`, `Status`, `CreatedBy`, `CreatedOn`, `LastEdit`) 
          VALUES ('$nonpwp', '$npwpname', '$npwpadd', '$status', '$creator', '$createdOn', '$createdOn')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan NPWP', 0, $nonpwp);
    header("Location:../Pajak/pajak.php?status=successNPWP");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan NPWP', 1, $nonpwp);
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