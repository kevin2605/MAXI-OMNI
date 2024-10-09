<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$nonpwp = $_POST["nonpwp"];
$npwpname = $_POST["npwpname"];
$npwpadd = $_POST["npwpadd"];
$status = $_POST["npwpStatus"];
$createdOn = date('Y-m-d H:i:s');

$query = "UPDATE `taxdetail` SET 
            `NPWPName`='$npwpname', `NPWPAddress`='$npwpadd', `Status`='$status', `LastEdit`='$createdOn' 
          WHERE `NPWPNum`='$nonpwp'";
$result = mysqli_query($conn, $query);

// Log the action
if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui NPWP', 0, $nonpwp);
    header("Location:../Pajak/pajak.php?status=successNPWP-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui NPWP', 1, $nonpwp);
    header("Location:../Pajak/pajak.php?status=error-edit");
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