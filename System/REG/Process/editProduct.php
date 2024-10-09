<?php
include "../DBConnection.php";

//set timezone
date_default_timezone_set("Asia/Jakarta");

$creator = $_COOKIE["UserID"];
$urutanreport = $_POST["urutanreport"];
$kodeproduk = $_POST["kodeproduk"];
$oldkodeproduk = $_POST["oldkodeproduk"];
$namaproduk = $_POST["namaproduk"];
$satuan = $_POST["satuan"];
$kategori = $_POST["kategori"];
$group = $_POST["group"];
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


$query = "UPDATE `product` SET `ProductCD`='$kodeproduk', `ProductName`='$namaproduk', `Sequence`='$urutanreport', `CategoryCD`='$kategori', `GroupCD`='$group', 
          `UnitCD`='$satuan', `WarehCD`='$gudang', `SupplierNum`='$supplier', `PcsPerBox`='$pcdperdos', `BoxLength`='$boxpanjang', `BoxWidth`='$boxlebar', `BoxHeight`='$boxtinggi',
          `Sales`='$rulesjual', `Purchase`='$rulesbeli', `Production`='$rulesproduksi', `Transaction`='$rulestransaksi', `Status`='$status', `LastEdit`='$createdOn'
          WHERE `ProductCD`='$oldkodeproduk'";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui produk', 0, $kodeproduk);
    header("Location:../Product/product.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui produk', 1, $kodeproduk);
    header("Location:../Product/product.php?status=error-edit");
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