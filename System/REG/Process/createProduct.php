<?php

include "../DBConnection.php";

date_default_timezone_set("Asia/Jakarta");

$kodeproduk = $_POST["kodeproduk"];
$namaproduk = $_POST["namaproduk"];
$urutanreport = $_POST["urutanreport"];
$kategori = $_POST["kategori"];
$group = $_POST["group"];
$satuan = $_POST["satuan"];
$gudang = $_POST["gudang"];
$supplier = $_POST["supplier"];
$pcdperdos = $_POST["pcsperdos"];
$boxpanjang = $_POST["boxpanjang"];
$boxlebar = $_POST["boxlebar"];
$boxtinggi = $_POST["boxtinggi"];
$rulesjual = $_POST["rulesJual"];
$rulesbeli = $_POST["rulesBeli"];
$rulesproduksi = $_POST["rulesProduksi"];
$rulestransaksi = $_POST["rulesTransaksi"];
$status = $_POST["produkStatus"];
$createdOn = date('Y-m-d H:i:s');

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$query = "INSERT INTO `product`(`ProductCD`, `ProductName`, `Sequence`, `StockQty`, `CategoryCD`, `GroupCD`, `UnitCD`, `WarehCD`, `SupplierNum`, `PcsPerBox`, `BoxLength`, `BoxWidth`, `BoxHeight`, `Sales`, `Purchase`, `Production`, `Transaction`, `Status`, `CreatedBy`, `CreatedOn`, `LastEdit`) 
VALUES ('$kodeproduk','$namaproduk','$urutanreport','0','$kategori','$group','$satuan','$gudang','$supplier','$pcdperdos','$boxpanjang','$boxlebar','$boxtinggi','$rulesjual','$rulesbeli','$rulesproduksi','$rulestransaksi','$status','$creator','$createdOn','$createdOn')";

$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan produk', 0, $kodeproduk);
    header("Location:../Product/product.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan produk', 1, $kodeproduk);
    header("Location:../Product/product.php?status=error");
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