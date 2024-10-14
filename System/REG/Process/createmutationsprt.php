<?php
include "../../DBConnection.php";

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

// Insert header
$queryh = "INSERT INTO `mutationheader` (`MutationID`, `CreatedOn`, `CreatedBy`, `Description`, `CategoryCD`) 
           VALUES ('$mutid', '$datetime', '$creator', '$desc', 'SPR')";
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

        // Mencari PartCD berdasarkan PartName
        $queryPartCD = "SELECT PartCD FROM sparepart WHERE PartName = '$prodName'";
        $resultPartCD = mysqli_query($conn, $queryPartCD);
        if ($resultPartCD && mysqli_num_rows($resultPartCD) > 0) {
            $rowPartCD = mysqli_fetch_assoc($resultPartCD);
            $partCD = $rowPartCD['PartCD'];

            // Update StockQty di tabel sparepart
            $queryUpdateStock = "UPDATE sparepart SET StockQty = StockQty + $flowin - $flowout WHERE PartCD = '$partCD'";
            if (!mysqli_query($conn, $queryUpdateStock)) {
                echo "Error updating stock: " . mysqli_error($conn);
                $insertSuccess = false;
            }

            // Memasukkan data ke tabel mutationdetailsprt
            $queryd = "INSERT INTO `mutationdetailsprt` (`MutationID`, `CreatedOn`, `FlowIn`, `FlowOut`, `UnitCD`, `Description`, `PartCD`) 
                       VALUES ('$mutid', '$datetime', '$flowin', '$flowout', '$unit', '$description', '$partCD')";
            if (!mysqli_query($conn, $queryd)) {
                echo "Error inserting into mutationdetailsprt: " . mysqli_error($conn);
                $insertSuccess = false;
            }

            // Memasukkan data ke tabel sparepartflowhistory
            $queryHistory = "INSERT INTO `sparepartflowhistory` (`Date`, `ReferenceKey`, `PartCD`, `FlowIn`, `FlowOut`, `Description`) 
                             VALUES ('$datetime', '$mutid', '$partCD', '$flowin', '$flowout', '$description')";
            if (!mysqli_query($conn, $queryHistory)) {
                echo "Error inserting into sparepartflowhistory: " . mysqli_error($conn);
                $insertSuccess = false;
            }

        } else {
            echo "Error: PartCD not found for PartName = '$prodName'<br>";
            logAction($conn, $creator, 'Create', 'Part tidak ditemukan: ' . $prodName, 1, $mutid);
        }
    }

    if ($insertSuccess) {
        logAction($conn, $creator, 'Create', 'menambahkan mutasi sparepart', 0, $mutid);
        header("Location:../Mutation/sparepart.php?status=new-success");
    } else {
        logAction($conn, $creator, 'Create', 'gagal menambahkan mutasi sparepart', 1, $mutid);
        header("Location:../Mutation/sparepart.php?status=error");
    }
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan header mutasi sparepart', 1, $mutid);
    header("Location:../Mutation/sparepart.php?status=error");
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