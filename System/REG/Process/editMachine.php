<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$machinecd = $_POST["machinecd"];
$machinename = $_POST["machinename"];
$seq = $_POST["seq"];
$speed = $_POST["speed"];
$cavity = $_POST["cavity"];
$mintarget = $_POST["mintarget"];
$maxtarget = $_POST["maxtarget"];
$machineStatus = $_POST["machineStatus"];
$creator = $_COOKIE["UserID"] ?? 'unknown'; // Using cookie for creator

$datetime = date('Y-m-d H:i:s');

$query = "UPDATE `machine` SET 
            `MachineName`='$machinename',
            `Sequence`='$seq',
            `Speed`='$speed',
            `Cavity`='$cavity',
            `MinTargetPerHour`='$mintarget',
            `MaxTargetPerHour`='$maxtarget',
            `Status`='$machineStatus' 
          WHERE `MachineCD`='$machinecd'";
$result = mysqli_query($conn, $query);

if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui machine', 0, $machinecd);
    header("Location:../Production/machine.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui machine', 1, $machinecd);
    header("Location:../Production/machine.php?status=error");
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