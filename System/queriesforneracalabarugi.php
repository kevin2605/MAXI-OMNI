<?php

include "DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$date = date('Y-m-d');

$query = "SELECT SUM(StockQty*AvgPrice) AS SupplyAkhirBahan FROM material";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$pers_akhir_bahan = $row["SupplyAkhirBahan"];
echo "Persediaan Akhir Bahan : Rp ". number_format($pers_akhir_bahan, 2, '.', ',') . "<br>";

$query = "SELECT SUM((p.MaterialOut*m.AvgPrice)*(1-((p.ExactOutcome+p.ProdLoss)/p.EstimateOutcome))) AS SupplyAkhirBrgProses
FROM productionorder p, material m
WHERE p.MaterialCD = m.MaterialCD";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$pers_akhir_brg_dlm_proses = $row["SupplyAkhirBrgProses"];
echo "Persediaan Akhir Brg Dlm Proses : Rp ". number_format($pers_akhir_brg_dlm_proses, 2, '.', ',') . "<br>";

$query = "SELECT SUM(StockQty*ModalPrice) AS SupplyAkhirBarangJadi FROM product";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$pers_akhir_brg_jadi = $row["SupplyAkhirBarangJadi"];
echo "Persediaan Akhir Brg Jadi : Rp ". number_format($pers_akhir_brg_jadi, 2, '.', ',') . "<br>";

$query = "INSERT INTO `datapersediaan`(`Tanggal`, `Pers_Akhir_Bahan`, `Pers_Akhir_Brg_Dlm_Proses`, `Pers_Akhir_Brg_Jadi`)
          VALUES ('$date','$pers_akhir_bahan','$pers_akhir_brg_dlm_proses','$pers_akhir_brg_jadi')";
$result = mysqli_query($conn, $query);

?>