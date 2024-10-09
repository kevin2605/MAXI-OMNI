<?php

include "../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$matcd = $_GET["matcd"];

$query = "DELETE FROM material WHERE MaterialCD='$matcd'";
$result = mysqli_query($conn,$query);

if($result == 1){
    header("Location:../Material/material.php?status=success-delete");
}else{
    header("Location:../Material/material.php?status=error-delete");
}

?>