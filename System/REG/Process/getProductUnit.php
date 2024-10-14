<?php

include "../../DBConnection.php";

$rows = array();
$kueri = "SELECT UnitCD FROM product WHERE ProductCD='" . $_POST["prodcd"] . "'";
$hasil = mysqli_query($conn, $kueri);
while ($row = mysqli_fetch_array($hasil)) {
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>