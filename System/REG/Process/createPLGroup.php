<?php

include "../DBConnection.php";

date_default_timezone_set("Asia/Jakarta");

$PLname = $_POST["PLname"];
$minorder = $_POST["minorder"];
$startdate = $_POST["startdate"];
$enddate = $_POST["enddate"];

$query = "SELECT PriceListCD FROM pricelistheader ORDER BY PriceListCD DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row["PriceListCD"] != "") {
    $lastnumber = substr($row["PriceListCD"], 6);
    $lastnumber = intval($lastnumber) + 1;
} else {
    $lastnumber = 1;
}

$plcode = date("Ym") . str_pad($lastnumber, 2, "0", STR_PAD_LEFT);


if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];

} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$query = "INSERT INTO pricelistheader (PriceListCD, PriceListName, MinimalOrder, StartDate, EndDate)
            VALUES ('$plcode', '$PLname', '$minorder', '$startdate', '$enddate')";
$result = mysqli_query($conn, $query);

if ($result) {
    logAction($conn, $creator, 'Create', 'menambahkan price list', 0, $plcode);
    header("Location:../Product/price-list.php?status=success");
} else {
    logAction($conn, $creator, 'Create', 'gagal menambahkan price list', 1, $plcode);
    header("Location:../Product/price-list.php?status=error");
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