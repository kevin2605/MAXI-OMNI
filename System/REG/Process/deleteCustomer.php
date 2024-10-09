<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

if (isset($_GET["id"])) {
    $custID = $_GET["id"];

    // Hapus data customer
    $query = "DELETE FROM customer WHERE CustID='$custID'";
    $result = mysqli_query($conn, $query);

    // Logging aktivitas
    $creator = isset($_COOKIE["UserID"]) ? $_COOKIE["UserID"] : 'System';
    $datetime = date('Y-m-d H:i:s');
    $action = "Delete";
    $status = $result ? 0 : 1; // 0 for success, 1 for failure
    $message = $result ? "Successfully deleted customer with ID $custID" : "Failed to delete customer with ID $custID";

    $logQuery = "INSERT INTO systemlog (timestamp, UserID, ActionDone, ActionStatus, RecordID, ActionMSG) 
                 VALUES ('$datetime', '$creator', '$action', '$status', '$custID', '$message')";
    mysqli_query($conn, $logQuery);

    if ($result) {
        header("Location:../Customer/customer.php?status=success-delete");
    } else {
        header("Location:../Customer/customer.php?status=error");
    }
} else {
    header("Location:../Customer/customer.php?status=error");
}

?>