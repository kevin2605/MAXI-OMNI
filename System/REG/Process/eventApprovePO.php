<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$POID = $_GET["id"];
$kategori = $_GET["po"];
$datetime = date('Y-m-d H:i:s');

//update approval list table
$queryu = "UPDATE purchaseorderheader SET ApprovalStatus='Approved', ApprovalBy='" . $_COOKIE["UserID"] . "', ApprovalOn='" . $datetime . "' WHERE PurchaseOrderID='" . $POID . "'";
$resultu = mysqli_query($conn, $queryu);

if ($resultu == 1) {
    header("Location:../Local-Purchasing/viewPO" . $kategori . ".php?id=" . $POID . "&status=approved");
} else {
    header("Location:../Local-Purchasing/viewPO" . $kategori . ".php?id=" . $POID . "&status=error");
}

?>