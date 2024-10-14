<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$kode = $_POST["kode"];
$nama = $_POST["nama"];
$tipe = $_POST["tipe"];
$dbcr = $_POST["dbcr"];
$hddt = $_POST["hddt"];
$creator = $_COOKIE["UserID"];
$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO `chartofaccount`(`AccountCD`, `AccountName`, `AcctType`, `DebitVsCredit`, `HeaderVsDetail`)
            VALUES ('$kode', '$nama', '$tipe', '$dbcr', '$hddt')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'membuat kode akun', 0, $kodekategori);
    header("Location:../Accounting/chartofaccount.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'Add Account Code Failed', 1, $kodekategori);
    header("Location:../Accounting/chartofaccount.php?status=error");
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