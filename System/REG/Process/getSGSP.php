<?php

include "../../DBConnection.php";

$rows = array();

//penunjang produksi
$kueri = "SELECT UnitCD,BuyPrice FROM supportinggoods WHERE GoodsCD='" . $_POST["cd"] . "'";
$hasil = mysqli_query($conn, $kueri);
while ($row = mysqli_fetch_array($hasil)) {
    $rows[] = $row;
}
//spare part
$kueri = "SELECT UnitCD,BuyPrice FROM sparepart WHERE PartCD='" . $_POST["cd"] . "'";
$hasil = mysqli_query($conn, $kueri);
while ($row = mysqli_fetch_array($hasil)) {
    $rows[] = $row;
}

$result = json_encode($rows);

echo $result;
?>