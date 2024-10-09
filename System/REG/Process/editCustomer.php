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

$custid = $_POST["custid"];
$namapel = $_POST["namapel"];
$namacom = $_POST["namacom"];
$alamatkirim = $_POST["alamatkirim"];
$kota = $_POST["kota"];
$nohp1 = $_POST["nohp1"];
$nohp2 = $_POST["nohp2"];
$email = $_POST["email"];
$plcd = explode(" - ", $_POST["pricelist"]);
$pricelist = $plcd[0];
$nik = $_POST["nik"];
$namaktp = $_POST["namaktp"];
$alamatktp = $_POST["alamatktp"];
$namaNPWP = $_POST["namaNPWP"];
$nomorNPWP = $_POST["nomorNPWP"];
$alamatNPWP = $_POST["alamatNPWP"];
$status = $_POST["customerStatus"];
$createdOn = date('Y-m-d H:i:s');


// Update customer data
$query = "UPDATE customer SET 
            CustName='$namapel', CompanyName='$namacom', ShipmentAddress='$alamatkirim', CityName='$kota', PhoneNumOne='$nohp1',
            PhoneNumTwo='$nohp2', Email='$email', PriceListCD='$pricelist', NIK='$nik', KTPName='$namaktp', KTPAddress='$alamatktp', 
            NPWPName='$namaNPWP', NPWPNum='$nomorNPWP', NPWPAddress='$alamatNPWP', Status='$status', LastEdit='$createdOn' 
            WHERE CustID='$custid'";
$result = mysqli_query($conn, $query);

// Log the action
if ($result == 1) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui data customer', 0, $custid);
    header("Location:../Customer/customer.php?status=success-edit");
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui data customer', 1, $custid);
    header("Location:../Customer/customer.php?status=error-edit");
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