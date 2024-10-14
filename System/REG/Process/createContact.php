<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$namakontak = $_POST["namakontak"];
$alamat = $_POST["alamat"];
$email = $_POST["email"];
$telepon = $_POST["telepon"];
$handphone = $_POST["handphone"];
$keterangan = $_POST["keterangan"];
$status = $_POST["kontakStatus"];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}
$createdOn = date('Y-m-d H:i:s');

// Check contactnum
$duplicate = true;
$contactnum = 0;

while ($duplicate) {
    $contactnum = rand(10000, 99999);
    $query = "SELECT COUNT(*) AS dup FROM contactperson WHERE ContactNum='$contactnum'";
    $res = mysqli_query($conn, $query);
    $ctr = mysqli_fetch_assoc($res);
    $row = $ctr["dup"];

    if ($row >= 1) {
        $duplicate = true;
    } else {
        $duplicate = false;
    }
}

$query = "INSERT INTO contactperson (ContactNum, ContactName, ContactAdd, ContactTel, ContactPhone, ContactEmail, Description, Status, CreatedBy, CreatedOn, LastEdit)
          VALUES ('$contactnum', '$namakontak', '$alamat', '$telepon', '$handphone', '$email', '$keterangan', '$status', '$creator', '$createdOn', '$createdOn')";
$result = mysqli_query($conn, $query);

if ($result == 1) {
    logAction($conn, $creator, 'Create', 'menambahkan kontak', 0, $contactnum);
    header("Location:../Contact/contact-person.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan kontak', 1, $contactnum);
    header("Location:../Contact/contact-person.php?status=error");
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