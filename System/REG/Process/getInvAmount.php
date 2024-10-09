<?php

include "../DBConnection.php";

$rows = array();
$kueri = "SELECT TotalInvoice FROM invoiceheader WHERE InvoiceID='".$_POST["invid"]."'";
$hasil = mysqli_query($conn,$kueri);
while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>