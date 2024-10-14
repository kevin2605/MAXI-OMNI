<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

if (isset($_GET["id"])) {
    $query = "UPDATE purchaseorderheader SET Finish=2 WHERE purchaseorderheader='" . $_GET["id"] . "'";
    $result = mysqli_query($conn, $query);
}

if ($result == 1) {
    header("Location:../Local-Purchasing/viewPurchaseOrder.php?status=so-close&id=" . $_GET["id"]);
} else {
    header("Location:../Local-Purchasing/viewPurchaseOrder.php?status=error&id=" . $_GET["id"]);
}

?>