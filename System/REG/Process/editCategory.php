<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$kodekategori = $_POST["kodekategori"];
$namakategori = $_POST["namakategori"];
$status = $_POST["kategoriStatus"];
$createdOn = date('Y-m-d H:i:s');

$query = "UPDATE category SET CategoryName='$namakategori', Status='$status', LastEdit='$createdOn' WHERE CategoryCD='$kodekategori'";
$result = mysqli_query($conn, $query);


if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui kategori', 0, $kodekategori);
    header("Location:../Category/category.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui kategori', 1, $kodekategori);
    header("Location:../Category/category.php?status=error-edit");
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