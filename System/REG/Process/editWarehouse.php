<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Parameter
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$kodegudang = $_POST["kodegudang"];
$namagudang = $_POST["namagudang"];
$keterangan = $_POST["keterangan"];
$alamat = $_POST["alamat"];
$status = $_POST["gudangStatus"];
$createdOn = date('Y-m-d H:i:s');

// Update warehouse data
$query = "UPDATE warehouse SET WarehName='$namagudang', Description='$keterangan', Address='$alamat', Status='$status', LastEdit='$createdOn' WHERE WarehCD='$kodegudang'";
$result = mysqli_query($conn, $query);

// Log the action
if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui gudang', 0, $kodegudang);
    header("Location:../Warehouse/warehouse.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui gudang', 1, $kodegudang);
    header("Location:../Warehouse/warehouse.php?status=error-edit");
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