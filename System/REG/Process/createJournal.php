<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Generate SO ID
$query = "SELECT PurchaseOrderID FROM purchaseorderheader WHERE substr(CreatedOn,6,2)='" . date("m") . "' ORDER BY CreatedOn DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row["PurchaseOrderID"] != "") {
    $lastnumber = substr($row["PurchaseOrderID"], 8);
    $lastnumber = intval($lastnumber);
    $lastnumber += 1;
    echo $lastnumber;
} else {
    $lastnumber = 1;
}

// New Purchase Order ID
$poid = "PO-" . date("ym") . "-" . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);

// Parameters
$deliverydate = $_POST["deliverydate"];
$creator = $_POST["creator"];
$supplier = $_POST["supplier"];
$termin = $_POST["termin"];
$shipadd = $_POST["shipadd"];
$desc = $_POST["desc"];
$datetime = date('Y-m-d H:i:s');

// Create Purchase Order Header
$queryh = "INSERT INTO `purchaseorderheader`(`PurchaseOrderID`, `CreatedOn`, `CreatedBy`, `DeliveryDate`, `ShippingAddress`, `SupplierNum`, `Termin`, `Description`,
         `TotalPurchase`, `TotalPaid`, `ApprovalStatus`, `ApprovalBy`, `ApprovalOn`, `LastEdit`, `Finish`) VALUES 
         ('$poid','$datetime','$creator','$deliverydate','$shipadd','$supplier','$termin','$desc','0','0','Pending',NULL,NULL,'$datetime','0')";
$resulth = mysqli_query($conn, $queryh);

// Total purchasing
$totalInvoice = 0;
$resultd = true;

if ($resulth == 1) {
    $arrMaterial = $_POST["materials"];
    $arrQty = $_POST["quantities"];
    $arrUnit = $_POST["units"];
    $arrPrice = $_POST["prices"];
    $arrSubt = $_POST["subtotals"];

    for ($i = 0; $i < count($arrMaterial); $i++) {
        $matcd = $arrMaterial[$i];
        $qty = $arrQty[$i];
        $unit = $arrUnit[$i];
        $price = $arrPrice[$i];
        $subtotal = $qty * $price;

        $queryd = "INSERT INTO `purchaseorderdetail`(`PurchaseOrderID`, `CreatedOn`, `MaterialCD`, `Quantity`, `UnitCD`, `Price`, `Subtotal`, `QuantityReceived`) 
                    VALUES ('$poid','$datetime','$matcd','$qty','$unit','$price','$subtotal','0')";
        $resultd = $resultd && mysqli_query($conn, $queryd);

        $totalInvoice += $subtotal;
    }
}

$queryu = "UPDATE purchaseorderheader SET TotalPurchase='" . $totalInvoice . "' WHERE PurchaseOrderID='" . $poid . "'";
$resultu = mysqli_query($conn, $queryu);

if ($resulth == 1 && $resultd == 1) {
    logAction($conn, $creator, 'Create', 'membuat PO', 0, $poid);
    header("Location:../Local-Purchasing/purchase-order-list.php?status=success-po");
} else {
    logAction($conn, $creator, 'Create', 'Add Purchase Order Failed', 1, $poid);
    header("Location:../Local-Purchasing/purchase-order-list.php?status=error-po");
}

function logAction($conn, $userID, $actionDone, $actionMSG, $actionStatus, $recordID)
{
    $timestamp = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO systemlog (Timestamp, UserID, ActionDone, ActionMSG, ActionStatus, RecordID) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssss", $timestamp, $userID, $actionDone, $actionMSG, $actionStatus, $recordID);
        if (!$stmt->execute()) {
            error_log("Log action failed: " . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
}

?>