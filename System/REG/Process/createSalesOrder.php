<?php

if (!isset($_POST["submitSO"])) {
    header("Location:../Sales/sales.php?status=no-entry");
    exit();
}

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Generate SO ID
$query = "SELECT SalesOrderID FROM salesorderheader WHERE substr(CreatedOn, 6, 2) = '" . date("m") . "' ORDER BY CreatedOn DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row && !empty($row["SalesOrderID"])) {
    $lastnumber = substr($row["SalesOrderID"], 8);
    $lastnumber = intval($lastnumber);
    $lastnumber += 1;
} else {
    $lastnumber = 1;
}

// New Sales Order ID
$soid = "SO-" . date("ym") . "-" . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);

// Parameters
$creator = $_COOKIE["UserID"] ?? 'unknown'; // Using cookie for creator
$marketing = $_POST["marketing"];
$customer = $_POST["customer"];
$pricelistcd = $_POST["pricelistcd"];
$logo = $_POST["logo"];
$desc = $_POST["desc"];
$datetime = date('Y-m-d H:i:s');

// Approval checking
$approval = 0;
$temparr = $_POST["discounts"];
foreach ($temparr as $disc) {
    if ($disc > 0) {
        $approval = 1;
        break;
    }
}
$approvalstatus = ($approval == 0) ? "Approved" : "Pending";


$queryh = "INSERT INTO salesorderheader (SalesOrderID, CreatedBy, CreatedOn, Marketing, CustID, Logo, Description, Approval, ApprovalStatus, ApprovalBy, ApprovalOn, LastEdit, Finish) 
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, ?, '0')";

if ($stmt = $conn->prepare($queryh)) {
    $stmt->bind_param("ssssssssss", $soid, $creator, $datetime, $marketing, $customer, $logo, $desc, $approval, $approvalstatus, $datetime);

    if ($stmt->execute()) {

        $arrProd = $_POST["products"];
        $arrPrice = $_POST["prices"];
        $arrQty = $_POST["quantities"];
        $arrDisc = $_POST["discounts"];
        $allDetailsInserted = true;

        $queryd = "INSERT INTO salesorderdetail (SalesOrderID, CreatedOn, ProductCD, Quantity, Price, Discount) 
                   VALUES (?, ?, ?, ?, ?, ?)";

        if ($detailStmt = $conn->prepare($queryd)) {
            foreach ($arrProd as $i => $prodcd) {
                $price = $arrPrice[$i];
                $qty = $arrQty[$i];
                $disc = $arrDisc[$i];

                $detailStmt->bind_param("ssssss", $soid, $datetime, $prodcd, $qty, $price, $disc);

                if (!$detailStmt->execute()) {
                    $allDetailsInserted = false;
                    break;
                }
            }
            $detailStmt->close();
        } else {
            $allDetailsInserted = false;
        }

        if ($allDetailsInserted) {
            logAction($conn, $creator, 'Create', 'menambahkan sales order', 0, $soid);
            header("Location:../Sales/salesorder.php?status=success-so");
        } else {
            logAction($conn, $creator, 'Create', 'gagal menambahkan sales order', 1, $soid);
            header("Location:../Sales/salesorder.php?status=error-so");
        }
    } else {

        logAction($conn, $creator, 'Create', 'gagal menambahkan sales order', 1, $soid);
        header("Location:../Sales/salesorder.php?status=error-so");
    }

    $stmt->close();
} else {

    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    logAction($conn, $creator, 'Create', 'gagal menambahkan sales order', 1, $soid);
    header("Location:../Sales/salesorder.php?status=error-so");
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