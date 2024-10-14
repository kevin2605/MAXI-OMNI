<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$contactnum = $_POST["contactnum"];
$namakontak = $_POST["namakontak"];
$alamat = $_POST["alamat"];
$email = $_POST["email"];
$telepon = $_POST["telepon"];
$handphone = $_POST["handphone"];
$keterangan = $_POST["keterangan"];
$status = $_POST["kontakStatus"];
$createdOn = date('Y-m-d H:i:s');

$query = "UPDATE contactperson SET 
            ContactName='$namakontak', ContactAdd='$alamat', ContactTel='$telepon', ContactPhone='$handphone', ContactEmail='$email',
            Description='$keterangan', Status='$status', LastEdit='$createdOn' WHERE ContactNum='$contactnum'";
$result = mysqli_query($conn, $query);

// Log the action
if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui contact person', 0, $contactnum);
    header("Location:../Contact/contact-person.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui contact person', 1, $contactnum);
    header("Location:../Contact/contact-person.php?status=error-edit");
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