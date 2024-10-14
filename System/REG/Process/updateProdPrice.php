<?php

include "../../DBConnection.php";

$kueri = "UPDATE pricelistdetail SET Price=" . $_POST["price"] . " WHERE No=" . $_POST["nomor"] . "";
$hasil = mysqli_query($conn, $kueri);

echo $hasil;
?>