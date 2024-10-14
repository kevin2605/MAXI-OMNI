<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

//generate ID
$query = "SELECT OthExpenseID FROM othexpenseheader WHERE substr(JournalDate,6,2)='" . date("m") . "' ORDER BY 1 DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row["OthExpenseID"] != "") {
    $lastnumber = substr($row["OthExpenseID"], 6);
    $lastnumber = intval($lastnumber);
    $lastnumber += 1;
} else {
    $lastnumber = 1;
}
$blid = "BL" . date("ym") . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);

//parameter
$date = $_POST["tanggal"];
$memo = $_POST["memo"];
$memodesc = $_POST["memodesc"];
$desc = $_POST["desc"];
$creator = $_COOKIE["UserID"];
$createdOn = date('Y-m-d H:i:s');

$queryh = "INSERT INTO `othexpenseheader`(`OthExpenseID`, `JournalDate`, `CreatedOn`, `MemoID`, `MemoDesc`, `Description`, `CreatedBy`)
            VALUES ('$blid', '$date', '$createdOn', '$memo', '$memodesc', '$desc', '$creator')";
$resulth = mysqli_query($conn, $queryh);

if ($resulth == 1) {
    $arrakun = $_POST["akun"];
    $arrnamaakun = $_POST["namaakun"];
    $arrdebit = $_POST["debit"];
    $arrcredit = $_POST["credit"];

    for ($i = 0; $i < count($arrakun); $i++) {
        $akun = $arrakun[$i];
        $namaakun = $arrnamaakun[$i];
        $debit = !empty($arrdebit[$i]) ? intval($arrdebit[$i]) : 0;
        $credit = !empty($arrcredit[$i]) ? intval($arrcredit[$i]) : 0;

        // Insert into othexpensedetail
        $queryd = "INSERT INTO `othexpensedetail`(`OthExpenseID`, `JournalDate`, `AccountCD`, `AccountName`, `Debit`, `Credit`)
                   VALUES ('$blid','$date','$akun','$namaakun','$debit','$credit')";
        $resultd = mysqli_query($conn, $queryd);

        // Insert into journaldata with Notes as OthExpenseID
        $queryj = "INSERT INTO `journaldata`(`JournalDate`, `AccountCD`, `AccountName`, `Debit`, `Credit`, `Notes`)
                   VALUES ('$date','$akun','$namaakun','$debit','$credit','$blid')";
        $resultj = mysqli_query($conn, $queryj);
    }
}

if ($resulth && $resultd) {
    logAction($conn, $creator, 'Create', 'membuat Biaya Lain-lain', 0, $kodekategori);
    header("Location:../Financing/other-expenses.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'Add Biaya Lain-lain Failed', 1, $kodekategori);
    header("Location:../Financing/other-expenses.php?status=error");
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