<?php

include "../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$advid = $_POST["advpaymentid"];
$invoice = $_POST["invoice"];
$invoiceid = explode(" ",$invoice);
$nominal = $_POST["nominal"];
$keterangan = $_POST["keterangan"];
$creator = $_COOKIE["UserID"] ?? 'unknown'; // Using cookie for creator
$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO `advancepaymentusage`(`AdvPaymentID`, `CreatedOn`, `InvoiceID`, `Amount`, `Description`)
            VALUES ('$advid', '$createdOn', '$invoice', '$nominal', '$keterangan')";
$result = mysqli_query($conn, $query);

//check if total usage same as amount, if yes update status 1.
$query = "SELECT * FROM advancepayment WHERE AdvPaymentID='".$advid."'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$tempTotal = $nominal + $row["TotalUsage"];
if($row["Amount"] == $tempTotal){
    $query = "UPDATE advancepayment SET TotalUsage =  TotalUsage+".$nominal.", Status='1' WHERE AdvPaymentID='".$advid."'";
    $result = mysqli_query($conn, $query);
}else{
    $query = "UPDATE advancepayment SET TotalUsage =  TotalUsage+".$nominal." WHERE AdvPaymentID='".$advid."'";
    $result = mysqli_query($conn, $query);
} 

//update invoice jadi lunas
$query = "UPDATE invoiceheader SET InvoiceStatus='1', TotalInvoice='".$nominal."', PaidDate='".$createdOn."' WHERE InvoiceID='".$invoiceid[0]."'";
$result = mysqli_query($conn, $query);


if ($result) {
    logAction($conn, $creator, 'Create', 'membuat Pembayaran ', 0, $advid);
    header("Location:../Payment/advance-payment.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'Add Pembayaran Failed', 1, $advid);
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