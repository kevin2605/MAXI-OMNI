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
           VALUES ('$mutid', '$datetime', '$creator', '$desc','BPP')";
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

        $queryGoodsCD = "SELECT GoodsCD FROM supportinggoods WHERE GoodsName = '$prodName'";
        $resultGoodsCD = mysqli_query($conn, $queryGoodsCD);
        if ($resultGoodsCD && mysqli_num_rows($resultGoodsCD) > 0) {
            $rowGoodsCD = mysqli_fetch_assoc($resultGoodsCD);
            $GoodsCD = $rowGoodsCD['GoodsCD'];

            $queryUpdateStock = "UPDATE supportinggoods SET StockQty = StockQty + $flowin - $flowout WHERE GoodsCD = '$GoodsCD'";
            if (!mysqli_query($conn, $queryUpdateStock)) {
                echo "Error updating stock: " . mysqli_error($conn);
                $insertSuccess = false;
            }

            $queryd = "INSERT INTO `mutationdetailbpp` (`MutationID`, `CreatedOn`, `FlowIn`, `FlowOut`, `UnitCD_1`, `Description`, `GoodsCD`) 
               VALUES ('$mutid', '$datetime', '$flowin', '$flowout', '$unit', '$description', '$GoodsCD')";
            if (!mysqli_query($conn, $queryd)) {
                echo "Error inserting into mutationdetailbpp: " . mysqli_error($conn);
                $insertSuccess = false;
            }

            // Insert into otherflowhistory
            $queryOtherFlow = "INSERT INTO `otherflowhistory` (`Date`, `ReferenceKey`, `ItemCD`, `FlowIn`, `FlowOut`, `Description`) 
                               VALUES ('$datetime', '$mutid', '$GoodsCD', '$flowin', '$flowout', '$description')";
            if (!mysqli_query($conn, $queryOtherFlow)) {
                echo "Error inserting into otherflowhistory: " . mysqli_error($conn);
                $insertSuccess = false;
            }
        } else {
            logAction($conn, $creator, 'Create', 'Goods tidak ditemukan: ' . $prodName, 1, $mutid);
            echo "Error: GoodsCD not found for GoodsName = '$prodName'";
        }
    }

    if ($insertSuccess) {
        logAction($conn, $creator, 'Create', 'menambahkan mutasi penunjang produksi', 0, $mutid);
        header("Location:../Mutation/supportinggoods.php?status=new-success");
    } else {
        logAction($conn, $creator, 'Create', 'gagal menambahkan mutasi penunjang produksi', 1, $mutid);
        header("Location:../Mutation/product-mutation.php?status=error");
    }
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan header mutasi penunjang produksi', 1, $mutid);
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


?>