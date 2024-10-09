<?php

include "../DBConnection.php";

$custid = $_GET["id"];

$query = "SELECT PriceListCD FROM customer WHERE CustID='".$custid."'";
$result=mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);

$queryp = "SELECT PriceListName FROM pricelistheader WHERE PriceListCD='".$row["PriceListCD"]."'";
$resultp=mysqli_query($conn,$queryp);
$rowp=mysqli_fetch_assoc($resultp);

echo '<label class="form-control" style="border-style:none;">'.$rowp["PriceListName"].'</label>';
echo '<input type="hidden" id="pricelistcd" name="pricelistcd" value="'.$row["PriceListCD"].'">';
?>