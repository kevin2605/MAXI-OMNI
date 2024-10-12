<?php

// Set timezone
date_default_timezone_set("Asia/Jakarta");

include "DBConnection.php";

if (isset($_COOKIE["UserID"])) {
    $userID = $_COOKIE["UserID"];
    $datetime = date('Y-m-d H:i:s');

    // Update user status
    $queryu = "UPDATE systemuser SET OnlineVsOffline='Offline' WHERE UserID='$userID'";
    $resultu = mysqli_query($conn, $queryu);

    // Set cookie to expire
    setcookie("UserID", "", time() - 3600, "/");
    setcookie("Name", "", time() - 3600, "/");
    setcookie("Status", "", time() - 3600, "/");

    // Log activity
    $action = "Logout";
    $status = $resultu ? 0 : 1; // 0 for success, 1 for failure
    $message = $resultu ? "berhasil logout" : "Failed to update logout status";

    $logQuery = "INSERT INTO systemlog (timestamp, UserID, ActionDone, ActionStatus, RecordID, ActionMSG) 
                 VALUES ('$datetime', '$userID', '$action', '$status', '$userID', '$message')";
    mysqli_query($conn, $logQuery);

    // Redirect to home page
    header("Location:index.php");
} else {
    // Log failed logout attempt
    $datetime = date('Y-m-d H:i:s');
    $action = "Logout";
    $status = 1; // 1 for failure
    $message = "Logout attempt failed, no user cookie found";

    $logQuery = "INSERT INTO systemlog (timestamp, UserID, ActionDone, ActionStatus, RecordID, ActionMSG) 
                 VALUES ('$datetime', NULL, '$action', '$status', NULL, '$message')";
    mysqli_query($conn, $logQuery);

    // Redirect to home page
    header("Location:index.php");
}

?>