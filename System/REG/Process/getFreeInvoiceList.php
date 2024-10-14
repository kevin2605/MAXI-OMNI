<?php

include "../../DBConnection.php";

$rows = array();
$kueri = "SELECT i.InvoiceID, i.CreatedOn, c.CustName, i.TotalInvoice
          FROM invoiceheader i, customer c
          WHERE i.CustID = c.CustID 
                AND i.TaxInvoiceNumber IS NULL 
                AND i.TaxInvoiceDate IS NULL
                AND substr(i.CreatedOn,1,10) >='" . $_POST["sd"] . "' AND substr(i.CreatedOn,1,10) <='" . $_POST["ed"] . "'";

$hasil = mysqli_query($conn, $kueri);
while ($row = mysqli_fetch_array($hasil)) {
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>