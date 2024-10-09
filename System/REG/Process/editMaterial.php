<?php

include "../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

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
$createdOn = date('Y-m-d H:i:s');

$query = "UPDATE `material` SET `MaterialName`='$namabahan', `Sequence`='$urutanreport', `ProductCD`='$product', `CategoryCD`='$kategori', `GroupCD`='$group', `UnitCD_1`='$satuanpertama', 
          `UnitCD_2`='$satuankedua', `WarehCD`='$gudang', `SupplierNum`='$supplier', `Desc_1`='$keterangan1', `Desc_2`='$keterangan2', `Desc_3`='$keterangan3', 
          `BuyPrice`='$buyprice', `Sales`='$rulesjual', `Purchase`='$rulesbeli', `Production`='$rulesproduksi', `Transaction`='$rulestransaksi', `Status`='$status', 
          `CreatedBy`='System', `LastEdit`='$createdOn' WHERE `MaterialCD`='$kodebahan'";
$result = mysqli_query($conn, $query);


if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui bahan baku', 0, $kodebahan);
    header("Location:../Material/material.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui bahan baku', 1, $kodebahan);
    header("Location:../Material/material.php?status=error-edit");
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