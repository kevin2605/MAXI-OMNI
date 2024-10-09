<?php

include "../DBConnection.php";

$rows = array();
$kueri = "SELECT inv.RCV_InvoiceID, po.PurchaseOrderID, inv.CreatedOn, inv.TotalAmount, inv.TaxInvoiceNumber, inv.TaxInvoiceDate
          FROM receptioninvoiceheader inv, receptionheader rh, purchaseorderheader po
          WHERE inv.ReceptionID=rh.ReceptionID
                AND rh.PurchaseOrderID=po.PurchaseOrderID
                AND po.SupplierNum='".$_POST["suppnum"]."'
                AND inv.Status=0";
$hasil = mysqli_query($conn,$kueri);
while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>