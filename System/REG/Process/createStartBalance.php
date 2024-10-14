<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

$query = "SELECT MutationID FROM mutationheader WHERE substr(CreatedOn, 6, 2)='" . date("m") . "' ORDER BY MutationID DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row && $row["MutationID"] != "") {
    $lastnumber = substr($row["MutationID"], 9);
    $lastnumber = intval($lastnumber);
    $lastnumber += 1;
} else {
    $lastnumber = 1;
}

$mutid = "MUT-" . date("ym") . "-" . str_pad($lastnumber, 4, "0", STR_PAD_LEFT);

// Parameter
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}
$datetime = date('Y-m-d H:i:s');
$desc = $_POST["desc"];
$categoryCD = 'SA';

$queryh = "INSERT INTO `mutationheader` (`MutationID`, `CreatedOn`, `CreatedBy`, `Description`, `CategoryCD`) 
           VALUES ('$mutid', '$datetime', '$creator', '$desc', 'SA')";
$resulth = mysqli_query($conn, $queryh);

if ($resulth) {
    $arrProd = $_POST["products"];
    $arrFlowin = $_POST["flowin"];
    $arrFlowout = $_POST["flowout"];
    $arrUnit = $_POST["units"];
    $arrDesc = $_POST["descriptions"];

    for ($i = 0; $i < count($arrProd); $i++) {
        $prodcd = $arrProd[$i];
        $flowin = $arrFlowin[$i];
        $flowout = isset($arrFlowout[$i]) ? $arrFlowout[$i] : 0;
        $unit = $arrUnit[$i];
        $description = $arrDesc[$i];

        $queryd = "INSERT INTO `mutationdetail` (`MutationID`, `CreatedOn`, `ProductCD`, `FlowIn`, `FlowOut`, `UnitCD`, `Description`) 
                   VALUES ('$mutid', '$datetime', '$prodcd', '$flowin', '$flowout', '$unit', '$description')";
        $resultd = mysqli_query($conn, $queryd);
    }

    if ($resultd) {
        logAction($conn, $creator, 'Create', 'menambahkan mutasi saldo awal', 0, $mutid);
        header("Location:../Mutation/startBalance.php?status=new-success");
    } else {
        logAction($conn, $creator, 'Create', 'gagal menambahkan mutasi saldo awal', 1, $mutid);
        header("Location:../Mutation/startBalance.php?status=error");
    }
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan header mutasi', 1, $mutid);
    header("Location:../Mutation/startBalance.php?status=error");
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