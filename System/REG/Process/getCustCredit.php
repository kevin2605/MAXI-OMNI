<?php

include "../DBConnection.php";

$rows = array();
$kueri = "SELECT inv.InvoiceID, inv.SalesOrderID, inv.CreatedOn, su.Name, inv.Description, inv.TotalInvoice, inv.DPAmount,inv.TotalPaid
          FROM invoiceheader inv, salesorderheader so, systemuser su
          WHERE inv.SalesOrderID=so.SalesOrderID
                AND so.Marketing=su.UserID
                AND inv.CustID='".$_POST["custid"]."'
                AND inv.InvoiceStatus = 0";
$hasil = mysqli_query($conn,$kueri);
while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>