<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Function to log actions
function logAction($conn, $userID, $actionDone, $actionMSG, $actionStatus, $recordID)
{
    $timestamp = date('Y-m-d H:i:s');
    $queryLog = "INSERT INTO systemlog (Timestamp, UserID, ActionDone, ActionMSG, ActionStatus, RecordID)
                 VALUES ('$timestamp', '$userID', '$actionDone', '$actionMSG', '$actionStatus', '$recordID')";
    mysqli_query($conn, $queryLog);
}

// Generate RCV
$query = "SELECT ReceptionID FROM receptionheader WHERE substr(CreatedOn,6,2)='" . date("m") . "' ORDER BY CreatedOn DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row["ReceptionID"] != "") {
    $lastnumber = substr($row["ReceptionID"], 9);
    $lastnumber = intval($lastnumber);
    $lastnumber += 1;
} else {
    $lastnumber = 1;
}

// New Reception ID
$recid = "RCV-" . date("ym") . "-" . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);

// Parameters
$posupp = explode(" | ", $_POST["poid"]);
$purchaseorder = $posupp[0];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}
$termin = $_POST["termin"];
$gudang = $_POST["gudang"];
$desc = $_POST["desc"];
$category = $_POST["category"];
$noSuratJalan = $_POST["noSuratJalan"];
$noInvoice = $_POST["noInvoice"];
$datetime = date('Y-m-d H:i:s');

// Insert into receptionheader
$queryH = "INSERT INTO `receptionheader`(`ReceptionID`, `CreatedOn`, `CreatedBy`, `PurchaseOrderID`, `CategoryCD`, `WarehCD`, `Termin`, `Description`, `SuratJalan`, `Invoice`,`LastEdit`)
          VALUES ('$recid','$datetime','$creator','$purchaseorder','$category','$gudang','$termin','$desc','$noSuratJalan','$noInvoice','$datetime')";
$resultH = mysqli_query($conn, $queryH);

if ($resultH) {
    // Log action for reception creation
    logAction($conn, $creator, "Create", "membuat penerimaan barang lokal.", "Success", $recid);

    if ($category == "BB") {
        // Process for BB category
        $arrItem = $_POST["items"];
        $arrQ1 = $_POST["qty1"];
        $arrU1 = $_POST["unit1"];
        $arrQ2 = $_POST["qty2"];
        $arrU2 = $_POST["unit2"];

        for ($i = 0; $i < count($arrItem); $i++) {
            $materials = $arrItem[$i];
            $materials = explode(" - ", $materials);
            $material = $materials[0];
            $qty1 = $arrQ1[$i];
            $unit1 = $arrU1[$i];
            $qty2 = $arrQ2[$i];
            $unit2 = $arrU2[$i];

            // Calculate average price and update material price
            $queryA = "SELECT Price FROM purchaseorderdetail WHERE PurchaseOrderID='$purchaseorder' AND ItemCD='$material'";
            $resultA = mysqli_query($conn, $queryA);
            $rowA = mysqli_fetch_assoc($resultA);
            $currBuyPrice = $rowA["Price"];

            $queryB = "SELECT StockQty, AvgPrice FROM material WHERE MaterialCD='$material'";
            $resultB = mysqli_query($conn, $queryB);
            $rowB = mysqli_fetch_assoc($resultB);
            $currAvgBuyPrice = ($currBuyPrice * $qty1) / $qty2;
            $newAvgPrice = (($currAvgBuyPrice * $qty2) + ($rowB["AvgPrice"] * $rowB["StockQty"])) / ($qty2 + $rowB["StockQty"]);

            $queryC = "UPDATE material SET AvgPrice='$newAvgPrice' WHERE MaterialCD='$material'";
            $resultC = mysqli_query($conn, $queryC);

            $queryE = "INSERT INTO materialavgpricehistory(Date, MaterialCD, LastPrice) VALUES ('$datetime','$material','$newAvgPrice')";
            $resultE = mysqli_query($conn, $queryE);

            $queryD = "INSERT INTO `receptiondetail`(`ReceptionID`, `CreatedOn`, `ItemCD`, `Quantity_1`, `UnitCD_1`, `Quantity_2`, `UnitCD_2`)
                      VALUES ('$recid','$datetime','$material','$qty1','$unit1','$qty2','$unit2')";
            $resultD = mysqli_query($conn, $queryD);

            $queryD = "UPDATE material SET StockQty = StockQty + $qty2 WHERE MaterialCD = '$material'";
            $resultD = mysqli_query($conn, $queryD);

            $queryD = "INSERT INTO materialflowhistory(Date, ReferenceKey, MaterialCD, FlowIn, Description)
                      VALUES ('$datetime','$recid','$material','$qty2', 'Penerimaan Barang')";
            $resultD = mysqli_query($conn, $queryD);

            $queryx = "UPDATE purchaseorderdetail SET QuantityReceived=QuantityReceived+$qty1 WHERE PurchaseOrderID='$purchaseorder' AND ItemCD='$material'";
            $resultx = mysqli_query($conn, $queryx);

            logAction($conn, $creator, "Update Material", "Material $material updated with reception $recid.", "Success", $material);
        }

        $completeorder = 1;
        $queryz = "SELECT Quantity, QuantityReceived FROM purchaseorderdetail WHERE PurchaseOrderID='$purchaseorder'";
        $resultz = mysqli_query($conn, $queryz);
        while ($rowz = mysqli_fetch_array($resultz)) {
            if ($rowz["Quantity"] != $rowz["QuantityReceived"]) {
                $completeorder = 0;
                break;
            }
        }

        if ($completeorder == 1) {
            $queryt = "UPDATE purchaseorderheader SET Finish=1 WHERE PurchaseOrderID='$purchaseorder'";
            $resultt = mysqli_query($conn, $queryt);
            // Log action for completing purchase order
            logAction($conn, $creator, "Complete PO", "Purchase order $purchaseorder completed.", "Success", $purchaseorder);
        }
    } else if ($category == "BPP") {
        // Process for BPP
        $arrItem = $_POST["items"];
        $arrQ1 = $_POST["qty1"];
        $arrU1 = $_POST["unit1"];

        for ($i = 0; $i < count($arrItem); $i++) {
            $items = $arrItem[$i];
            $items = explode(" - ", $items);
            $item = $items[0];
            $qty1 = $arrQ1[$i];
            $unit1 = $arrU1[$i];

            $queryD = "INSERT INTO `receptiondetail`(`ReceptionID`, `CreatedOn`, `ItemCD`, `Quantity_1`, `UnitCD_1`, `Quantity_2`, `UnitCD_2`)
                      VALUES ('$recid','$datetime','$item','$qty1','$unit1',NULL,NULL)";
            $resultD = mysqli_query($conn, $queryD);

            $tableName = ($category == "BPP") ? "supportinggoods" : "sparepart";
            $queryD = "UPDATE $tableName SET StockQty = StockQty + $qty1 WHERE GoodsCD = '$item'";
            $resultD = mysqli_query($conn, $queryD);

            $queryD = "INSERT INTO otherflowhistory(Date, ReferenceKey, ItemCD, FlowIn, Description)
                      VALUES ('$datetime','$recid','$item','$qty1', 'Penerimaan Barang')";
            $resultD = mysqli_query($conn, $queryD);

            $queryx = "UPDATE purchaseorderdetail SET QuantityReceived=QuantityReceived+$qty1 WHERE PurchaseOrderID='$purchaseorder' AND ItemCD='$item'";
            $resultx = mysqli_query($conn, $queryx);

            // Log action for item update
            logAction($conn, $creator, "Update Item", "Item $item updated with reception $recid.", "Success", $item);
        }

        // Check completion of purchase order
        $completeorder = 1;
        $queryz = "SELECT Quantity, QuantityReceived FROM purchaseorderdetail WHERE PurchaseOrderID='$purchaseorder'";
        $resultz = mysqli_query($conn, $queryz);
        while ($rowz = mysqli_fetch_array($resultz)) {
            if ($rowz["Quantity"] != $rowz["QuantityReceived"]) {
                $completeorder = 0;
                break;
            }
        }

        // Update purchase order to done if complete
        if ($completeorder == 1) {
            $queryt = "UPDATE purchaseorderheader SET Finish=1 WHERE PurchaseOrderID='$purchaseorder'";
            $resultt = mysqli_query($conn, $queryt);
            // Log action for completing purchase order
            logAction($conn, $creator, "Complete PO", "Purchase order $purchaseorder completed.", "Success", $purchaseorder);
        }
    } else if ($category == "SPR") {
        // Process for SPR category
        $arrItem = $_POST["items"];
        $arrQ1 = $_POST["qty1"];
        $arrU1 = $_POST["unit1"];

        for ($i = 0; $i < count($arrItem); $i++) {
            $items = $arrItem[$i];
            $items = explode(" - ", $items);
            $item = $items[0];
            $qty1 = $arrQ1[$i];
            $unit1 = $arrU1[$i];

            $queryD = "INSERT INTO `receptiondetail`(`ReceptionID`, `CreatedOn`, `ItemCD`, `Quantity_1`, `UnitCD_1`, `Quantity_2`, `UnitCD_2`)
                      VALUES ('$recid','$datetime','$item','$qty1','$unit1',NULL,NULL)";
            $resultD = mysqli_query($conn, $queryD);

            $tableName = ($category == "BPP") ? "supportinggoods" : "sparepart";
            $queryD = "UPDATE $tableName SET StockQty = StockQty + $qty1 WHERE PartCD  = '$item'";
            $resultD = mysqli_query($conn, $queryD);

            $queryD = "INSERT INTO otherflowhistory(Date, ReferenceKey, ItemCD, FlowIn, Description)
                      VALUES ('$datetime','$recid','$item','$qty1', 'Penerimaan Barang')";
            $resultD = mysqli_query($conn, $queryD);

            $queryx = "UPDATE purchaseorderdetail SET QuantityReceived=QuantityReceived+$qty1 WHERE PurchaseOrderID='$purchaseorder' AND ItemCD='$item'";
            $resultx = mysqli_query($conn, $queryx);

            // Log action for item update
            logAction($conn, $creator, "Update Item", "Item $item updated with reception $recid.", "Success", $item);
        }

        // Check completion of purchase order
        $completeorder = 1;
        $queryz = "SELECT Quantity, QuantityReceived FROM purchaseorderdetail WHERE PurchaseOrderID='$purchaseorder'";
        $resultz = mysqli_query($conn, $queryz);
        while ($rowz = mysqli_fetch_array($resultz)) {
            if ($rowz["Quantity"] != $rowz["QuantityReceived"]) {
                $completeorder = 0;
                break;
            }
        }

        // Update purchase order to done if complete
        if ($completeorder == 1) {
            $queryt = "UPDATE purchaseorderheader SET Finish=1 WHERE PurchaseOrderID='$purchaseorder'";
            $resultt = mysqli_query($conn, $queryt);
            // Log action for completing purchase order
            logAction($conn, $creator, "Complete PO", "Purchase order $purchaseorder completed.", "Success", $purchaseorder);
        }
    }
} else {
    logAction($conn, $creator, "Create", "gagal membuat penerimaan barang.", "Failed", $recid);
}

$uploadDir = '../Local-Purchasing/documentimage/';

if (is_writable($uploadDir)) {
    echo 'Directory is writable.<br>';
} else {
    echo 'Directory is not writable.<br>';
}

function generateFormattedFileName($receptionID, $category, $sequence, $fileName)
{
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $formattedName = $receptionID . "-" . str_pad($sequence, 4, "0", STR_PAD_LEFT) . "_$category." . $fileExt;
    return $formattedName;
}

function uploadMultipleSingleFiles($fileInputNames, $uploadDir, $conn, $recid, $columnName)
{
    $fileNames = [];
    $sequence = 1;


    $querySelect = "SELECT ReceptionID FROM receptiondetail WHERE ReceptionID='$recid'";
    $result = mysqli_query($conn, $querySelect);
    $row = mysqli_fetch_assoc($result);
    $receptionID = $row['ReceptionID'];

    foreach ($fileInputNames as $fileInputName) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$fileInputName];
            $tmpName = $file['tmp_name'];
            $originalName = $file['name'];
            $category = ($fileInputName === 'dokSuratJalan') ? 'SJ' : 'Invoice';
            $uniqueName = generateFormattedFileName($receptionID, $category, $sequence, $originalName);
            $destination = $uploadDir . $uniqueName;

            if (move_uploaded_file($tmpName, $destination)) {
                $fileNames[] = $uniqueName;
                $sequence++;
            } else {
                echo "Failed to move uploaded file: $originalName<br>";
            }
        }
    }

    if (!empty($fileNames)) {
        $querySelect = "SELECT `$columnName` FROM receptiondetail WHERE ReceptionID='$recid'";
        $result = mysqli_query($conn, $querySelect);
        $row = mysqli_fetch_assoc($result);
        $existingFiles = $row[$columnName];
        $newFileNames = $existingFiles ? $existingFiles . ',' . implode(',', $fileNames) : implode(',', $fileNames);
        $queryUpdate = "UPDATE receptiondetail SET `$columnName` = '$newFileNames' WHERE ReceptionID='$recid'";
        mysqli_query($conn, $queryUpdate);
    }
}
function generateFormattedFileNamee($receptionID, $suffix, $sequence, $originalName)
{
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);

    $originalName = preg_replace("/[^a-zA-Z0-9-_\.]/", "", pathinfo($originalName, PATHINFO_FILENAME));

    // Ubah format penamaan menjadi 0001-brg
    return "{$receptionID}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT) . "-brg.{$ext}";
}

function uploadMultipleFiles($fileInputName, $uploadDir, $conn, $recid, $columnName)
{
    if (isset($_FILES[$fileInputName])) {
        $errors = [];
        $files = $_FILES[$fileInputName];
        $sequence = 1;

        $querySelect = "SELECT ReceptionID FROM receptiondetail WHERE ReceptionID='$recid'";
        $result = mysqli_query($conn, $querySelect);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $receptionID = $row['ReceptionID'];

            if (is_array($files['name'])) {
                foreach ($files['name'] as $key => $name) {
                    if ($files['error'][$key] === UPLOAD_ERR_OK) {
                        $tmpName = $files['tmp_name'][$key];
                        $uniqueName = generateFormattedFileNamee($receptionID, 'brg', $sequence, $name); // Gunakan suffix 'brg'
                        $destination = $uploadDir . $uniqueName;

                        if (move_uploaded_file($tmpName, $destination)) {
                            // Update database with the new file name
                            $query = "UPDATE receptiondetail SET `$columnName` = CONCAT(IFNULL(`$columnName`, ''), '$uniqueName,') WHERE ReceptionID='$recid'";
                            if (!mysqli_query($conn, $query)) {
                                $errors[] = "Failed to update database for file: $name";
                            }
                            $sequence++;
                        } else {
                            $errors[] = "Failed to move uploaded file: $name";
                        }
                    } else {
                        $errors[] = "Error uploading file: $name";
                    }
                }
            }
        } else {
            $errors[] = "Invalid ReceptionID or database query failed.";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    }
}


uploadMultipleSingleFiles(['dokSuratJalan', 'dokInvoice'], $uploadDir, $conn, $recid, 'documentimage_1');
uploadMultipleFiles('dokbarang', $uploadDir, $conn, $recid, 'documentimage_2');

mysqli_close($conn);

header("Location:../Local-Purchasing/reception.php?status=success");
exit();
?>