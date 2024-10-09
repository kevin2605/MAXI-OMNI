<?php

function processInvoice($invid,$conn){
    //select SO ID for the particulan INV
    $queryk = "SELECT SalesOrderID FROM invoiceheader WHERE InvoiceID='".$invid."'";
    $resultk=mysqli_query($conn,$queryk);
    $rowk=mysqli_fetch_assoc($resultk);
    
    //loop through inv detail and update sales order quantity sent
    $queryz = "SELECT ProductCD, Quantity FROM invoicedetail WHERE InvoiceID='".$invid."'";
    $resultz = mysqli_query($conn,$queryz);
    while ($rowz = mysqli_fetch_array($resultz)) 
    {
        //update quantity sent
        $queryx = "UPDATE salesorderdetail SET QuantitySent=QuantitySent+".$rowz["Quantity"]." WHERE SalesOrderID='".$rowk["SalesOrderID"]."' AND ProductCD='".$rowz["ProductCD"]."'";
        $resultx = mysqli_query($conn,$queryx);
        
        //update stock prodcut
        $queryg = "UPDATE product SET StockQty=StockQty-".$rowz["Quantity"]." WHERE ProductCD='".$rowz["ProductCD"]."'";
        $resultg = mysqli_query($conn,$queryg);
    }
    
    
    //check all sales order detail
    $completeorder = 1;
    $queryz = "SELECT Quantity, QuantitySent FROM salesorderdetail WHERE SalesOrderID='".$rowk["SalesOrderID"]."'";
    $resultz = mysqli_query($conn,$queryz);
    while ($rowz = mysqli_fetch_array($resultz)) 
    {
        if($rowz["Quantity"] != $rowz["QuantitySent"]){
            $completeorder = 0;
        }
    }

    //if complete all so detail, update so to done
    if($completeorder == 1){
        $queryt = "UPDATE salesorderheader SET Finish=1 WHERE SalesOrderID='".$rowk["SalesOrderID"]."'";
        $resultt = mysqli_query($conn,$queryt);
    }
}

?>