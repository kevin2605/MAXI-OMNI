<?php
include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$urutan = $_POST["urutan"];
$kodegroup = $_POST["kodegroup"];
$namagroup = $_POST["namagroup"];
$status = $_POST["groupStatus"];
$createdOn = date('Y-m-d H:i:s');

// Update group data
$query = "UPDATE `groups` SET `GroupName` = ?, `Sequence` = ?, `Status` = ?, `LastEdit` = ? WHERE `GroupCD` = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("sisss", $namagroup, $urutan, $status, $createdOn, $kodegroup);
    $result = $stmt->execute();

    if ($result) {
        logAction($conn, $creator, 'Update', 'berhasil memperbarui grup', 0, $kodegroup);
        header("Location:../Category/group.php?status=success-edit");
    } else {
        logAction($conn, $creator, 'Update', 'gagal memperbarui grup', 1, $kodegroup);
        header("Location:../Category/group.php?status=error-edit");
    }

    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

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