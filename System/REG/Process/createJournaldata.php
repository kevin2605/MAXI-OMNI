<?php
include "../../DBConnection.php";

date_default_timezone_set("Asia/Jakarta");

if (isset($_GET['rcvinvid']) && !empty($_GET['rcvinvid'])) {
    $rcvinvid = $_GET['rcvinvid'];
} else {
    die("Error: Parameter 'rcvinvid' tidak ditemukan di URL.");
}

$biaya = isset($_POST['biaya']) ? (is_array($_POST['biaya']) ? $_POST['biaya'] : [$_POST['biaya']]) : [];
$kodeakun = isset($_POST['kodeakun']) ? (is_array($_POST['kodeakun']) ? $_POST['kodeakun'] : [$_POST['kodeakun']]) : [];

$date = date('Y-m-d');

for ($i = 0; $i < count($biaya); $i++) {
    $akun = isset($kodeakun[$i]) && !empty($kodeakun[$i]) ? $kodeakun[$i] : null;
    $debit = 0;
    $credit = intval(isset($biaya[$i]) && !empty($biaya[$i]) ? $biaya[$i] : 0);
    if ($akun && $credit > 0) {
        $checkQuery = "SELECT * FROM journaldata WHERE AccountCD = '$akun' AND Credit = '$credit' AND Notes = '$rcvinvid'";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($checkResult) == 0) {
            $queryAccountName = "SELECT AccountName FROM chartofaccount WHERE AccountCD = '$akun'";
            $resultAccountName = mysqli_query($conn, $queryAccountName);
            if ($resultAccountName && mysqli_num_rows($resultAccountName) > 0) {
                $row = mysqli_fetch_assoc($resultAccountName);
                $namaakun = $row['AccountName'];
                $queryj = "INSERT INTO `journaldata`(`JournalDate`, `AccountCD`, `AccountName`, `Debit`, `Credit`, `Notes`)
                           VALUES ('$date', '$akun', '$namaakun', '$debit', '$credit', '$rcvinvid')";
                $resultj = mysqli_query($conn, $queryj);
                if (!$resultj) {
                    error_log("Error: " . mysqli_error($conn));
                }
            }
        }
    }
}

header("Location: ../Import-Purchasing/invoicing.php");
exit();
?>