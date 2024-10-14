<?php

include "../../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Parameter
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$suppnum = $_POST["suppnum"];
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
$createdOn = date('Y-m-d H:i:s');

$query = "UPDATE supplier SET 
            SupplierName='$namasupplier', SupplierAdd='$alamat', Email='$email', Telepon='$telepon', PhoneNum='$hpsupplier',
            NPWPName='$namaNPWP', NPWPNum='$nomorNPWP', NPWPAddress='$alamatNPWP', ContactName='$namakontak', ContactPhone='$hpkontak', 
            BankCode='$beneficiaryBank', BeneficiaryNumber='$norek', Description='$keterangan', Approval='$approval', 
            Status='$status', LastEdit='$createdOn' WHERE SupplierNum='$suppnum'";
$result = mysqli_query($conn, $query);

if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui supplier', 0, $suppnum);
    header("Location:../Supplier/supplier.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui supplier', 1, $suppnum);
    header("Location:../Supplier/supplier.php?status=error-edit");
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