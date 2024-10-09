<?php

include "../DBConnection.php";

$rows = array();
$kueri = "SELECT c.CustID, c.CustName FROM salesorderheader AS soh, customer AS c WHERE soh.CustID = c.CustID AND soh.SalesOrderID='".$_POST["id"]."'";
$hasil = mysqli_query($conn,$kueri);
while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>