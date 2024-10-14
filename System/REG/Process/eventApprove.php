<?php

include "../../DBConnection.php";
include "eventProcessInv.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}


$SOID = $_GET["id"];
$datetime = date('Y-m-d H:i:s');

//update approval list table
$queryu = "UPDATE salesorderheader SET ApprovalStatus='Approved', ApprovalBy='" . $_COOKIE["UserID"] . "', ApprovalOn='" . $datetime . "' WHERE SalesOrderID='" . $SOID . "'";
$resultu = mysqli_query($conn, $queryu);

if ($resultu == 1) {
    header("Location:../Sales/viewSalesOrder.php?id=" . $SOID . "&status=approved");
} else {
    header("Location:../Sales/viewSalesOrder.php?id=" . $SOID . "&status=error");
}

?>