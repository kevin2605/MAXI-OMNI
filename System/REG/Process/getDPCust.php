<?php

include "../../DBConnection.php";

$salesOrderID = $_POST["id"];
$salesOrderID = mysqli_real_escape_string($conn, $salesOrderID);

$kueri = "
    SELECT d.Amount 
    FROM downpaymentheader AS h
    JOIN downpaymentdetail AS d ON h.DPID = d.DPID
    WHERE h.SalesOrderID = '$salesOrderID'
";
$hasil = mysqli_query($conn, $kueri);
if (!$hasil) {
    die('Error: ' . mysqli_error($conn));
}

$rows = array();
while ($row = mysqli_fetch_array($hasil, MYSQLI_ASSOC)) {
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;

?>