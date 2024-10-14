<?php

include "../../DBConnection.php";

$rows = array();
$kueri = "SELECT * FROM salesorderdetail AS sod, product AS p WHERE sod.ProductCD = p.ProductCD AND sod.SalesOrderID='" . $_POST["id"] . "' AND sod.Quantity > sod.QuantitySent";
$hasil = mysqli_query($conn, $kueri);
while ($row = mysqli_fetch_array($hasil)) {
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>