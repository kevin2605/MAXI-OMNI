<?php

include "../../DBConnection.php";

date_default_timezone_set("Asia/Jakarta");

$urutan = $_POST["urutan"];
$kodegroup = $_POST["kodegroup"];
$namagroup = $_POST["namagroup"];
$status = $_POST["groupStatus"];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO `groups` (GroupCD, GroupName, Sequence, Status, CreatedBy, CreatedOn, LastEdit)
          VALUES ('$kodegroup', '$namagroup', '$urutan', '$status', '$creator', '$createdOn', '$createdOn')";

echo "Query: $query<br>";

$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'membuat grup', 0, $kodegroup);
    header("Location:../Category/group.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal membuat grup', 1, $kodegroup);
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