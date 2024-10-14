<?php

include "../../DBConnection.php";

$rows = array();
$kueri = "SELECT AccountName
          FROM chartofaccount
          WHERE AccountCD='".$_POST["acctcd"]."'";
$hasil = mysqli_query($conn,$kueri);
while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
}
$result = json_encode($rows);

echo $result;
?>