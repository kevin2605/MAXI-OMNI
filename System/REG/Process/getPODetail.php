<?php

include "../../DBConnection.php";

$rows = array();

if($_POST["category"] == "BB"){
    $kueri = "SELECT pod.ItemCD, m.MaterialName, pod.Quantity, pod.UnitCD, m.UnitCD_2, pod.QuantityReceived
    FROM purchaseorderdetail AS pod, material AS m 
    WHERE pod.ItemCD = m.MaterialCD AND pod.PurchaseOrderID='".$_POST["id"]."' AND pod.Quantity > pod.QuantityReceived";
    $hasil = mysqli_query($conn,$kueri);
    while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
    }
}else if($_POST["category"] == "BPP"){
    $kueri = "SELECT pod.ItemCD, s.GoodsName, pod.Quantity, pod.UnitCD, pod.QuantityReceived
    FROM purchaseorderdetail pod, supportinggoods s
    WHERE pod.ItemCD = s.GoodsCD AND pod.PurchaseOrderID='".$_POST["id"]."' AND pod.Quantity > pod.QuantityReceived";
    $hasil = mysqli_query($conn,$kueri);
    while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
    }
}else if($_POST["category"] == "SPR"){
    $kueri = "SELECT pod.ItemCD, s.PartName, pod.Quantity, pod.UnitCD, pod.QuantityReceived
    FROM purchaseorderdetail pod, sparepart s
    WHERE pod.ItemCD = s.PartCD AND pod.PurchaseOrderID='".$_POST["id"]."' AND pod.Quantity > pod.QuantityReceived";
    $hasil = mysqli_query($conn,$kueri);
    while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
    }
}


$result = json_encode($rows);

echo $result;
?>