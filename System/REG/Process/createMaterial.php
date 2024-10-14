<?php

include "../../DBConnection.php";

date_default_timezone_set("Asia/Jakarta");

$urutanreport = $_POST["urutanreport"];
$kodebahan = $_POST["kodebahan"];
$namabahan = $_POST["namabahan"];
$satuanpertama = $_POST["satuanpertama"];
$satuankedua = $_POST["satuankedua"];
$kategori = $_POST["kategori"];
$group = $_POST["group"];
$gudang = $_POST["gudang"];
$supplier = isset($_POST["supplier"]) ? $_POST["supplier"] : NULL;
$products = explode(" - ", $_POST["produk"]);
$product = $products[0];
$keterangan1 = $_POST["keterangan1"];
$keterangan2 = $_POST["keterangan2"];
$keterangan3 = $_POST["keterangan3"];
$buyprice = $_POST["buyprice"];
$rulesjual = $_POST["rulesJual"];
$rulesbeli = $_POST["rulesBeli"];
$rulesproduksi = $_POST["rulesProduksi"];
$rulestransaksi = $_POST["rulesTransaksi"];
$status = $_POST["produkStatus"];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');

$query = "INSERT INTO `material`(`MaterialCD`, `MaterialName`, `Sequence`, `StockQty`, `ProductCD`, `CategoryCD`, `GroupCD`, `UnitCD_1`, `UnitCD_2`, `WarehCD`, `SupplierNum`, `Desc_1`, `Desc_2`, `Desc_3`,`BuyPrice`, `Sales`, `Purchase`, `Production`, `Transaction`, `Status`, `CreatedBy`, `CreatedOn`, `LastEdit`) 
VALUES ('$kodebahan','$namabahan','$urutanreport','0','$product','$kategori','$group','$satuanpertama','$satuankedua','$gudang','$supplier','$keterangan1','$keterangan2','$keterangan3','$buyprice','$rulesjual','$rulesbeli','$rulesproduksi','$rulestransaksi','$status','$creator','$createdOn','$createdOn')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan material', 0, $kodebahan);
    header("Location:../Material/material.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan material', 1, $kodebahan);
    header("Location:../Material/material.php?status=error");
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