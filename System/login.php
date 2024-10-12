<?php

// Set timezone
date_default_timezone_set("Asia/Jakarta");

include "DBConnection.php";

$username = $_POST["username"];
$password = $_POST["password"];

$query = "SELECT * FROM systemuser WHERE Username='$username' AND Password='$password'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    // Update user status
    $queryu = "UPDATE systemuser SET OnlineVsOffline='Online' WHERE Username='$username' AND Password='$password'";
    $resultu = mysqli_query($conn, $queryu);

    // Set cookie
    setcookie("UserID", $row["UserID"], time() + 7200, "/");
    setcookie("Name", $row["Name"], time() + 7200, "/");
    setcookie("Status", "Online", time() + 7200, "/");

    // Log activity
    $creator = $row["UserID"];
    $datetime = date('Y-m-d H:i:s');
    $action = "Login";
    $status = $resultu ? 0 : 1; // 0 for success, 1 for failure
    $message = $resultu ? "berhasil login" : "Failed to update login status";

    $logQuery = "INSERT INTO systemlog (timestamp, UserID, ActionDone, ActionStatus, RecordID, ActionMSG) 
                 VALUES ('$datetime', '$creator', '$action', '$status', '$creator', '$message')";
    mysqli_query($conn, $logQuery);

    // Redirect to dashboard
    header("Location:ui-navigator.php");
} else {
    // Log failed login attempt
    $datetime = date('Y-m-d H:i:s');
    $action = "Login";
    $status = 1; // 1 for failure
    $message = "Login attempt failed for username: $username";

    $logQuery = "INSERT INTO systemlog (timestamp, UserID, ActionDone, ActionStatus, RecordID, ActionMSG) 
                 VALUES ('$datetime', NULL, '$action', '$status', NULL, '$message')";
    mysqli_query($conn, $logQuery);

    // Redirect to login page
    header("Location:../index.php?thr=user-not-found");
}

?>