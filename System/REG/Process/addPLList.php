<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Parameter
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$plcd = $_POST["plcd"];
$plname = $_POST["plname"];
$arrProd = $_POST["products"];
$arrPrice = $_POST["prices"];
$datetime = date('Y-m-d H:i:s');

$allSuccess = true;

for ($i = 0; $i < count($arrProd); $i++) {
    $prodcd = $arrProd[$i];
    $price = $arrPrice[$i];

    $queryd = "INSERT INTO `pricelistdetail`(`PriceListCD`, `ProductCD`, `Price`) 
               VALUES ('$plcd','$prodcd','$price')";
    $resultd = mysqli_query($conn, $queryd);

    if (!$resultd) {
        $allSuccess = false;
        break;
    }
}

if ($allSuccess) {
    logAction($conn, $creator, 'Create', 'menambahkan detail daftar harga', 0, $plcd);
    header("Location:../Product/price-list-detail.php?status=success&plcd=" . $plcd . "&plname=" . $plname);
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan detail daftar harga', 1, $plcd);
    header("Location:../Product/price-list-detail.php?status=error&plcd=" . $plcd . "&plname=" . $plname);
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