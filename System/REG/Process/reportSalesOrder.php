<?php
include "../../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$query = "SELECT sh.SalesOrderID, sh.CreatedOn, su.Name, c.CustName, p.ProductCD, p.ProductName, sd.Price, sd.Discount, sd.Quantity
            FROM salesorderheader sh, salesorderdetail sd, customer c, product p, systemuser su
            WHERE sh.SalesOrderID=sd.SalesOrderID
                    AND sh.CustID=c.CustID
                    AND sh.Marketing=su.UserID
                    AND sd.ProductCD=p.ProductCD";

if($_POST["customer"] != ''){
    $customers = explode(" - ",$_POST["customer"]);
    $query .= " AND sh.CustID ='".$customers[0]."'";
}
if($_POST["startdate"] != ''){
    $query .= " AND substr(sh.CreatedOn,1,10) >='".$_POST["startdate"]."'";
}
if($_POST["enddate"] != ''){
    $query .= " AND substr(sh.CreatedOn,1,10) <='".$_POST["enddate"]."'";
}
if($_POST["status"] != ''){
    if($_POST["status"] == "Pending"){
        $finish = 0;
    }else if($_POST["status"] == "Complete"){
        $finish = 1;
    }else if($_POST["status"] == "Close"){
        $finish = 2;
    }
    $query .= " AND Finish ='".$finish."'";
}

$rows = array();

$hasil = mysqli_query($conn,$query);
while($row = mysqli_fetch_array($hasil)){
    $rows[] = $row;
}
$result = json_encode($rows);//encode result array with json

echo $result;//pass back to main page

?>