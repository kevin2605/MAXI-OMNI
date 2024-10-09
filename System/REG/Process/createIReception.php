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


$query = "SELECT ReceptionID FROM importreceptionheader WHERE substr(CreatedOn, 6, 2) = '" . date("m") . "' ORDER BY CreatedOn DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $lastReceptionID = $row["ReceptionID"];
    $lastnumber = substr($lastReceptionID, -4);
    $lastnumber = intval($lastnumber);
    $lastnumber += 1; // Increment
} else {
    $lastnumber = 1;
}

$recid = "RCVI-" . date("ym") . "-" . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);

echo "New ReceptionID: " . $recid;


// Parameters
$posupp = explode(" | ", $_POST["poid"]);
$purchaseorder = $posupp[0];
$creator = $_COOKIE["UserID"] ?? 'unknown'; // Using cookie for creator
$termin = $_POST["termin"];
$gudang = $_POST["gudang"];
$desc = $_POST["desc"];
$category = $_POST["category"];
$noInvoice = $_POST["noInvoice"];
$noPackingList = $_POST["noPackingList"];
$noBL = $_POST["noBL"];
$noInsurance = $_POST["noInsurance"];
$datetime = date('Y-m-d H:i:s');

// Insert into receptionheader
$queryH = "INSERT INTO `importreceptionheader`(`ReceptionID`, `CreatedOn`, `CreatedBy`, `PurchaseOrderID`, `CategoryCD`, `WarehCD`, `Termin`, `Description`,
           `Invoice`,`PackingList`,`BL`,`Insurance`,`LastEdit`) 
          VALUES ('$recid','$datetime','$creator','$purchaseorder','$category','$gudang','$termin','$desc','$noInvoice','$noPackingList','$noBL','$noInsurance','$datetime')";
$resultH = mysqli_query($conn, $queryH);

if ($resultH) {
    // Log action for reception creation
    logAction($conn, $creator, "Create", "membuat penerimaan barang dengan nomor.", "Success", $recid);

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
            $queryA = "SELECT Price FROM importpurchaseorderdetail WHERE PurchaseOrderID='$purchaseorder' AND ItemCD='$material'";
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

            $queryE = "INSERT INTO `materialavgpricehistory`(`Date`, `MaterialCD`, `LastPrice`) VALUES ('$datetime','$material','$newAvgPrice')";
            $resultE = mysqli_query($conn, $queryE);

            $queryD = "INSERT INTO `importreceptiondetail`(`ReceptionID`, `CreatedOn`, `ItemCD`, `Quantity_1`, `UnitCD_1`, `Quantity_2`, `UnitCD_2`) 
                    VALUES ('$recid','$datetime','$material','$qty1','$unit1','$qty2','$unit2')";
            $resultD = mysqli_query($conn, $queryD);

            $queryD = "UPDATE material SET StockQty = StockQty + $qty2 WHERE MaterialCD = '$material'";
            $resultD = mysqli_query($conn, $queryD);

            $queryD = "INSERT INTO materialflowhistory(Date, ReferenceKey, MaterialCD, FlowIn, Description) 
                       VALUES ('$datetime','$recid','$material','$qty2', 'Penerimaan Barang')";
            $resultD = mysqli_query($conn, $queryD);

            $queryx = "UPDATE importpurchaseorderdetail SET QuantityReceived=QuantityReceived+$qty1 WHERE PurchaseOrderID='$purchaseorder' AND ItemCD='$material'";
            $resultx = mysqli_query($conn, $queryx);

            // Log action for material update
            logAction($conn, $creator, "Update Material", "Material $material updated with reception $recid.", "Success", $material);
        }

        // Check completion of purchase order
        $completeorder = 1;
        $queryz = "SELECT Quantity, QuantityReceived FROM importpurchaseorderdetail WHERE PurchaseOrderID='$purchaseorder'";
        $resultz = mysqli_query($conn, $queryz);
        while ($rowz = mysqli_fetch_array($resultz)) {
            if ($rowz["Quantity"] != $rowz["QuantityReceived"]) {
                $completeorder = 0;
                break;
            }
        }

        // Update purchase order to done if complete
        if ($completeorder == 1) {
            $queryt = "UPDATE importpurchaseorderheader SET Finish=1 WHERE PurchaseOrderID='$purchaseorder'";
            $resultt = mysqli_query($conn, $queryt);
            // Log action for completing purchase order
            logAction($conn, $creator, "Complete PO", "Purchase order $purchaseorder completed.", "Success", $purchaseorder);
        }
    } else if ($category == "BPP" || $category == "SPR") {
        // Process for BPP and SPR categories
        $arrItem = $_POST["items"];
        $arrQ1 = $_POST["qty1"];
        $arrU1 = $_POST["unit1"];

        for ($i = 0; $i < count($arrItem); $i++) {
            $items = $arrItem[$i];
            $items = explode(" - ", $items);
            $item = $items[0];
            $qty1 = $arrQ1[$i];
            $unit1 = $arrU1[$i];

            $queryD = "INSERT INTO `importreceptiondetail`(`ReceptionID`, `CreatedOn`, `ItemCD`, `Quantity_1`, `UnitCD_1`, `Quantity_2`, `UnitCD_2`) 
                    VALUES ('$recid','$datetime','$item','$qty1','$unit1',NULL,NULL)";
            $resultD = mysqli_query($conn, $queryD);

            $tableName = ($category == "BPP") ? "supportinggoods" : "sparepart";
            $queryD = "UPDATE $tableName SET StockQty = StockQty + $qty1 WHERE GoodsCD = '$item'";
            $resultD = mysqli_query($conn, $queryD);

            $queryD = "INSERT INTO otherflowhistory(Date, ReferenceKey, ItemCD, FlowIn, Description) 
                       VALUES ('$datetime','$recid','$item','$qty1', 'Penerimaan Barang')";
            $resultD = mysqli_query($conn, $queryD);

            $queryx = "UPDATE importpurchaseorderdetail SET QuantityReceived=QuantityReceived+$qty1 WHERE PurchaseOrderID='$purchaseorder' AND ItemCD='$item'";
            $resultx = mysqli_query($conn, $queryx);

            // Log action for item update
            logAction($conn, $creator, "Update", "berhasil update dengan nomor.", "Success", $item);
        }

        // Check completion of purchase order
        $completeorder = 1;
        $queryz = "SELECT Quantity, QuantityReceived FROM importpurchaseorderdetail WHERE PurchaseOrderID='$purchaseorder'";
        $resultz = mysqli_query($conn, $queryz);
        while ($rowz = mysqli_fetch_array($resultz)) {
            if ($rowz["Quantity"] != $rowz["QuantityReceived"]) {
                $completeorder = 0;
                break;
            }
        }
        if ($completeorder == 1) {
            $queryt = "UPDATE importpurchaseorderheader SET Finish=1 WHERE PurchaseOrderID='$purchaseorder'";
            $resultt = mysqli_query($conn, $queryt);
            logAction($conn, $creator, "Complete PO", "Purchase order $purchaseorder completed.", "Success", $purchaseorder);
        }
    }
    $uploadDir = realpath('../Import-Purchasing/documentimageI/') . '/';
    echo $uploadDir;
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
        $querySelect = "SELECT ReceptionID FROM importreceptiondetail WHERE ReceptionID='$recid'";
        $result = mysqli_query($conn, $querySelect);
        $row = mysqli_fetch_assoc($result);
        $receptionID = $row['ReceptionID'];
        foreach ($fileInputNames as $fileInputName) {
            if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES[$fileInputName];
                $tmpName = $file['tmp_name'];
                $originalName = $file['name'];
                switch ($fileInputName) {
                    case 'dokInvoice':
                        $category = 'Invoice';
                        break;
                    case 'dokPackingList':
                        $category = 'PackingList';
                        break;
                    case 'dokBL':
                        $category = 'BL';
                        break;
                    case 'dokInsurance':
                        $category = 'Insurance';
                        break;
                    default:
                        $category = 'Unknown';
                }
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
            $querySelect = "SELECT `$columnName` FROM importreceptiondetail WHERE ReceptionID='$recid'";
            $result = mysqli_query($conn, $querySelect);
            $row = mysqli_fetch_assoc($result);
            $existingFiles = $row[$columnName];
            $newFileNames = $existingFiles ? $existingFiles . ',' . implode(',', $fileNames) : implode(',', $fileNames);
            $queryUpdate = "UPDATE importreceptiondetail SET `$columnName` = '$newFileNames' WHERE ReceptionID='$recid'";
            mysqli_query($conn, $queryUpdate);
        }
    }
    function generateUniqueFormattedFileName2($receptionID, $category, $uploadDir, $fileName)
    {
        do {
            $sequence = mt_rand(1000, 9999);
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $formattedName = $receptionID . "-" . str_pad($sequence, 4, '0', STR_PAD_LEFT) . "-brg." . $fileExt;
            $destination = $uploadDir . $formattedName;
        } while (file_exists($destination));

        return $formattedName;
    }
    function uploadMultipleFiles($fileInputName, $uploadDir, $conn, $recid, $columnName)
    {
        if (isset($_FILES[$fileInputName])) {
            $errors = [];
            $files = $_FILES[$fileInputName];
            $sequence = 1;
            $querySelect = "SELECT ReceptionID FROM importreceptiondetail WHERE ReceptionID='$recid'";
            $result = mysqli_query($conn, $querySelect);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $receptionID = $row['ReceptionID'];
                if (is_array($files['name'])) {
                    foreach ($files['name'] as $key => $name) {
                        if ($files['error'][$key] === UPLOAD_ERR_OK) {
                            $tmpName = $files['tmp_name'][$key];
                            $uniqueName = generateUniqueFormattedFileName2($receptionID, '2', $sequence, $name); // Gunakan suffix '2'
                            $destination = $uploadDir . $uniqueName;

                            if (move_uploaded_file($tmpName, $destination)) {
                                $query = "UPDATE importreceptiondetail SET `$columnName` = CONCAT(IFNULL(`$columnName`, ''), '$uniqueName,') WHERE ReceptionID='$recid'";
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
    uploadMultipleSingleFiles(['dokInvoice', 'dokPackingList', 'dokBL', 'dokInsurance'], $uploadDir, $conn, $recid, 'documentimageI_1');
    uploadMultipleFiles('dokbarangI', $uploadDir, $conn, $recid, 'documentimageI_2');
    // Redirect to reception page
    header("Location:../Import-Purchasing/reception.php?status=success");
    exit();
} else {
    // Log action for failed reception creation
    logAction($conn, $creator, "Create Reception", "Failed to create reception $recid.", "Fail", $recid);
    echo "Data Gagal Masuk";
    mysqli_close($conn);
    exit();
}


?>