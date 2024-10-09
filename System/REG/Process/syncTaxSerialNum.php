<?php

include "../DBConnection.php";

//tax serial id dan end number
$taxserialid = $_POST["taxserialid"];
$endnumber = $_POST["endnumber"];

//invoice dan no faktur
$arrInv = $_POST["invoiceids"];
$arrNoFaktur = $_POST["noFaktur"];
$arrDateFaktur = $_POST["dateFaktur"];

$lastnumflag = 0;

for ($i = 0; $i < count($arrInv); $i++) {
    //update invoiceheader taxnumber and taxdate
    $kueri = "UPDATE invoiceheader
              SET TaxInvoiceNumber='".$arrNoFaktur[$i]."', TaxInvoiceDate='".$arrDateFaktur[$i]."'
              WHERE InvoiceID='".$arrInv[$i]."'";
    $hasil = mysqli_query($conn,$kueri);

    //last num flag
    $lastnumflag = substr($arrNoFaktur[$i],11);
}

//update last flag number and used number
$usednumber = $endnumber - $lastnumflag;
$kueriu = "UPDATE taxserialnumber
          SET LastNumberFlag='".$lastnumflag."', UsedNumber='".$usednumber."'
          WHERE SerialID='".$taxserialid."'";
$hasilu = mysqli_query($conn,$kueriu);

if($hasil == 1 && $hasilu == 1){
    header("Location:../Pajak/sync-tax-number.php?status=success");
}

?>