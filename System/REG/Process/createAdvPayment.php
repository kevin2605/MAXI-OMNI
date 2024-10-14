<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$query = "SELECT AdvPaymentID FROM advancepayment WHERE substr(CreatedOn,6,2)='" . date("m") . "' ORDER BY CreatedOn DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row["AdvPaymentID"] != "") {
    $lastnumber = substr($row["AdvPaymentID"], 9);
    $lastnumber = intval($lastnumber);
    $lastnumber += 1;
} else {
    $lastnumber = 1;
}

$advid = "ADV-" . date("ym") . "-" . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);
$customer = $_POST["customer"];
$tanggal = $_POST["tanggal"];
$nominal = $_POST["nominal"];
$account = explode(" - ", $_POST["account"]);
$accountcode = "2-1250";//$account[0];
$desc = $_POST["desc"];
$paymentoption = $_POST["paymentoption"];
$creator = $_COOKIE["UserID"] ?? 'unknown'; // Using cookie for creator
$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO advancepayment (`AdvPaymentID`, `CreatedOn`, `CreatedBy`, `CustID`, `AccountCD`, `Amount`, `Description`, `PaymentBy`, `TotalUsage`, `Status`)
            VALUES ('$advid', '$createdOn', '$creator', '$customer', '$accountcode', '$nominal','$desc','$paymentoption','0','0')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'membuat Nota Pembayaran di Awal', 0, $advid);
    header("Location:../Payment/advance-payment.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'Add Nota Pembayaran di Awal Failed', 1, $advid);
    header("Location:../Payment/advance-payment.php?status=error");
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