<?php

include "../DBConnection.php";

$hasil = 0;

$kueri = "DELETE FROM pricelistdetail WHERE No=".$_POST["nomor"]."";
$hasil = mysqli_query($conn,$kueri);

echo $hasil;
?>