<?php

include "../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$POID = $_GET["id"];
$kategori = $_GET["po"];
$datetime = date('Y-m-d H:i:s');

//update approval list table
$queryu = "UPDATE importpurchaseorderheader SET ApprovalStatus='Reject', ApprovalBy='".$_COOKIE["UserID"]."', ApprovalOn='".$datetime."' WHERE PurchaseOrderID='".$POID."'";
$resultu = mysqli_query($conn,$queryu);

if($resultu == 1){
    header("Location:../Import-Purchasing/viewIPO".$kategori.".php?id=" . $POID . "&status=reject");
}else{
    header("Location:../Import-Purchasing/viewIPO".$kategori.".php?id=" . $POID . "&status=error");
}

?>