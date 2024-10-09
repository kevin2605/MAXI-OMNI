<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");


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

if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$createdOn = date('Y-m-d H:i:s');

$duplicate = true;
$custnum = 0;

while ($duplicate) {
    $custnum = rand(10000, 99999);
    $query = "SELECT COUNT(*) AS dup FROM customer WHERE CustID='" . $custnum . "'";
    $res = mysqli_query($conn, $query);
    $ctr = mysqli_fetch_assoc($res);
    $row = $ctr["dup"];

    if ($row >= 1) {
        $duplicate = true;
    } else {
        $duplicate = false;
    }
}

$query = "INSERT INTO `customer`(`CustID`, `CustName`, `CompanyName`, `ShipmentAddress`, `CityName`, `PhoneNumOne`, `PhoneNumTwo`, `Email`, `PriceListCD`, `NIK`, `KTPName`, `KTPAddress`, `NPWPName`, `NPWPNum`, `NPWPAddress`, `Status`, `CreatedBy`, `CreatedOn`, `LastEdit`) 
                        VALUES ('$custnum','$namapel','$namacom','$alamatkirim','$kota','$nohp1','$nohp2','$email','$pricelist','$nik','$namaktp','$alamatktp','$namaNPWP','$nomorNPWP','$alamatNPWP','$status','$creator','$createdOn','$createdOn')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan pelanggan', 0, $custnum);
    header("Location:../Customer/customer.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'Add Customer Failed', 1, $custnum);
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