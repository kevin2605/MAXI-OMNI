<?php
include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Generate Mutation ID
$query = "SELECT MutationID FROM mutationheader WHERE substr(CreatedOn, 6, 2)='" . date("m") . "' ORDER BY MutationID DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$lastnumber = $row["MutationID"] != "" ? intval(substr($row["MutationID"], 9)) + 1 : 1;

$mutid = "MUT-" . date("ym") . "-" . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}
$datetime = date('Y-m-d H:i:s');
$desc = $_POST["desc"];

$queryh = "INSERT INTO `mutationheader` (`MutationID`, `CreatedOn`, `CreatedBy`, `Description`,`CategoryCD`) 
           VALUES ('$mutid', '$datetime', '$creator', '$desc','BTM')";
$resulth = mysqli_query($conn, $queryh);

if ($resulth) {
    $arrProd = $_POST["products"];
    $arrFlowin = $_POST["flowin"];
    $arrFlowout = $_POST["flowout"];
    $arrUnit = $_POST["units"];
    $arrDesc = $_POST["descriptions"];

    $insertSuccess = true;

    for ($i = 0; $i < count($arrProd); $i++) {
        $prodName = $arrProd[$i];
        $flowin = !empty($arrFlowin[$i]) ? intval($arrFlowin[$i]) : 0;
        $flowout = !empty($arrFlowout[$i]) ? intval($arrFlowout[$i]) : 0;
        $unit = $arrUnit[$i];
        $description = $arrDesc[$i];

        $queryMaterialCD = "SELECT MaterialCD FROM material WHERE MaterialName = '$prodName'";
        $resultMaterialCD = mysqli_query($conn, $queryMaterialCD);
        if ($resultMaterialCD && mysqli_num_rows($resultMaterialCD) > 0) {
            $rowMaterialCD = mysqli_fetch_assoc($resultMaterialCD);
            $materialCD = $rowMaterialCD['MaterialCD'];

            $queryUpdateStock = "UPDATE material SET StockQty = StockQty + $flowin - $flowout WHERE MaterialCD = '$materialCD'";
            if (!mysqli_query($conn, $queryUpdateStock)) {
                echo "Error updating stock: " . mysqli_error($conn);
                $insertSuccess = false;
            }

            $queryd = "INSERT INTO `mutationdetailbtm` (`MutationID`, `CreatedOn`, `FlowIn`, `FlowOut`, `UnitCD_1`, `Description`, `MaterialCD`) 
               VALUES ('$mutid', '$datetime', '$flowin', '$flowout', '$unit', '$description', '$materialCD')";
            if (!mysqli_query($conn, $queryd)) {
                echo "Error inserting into mutationdetailbtm: " . mysqli_error($conn);
                $insertSuccess = false;
            }

            // Insert into otherflowhistory
            $querymaterialFlow = "INSERT INTO `materialflowhistory` (`Date`, `ReferenceKey`, `MaterialCD`, `FlowIn`, `FlowOut`, `Description`) 
                               VALUES ('$datetime', '$mutid', '$materialCD', '$flowin', '$flowout', '$description')";
            if (!mysqli_query($conn, $querymaterialFlow)) {
                echo "Error inserting into materialflowhistory: " . mysqli_error($conn);
                $insertSuccess = false;
            }
        } else {
            logAction($conn, $creator, 'Create', 'Material tidak ditemukan: ' . $prodName, 1, $mutid);
            echo "Error: MaterialCD not found for MaterialName = '$prodName'";
        }
    }

    if ($insertSuccess) {
        logAction($conn, $creator, 'Create', 'menambahkan mutasi produk', 0, $mutid);
        header("Location:../Mutation/product-mutation.php?status=new-success");
    } else {
        logAction($conn, $creator, 'Create', 'gagal menambahkan mutasi produk', 1, $mutid);
        header("Location:../Mutation/product-mutation.php?status=error");
    }
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan header mutasi produk', 1, $mutid);
    header("Location:../Mutation/product-mutation.php?status=error");
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