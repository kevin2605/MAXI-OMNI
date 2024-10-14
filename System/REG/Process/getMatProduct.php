<?php

include "../../DBConnection.php";

$rows = array();
$kueri = "SELECT ProductCD FROM material WHERE MaterialCD='" . $_POST["matcd"] . "'";
$hasil = mysqli_query($conn, $kueri);
while ($row = mysqli_fetch_array($hasil)) {
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>