<?php

include "../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$query = "
    SELECT GenJourID 
    FROM genjournalheader 
    WHERE DATE_FORMAT(CreatedOn, '%y%m') = '" . date("ym") . "' 
    ORDER BY GenJourID DESC 
    LIMIT 1";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $lastnumber = intval(substr($row["GenJourID"], 6)) + 1;
} else {
    $lastnumber = 1;
}
$gjid = "GJ" . date("ym") . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);

$date = $_POST["tanggal"];
$memo = $_POST["memo"];
$memodesc = $_POST["memodesc"];
$desc = $_POST["desc"];
$creator = $_COOKIE["UserID"];
$createdOn = date('Y-m-d H:i:s');

$queryh = "INSERT INTO `genjournalheader`(`GenJourID`, `JournalDate`, `CreatedOn`, `MemoID`, `MemoDesc`, `Description`, `CreatedBy`)
            VALUES ('$gjid', '$date', '$createdOn', '$memo', '$memodesc', '$desc', '$creator')";
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
        echo $akun . $namaakun . $debit . $credit . "<br>";

        $queryd = "INSERT INTO `genjournaldetail`(`GenJourID`, `JournalDate`, `AccountCD`, `AccountName`, `Debit`, `Credit`)
                   VALUES ('$gjid','$date','$akun','$namaakun','$debit','$credit')";
        $resultd = mysqli_query($conn, $queryd);

        $queryj = "INSERT INTO `journaldata`(`JournalDate`, `AccountCD`, `AccountName`, `Debit`, `Credit`, `Notes`)
                   VALUES ('$date','$akun','$namaakun','$debit','$credit','$gjid')";
        $resultj = mysqli_query($conn, $queryj);
    }
}

if ($resulth && $resultd) {
    logAction($conn, $creator, 'Create', 'membuat General Journal', 0, $kodekategori);
    header("Location:../Financing/general-journal.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'Add General Journal Failed', 1, $kodekategori);
    header("Location:../Financing/general-journal.php?status=error");
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