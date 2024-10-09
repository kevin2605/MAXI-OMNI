<?php

include "../DBConnection.php";

$rows = array();
$kueri = "SELECT p.ProductionOrderID, p.MachineCD, m.MachineName, p.MaterialCD, mt.MaterialName, p.ProductCD, pr.ProductName, p.EstimateOutcome, p.ExactOutcome, p.ProdLoss
          FROM productionorder p, machine m, material mt, product pr
          WHERE p.Status='0'
                AND p.MachineCD=m.MachineCD
                AND p.MaterialCD=mt.MaterialCD
                AND p.ProductCD=pr.ProductCD
                AND p.ProductionOrderID='".$_POST["spk"]."'";
$hasil = mysqli_query($conn,$kueri);
while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>