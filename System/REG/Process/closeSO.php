<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

if (isset($_GET["id"])) {
    $query = "UPDATE salesorderheader SET Finish=2 WHERE SalesOrderID='" . $_GET["id"] . "'";
    $result = mysqli_query($conn, $query);
}

if ($result == 1) {
    header("Location:../Sales/viewSalesOrder.php?status=so-close&id=" . $_GET["id"]);
} else {
    header("Location:../Sales/viewSalesOrder.php?status=error&id=" . $_GET["id"]);
}

?>