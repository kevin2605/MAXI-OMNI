<?php

include "../../DBConnection.php";

date_default_timezone_set("Asia/Jakarta");

$kodegudang = $_POST["kodegudang"];
$namagudang = $_POST["namagudang"];
$keterangan = $_POST["keterangan"];
$alamat = $_POST["alamat"];
$status = $_POST["gudangStatus"];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO warehouse (WarehCD, WarehName, Description, Address, Status, CreatedBy, CreatedOn, LastEdit)
          VALUES ('$kodegudang', '$namagudang', '$keterangan', '$alamat', '$status', '$creator', '$createdOn', '$createdOn')";

echo "Query: $query<br>";

$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahakan gudang', 0, $kodegudang);
    header("Location:../Warehouse/warehouse.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahakan gudang', 1, $kodegudang);
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