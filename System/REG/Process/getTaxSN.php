<?php

include "../DBConnection.php";

$rows = array();
$kueri = "SELECT *
          FROM taxserialnumber
          WHERE SerialID='".$_POST["sid"]."'";
$hasil = mysqli_query($conn,$kueri);
while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>