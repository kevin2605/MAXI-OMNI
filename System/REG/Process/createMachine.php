<?php

include "../DBConnection.php";

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

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$datetime = date('Y-m-d H:i:s');

$query = "INSERT INTO `machine`(`MachineCD`, `MachineName`, `Sequence`, `Speed`, `Cavity`, `MinTargetPerHour`, `MaxTargetPerHour`, `CreatedOn`, `CreatedBy`, `Status`) 
            VALUES ('$machinecd','$machinename','$seq','$speed','$cavity','$mintarget','$maxtarget','$datetime','$creator','$machineStatus')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan mesin', 0, $machinecd);
    header("Location:../Production/machine.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan mesin', 1, $machinecd);
    echo "Error: " . mysqli_error($conn) . "<br>";
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