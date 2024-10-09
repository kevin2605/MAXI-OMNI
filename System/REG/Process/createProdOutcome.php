<?php

include "../DBConnection.php";

// Set timezone
date_default_timezone_set("Asia/Jakarta");

// Ambil data dari POST
$x = explode(" | ", $_POST["spk"]);
$spk = $x[0];
$mesin = $_POST["mesin"];
$produk = $_POST["produk"];
$workhour = $_POST["workhour"];
$shift = $_POST["shift"];
$hasil = $_POST["hasil"];
$rusak = $_POST["rusak"];
$closeorder = $_POST["closeorder"];
$creator = $_COOKIE["UserID"] ?? 'unknown'; // Menggunakan cookie untuk creator
$datetime = date('Y-m-d H:i:s');

// Query untuk memasukkan data ke tabel productionresulthistory
$query = "INSERT INTO `productionresulthistory`(`ProductionOrderID`, `MachineCD`, `ProductCD`, `CreatedOn`, `WorkingHour`, `ProdOutcome`, `ProdLoss`, `Shift`) 
          VALUES ('$spk','$mesin','$produk','$datetime','$workhour','$hasil','$rusak','$shift')";
$result = mysqli_query($conn, $query);

// Update production order
$queryU = "UPDATE productionorder SET ExactOutcome=ExactOutcome+" . $hasil . ", ProdLoss=ProdLoss+" . $rusak . " WHERE ProductionOrderID='" . $spk . "'";
$resultU = mysqli_query($conn, $queryU);

// Check if Estimate = exact + loss
$queryc = "SELECT * FROM productionorder WHERE ProductionOrderID='" . $spk . "'";
$resultc = mysqli_query($conn, $queryc);
$rowc = mysqli_fetch_assoc($resultc);
$totalproduction = $rowc["ExactOutcome"]+$rowc["ProdLoss"];
if($rowc["EstimateOutcome"] == $totalproduction){
    $queryU = "UPDATE productionorder SET Status='1' WHERE ProductionOrderID='" . $spk . "'";
    $resultU = mysqli_query($conn, $queryU);
}

// Check if closeorder value is 1
if($closeorder == 1){
    $queryU = "UPDATE productionorder SET Status='1' WHERE ProductionOrderID='" . $spk . "'";
    $resultU = mysqli_query($conn, $queryU);
}

// Log aktivitas ke tabel systemlog
logAction($conn, $creator, 'Update', 'menambahkan hasil produksi', ($result && $resultU) ? 0 : 1, $spk);

// Arahkan ke halaman dengan status sukses atau error
if ($result && $resultU) {
    header("Location:../Production/production-outcome.php?status=success");
} else {
    header("Location:../Production/production-outcome.php?status=error");
}

function logAction($conn, $userID, $actionDone, $actionMSG, $actionStatus, $recordID)
{
    $timestamp = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO systemlog (Timestamp, UserID, ActionDone, ActionMSG, ActionStatus, RecordID) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssss", $timestamp, $userID, $actionDone, $actionMSG, $actionStatus, $recordID);
        if (!$stmt->execute()) {
            error_log("Log action failed: " . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
}

?>