<?php

include "../../DBConnection.php";

// Set timezon
date_default_timezone_set("Asia/Jakarta");

$namasupplier = $_POST["namasupplier"];
$alamat = $_POST["alamat"];
$email = $_POST["email"];
$telepon = $_POST["telepon"];
$hpsupplier = $_POST["hpsupplier"];
$namakontak = $_POST["namakontak"];
$hpkontak = $_POST["hpkontak"];
$keterangan = $_POST["description"];
$approval = $_POST["approval"];
$status = $_POST["suppStatus"];
$namaNPWP = $_POST["namaNPWP"];
$nomorNPWP = $_POST["nomorNPWP"];
$alamatNPWP = $_POST["alamatNPWP"];
$beneficiaryBank = $_POST["beneficiaryBank"];
$norek = $_POST["norek"];

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');

$duplicate = true;
$suppnum = 0;

while ($duplicate) {
    $suppnum = rand(10000, 99999);
    $query = "SELECT COUNT(*) AS dup FROM supplier WHERE SupplierNum='" . $suppnum . "'";
    $res = mysqli_query($conn, $query);
    $ctr = mysqli_fetch_assoc($res);
    $row = $ctr["dup"];

    if ($row >= 1) {
        $duplicate = true;
    } else {
        $duplicate = false;
    }
}

$query = "INSERT INTO `supplier`(`SupplierNum`, `SupplierName`, `SupplierAdd`, `Email`, `Telepon`, `PhoneNum`, `NPWPName`, `NPWPNum`, `NPWPAddress`, `ContactName`, `ContactPhone`, `BankCode`, `BeneficiaryNumber`, `Description`, `Approval`, `Status`, `CreatedBy`, `CreatedOn`, `LastEdit`) 
VALUES ('$suppnum','$namasupplier','$alamat','$email','$telepon','$hpsupplier','$namaNPWP','$nomorNPWP','$alamatNPWP','$namakontak','$hpkontak','$beneficiaryBank','$norek','$keterangan','$approval','$status','$creator','$createdOn','$createdOn')";
$result = mysqli_query($conn, $query);


if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan suplier', 0, $suppnum);
    header("Location:../Supplier/supplier.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan suplier', 1, $suppnum);
    echo "Error: " . mysqli_error($conn) . "<br>";
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