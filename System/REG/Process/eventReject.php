<?php

include "../DBConnection.php";
include "eventProcessInv.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$SOID = $_GET["id"];
$datetime = date('Y-m-d H:i:s');

//update approval list table
$queryu = "UPDATE salesorderheader SET ApprovalStatus='Reject', ApprovalBy='".$_COOKIE["UserID"]."', ApprovalOn='".$datetime."' WHERE SalesOrderID='".$SOID."'";
$resultu = mysqli_query($conn,$queryu);

if($resultu == 1){
    header("Location:../Sales/viewSalesOrder.php?id=" . $SOID . "&status=reject");
}else{
    header("Location:../Sales/viewSalesOrder.php?id=" . $SOID . "&status=error");
}

?>