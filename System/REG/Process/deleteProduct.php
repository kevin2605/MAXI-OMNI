<?php

include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$prodcd = $_GET["prodcd"];

$query = "DELETE FROM product WHERE ProductCD='$prodcd'";
$result = mysqli_query($conn, $query);

if ($result == 1) {
    header("Location:../Product/product.php?status=success-delete");
} else {
    header("Location:../Product/product.php?status=error-delete");
}

?>