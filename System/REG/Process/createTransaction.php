<?php

echo "<pre>";
var_dump($_POST);
echo "</pre>";


include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");
$transactionID = $_POST["idtransaksi"];
$transactionDesc = $_POST["keterangan"];
$transactionType = $_POST["tipetransaksi"];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO transaction (TransactionID, TransDesc, TransType, CreatedBy, CreatedOn, LastEdit) 
          VALUES (?, ?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("ssssss", $transactionID, $transactionDesc, $transactionType, $creator, $createdOn, $createdOn);

    if ($stmt->execute()) {
        // Log aksi
        logAction($conn, $creator, 'Create', 'menambahkan tipe pembayaran', 0, $transactionID);
        header("Location:../Transaction/transaction.php?status=success");
    } else {
        logAction($conn, $creator, 'Create', 'gagal menambahkan tipe pembayaran ', 1, $transactionID);
        echo "Error: " . $stmt->error . "<br>";
        header("Location:../Transaction/transaction.php?status=error");
    }

    $stmt->close();
} else {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
}
function logAction($conn, $userID, $actionDone, $actionMSG, $actionStatus, $recordID)
{
    $timestamp = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO systemlog (Timestamp, UserID, ActionDone, ActionMSG, ActionStatus, RecordID) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssss", $timestamp, $userID, $actionDone, $actionMSG, $actionStatus, $recordID);
        if (!$stmt->execute()) {
            error_log("Log action failed: " . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
}
?>