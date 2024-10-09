<?php
include "../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$query = "SELECT i.InvoiceID, i.CreatedOn, c.CustName, c.NPWPNum, i.TaxInvoiceNumber, i.TaxInvoiceDate, i.TotalInvoice
            FROM invoiceheader i, customer c 
            WHERE i.CustID=c.CustID";

if($_POST["customer"] != ''){
    $customers = explode(" - ",$_POST["customer"]);
    $query .= " AND i.CustID ='".$customers[0]."'";
}
if($_POST["startdate"] != ''){
    $query .= " AND substr(i.CreatedOn,1,10) >='".$_POST["startdate"]."'";
}
if($_POST["enddate"] != ''){
    $query .= " AND substr(i.CreatedOn,1,10) <='".$_POST["enddate"]."'";
}
if($_POST["startdatefaktur"] != ''){
    $query .= " AND substr(i.TaxInvoiceDate,1,10) >='".$_POST["startdatefaktur"]."'";
}
if($_POST["enddatefaktur"] != ''){
    $query .= " AND substr(i.TaxInvoiceDate,1,10) <='".$_POST["enddatefaktur"]."'";
}

$rows = array();

$hasil = mysqli_query($conn,$query);
while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
}
$result = json_encode($rows);//encode result array with json

echo $result;//pass back to main page

?>