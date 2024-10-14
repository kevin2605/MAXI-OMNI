<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

//generate random serialid
$serialid = 0;
$duplicate = true;
while ($duplicate) {
    $serialid = rand(10000, 99999);
    $query = "SELECT COUNT(*) as duplicate FROM taxserialnumber WHERE SerialID='" . $serialid . "'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    echo $serialid;
    ($row["duplicate"] > 0) ? $duplicate = true : $duplicate = false;
}

//parameter
$keterangan = $_POST["keterangan"];
$prefix = $_POST["prefix"];
$startdate = $_POST["startdate"];
$enddate = $_POST["enddate"];
$startnum = $_POST["startnum"];
$endnum = $_POST["endnum"];
$totalnum = $endnum - $startnum;
$creator = $_COOKIE["UserID"] ?? 'unknown'; // Menggunakan cookie untuk creator
$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO taxserialnumber (`SerialID`, `Description`, `Prefix`, `StartDate`, `EndDate`, `StartNumber`, `EndNumber`, `LastNumberFlag`, `UsedNumber`, `TotalNumber`)
            VALUES ('$serialid','$keterangan','$prefix','$startdate', '$enddate', '$startnum' , '$endnum','0','0','$totalnum')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan nomor seri pajak', 0, $serialid);
    header("Location:../Pajak/tax-number.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan nomor seri pajak', 1, $serialid);
    header("Location:../Pajak/tax-number.php?status=error");
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