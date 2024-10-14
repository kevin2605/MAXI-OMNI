<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$restrictedIDs = ['MKT02', 'MKT01'];

if (in_array($creator, $restrictedIDs)) {
    header("Location: ../Import-Purchasing/reception.php?error=access_denied");
    exit();
}

// Generate PINV
$query = "SELECT RCV_InvoiceID FROM importreceptioninvoiceheader WHERE substr(CreatedOn,6,2)='" . date("m") . "' ORDER BY CreatedOn DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row["RCV_InvoiceID"] != "") {
    $lastnumber = substr($row["RCV_InvoiceID"], 11);
    $lastnumber = intval($lastnumber);
    $lastnumber += 1;
} else {
    $lastnumber = 1;
}

// New PINV
$pinv = "PINVI-" . date("ym") . "-" . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);

// Parameter
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}
$rcvId = $_POST["rcvId"];
$category = $_POST["category"];
$tdpp = $_POST["tdpp"];
$bm = $_POST["bm"];
$ppn = $_POST["ppn"];
$pph = $_POST["pph"];
$tImport = $tdpp + $bm + $ppn + $pph;
$total = $_POST["total"];
$datetime = date('Y-m-d H:i:s');
$date = date('Y-m-d');

$queryH = "INSERT INTO `importreceptioninvoiceheader`(`RCV_InvoiceID`, `ReceptionID`, `CategoryCD`, `CreatedOn`, `CreatedBy`, `DPP`, `BM`, `PPN`, `PPH`, `TotalAmount`, `Status`)
          VALUES ('$pinv','$rcvId','$category','$datetime','$creator','$tdpp','$bm','$ppn','$pph','$total','0')";
$resultH = mysqli_query($conn, $queryH);

//insert journal data
$queryjd = "INSERT INTO `journaldata`(`JournalDate`, `AccountCD`, `AccountName`, `Debit`, `Credit`, `Notes`) 
            VALUES ('$date','5-1100','Pembelian Bhn Baku','$tdpp','0','$pinv'),('$date','2-1100','Utang Usaha','$tImport','0','$pinv');";
$resultjd = mysqli_query($conn, $queryjd);

if ($resultH == 1) {
    $arrMat = $_POST["items"];
    $arrQ = $_POST["quantity"];
    $arrU = $_POST["unit"];
    $arrP = $_POST["price"];
    $arrdpp = $_POST["dpp"];
    $arrsub = $_POST["subtotal"];

    for ($i = 0; $i < count($arrMat); $i++) {
        $mat = $arrMat[$i];
        $qty = $arrQ[$i];
        $unit = $arrU[$i];

        $price = str_replace(',', '', $arrP[$i]);

        $dpp = str_replace(',', '', $arrdpp[$i]);

        $sub = str_replace(',', '', $arrsub[$i]);

        //insert reception detail
        $queryD = "INSERT INTO `importreceptioninvoicedetail`(`RCV_InvoiceID`, `CreatedOn`, `ItemCD`, `Quantity`, `UnitCD`, `Price`, `DPP`, `Subtotal`) 
                VALUES ('$pinv','$datetime','$mat','$qty','$unit','$price','$dpp','$sub')";
        $resultD = mysqli_query($conn, $queryD);

    }
}

if ($resultH == 1 && $resultD == 1) {
    $queryU = "UPDATE importreceptionheader SET Status='1' WHERE ReceptionID='$rcvId'";
    mysqli_query($conn, $queryU);
    logAction($conn, $creator, 'Create', 'menambahkan faktur penerimaan', 0, $pinv);
    header("Location:../Import-Purchasing/invoicing.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan faktur penerimaan', 1, $pinv);
    header("Location:../Import-Purchasing/invoicing.php?status=error");
}

function logAction($conn, $userID, $actionDone, $actionMSG, $actionStatus, $recordID)
{
    $timestamp = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO systemlog (Timestamp, UserID, ActionDone, ActionMSG, ActionStatus, RecordID) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssss", $timestamp, $userID, $actionDone, $actionMSG, $actionStatus, $recordID);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
}

?>