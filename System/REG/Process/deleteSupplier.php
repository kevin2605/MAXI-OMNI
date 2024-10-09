<?php

include "../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

if(isset($_GET["id"])){
    $query = "DELETE FROM supplier WHERE SupplierNum='".$_GET["id"]."'";
    $result = mysqli_query($conn,$query);
}

if($result == 1){
    header("Location:../Supplier/supplier.php?status=success-delete");
}else{
    header("Location:../Supplier/supplier.php?status=error");
}

?>