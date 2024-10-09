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

$receptionID = $_POST["ReceptionID"];
$noSuratJalan = $_POST["noSuratJalan"];
$noInvoice = $_POST["noInvoice"];
$createdOn = date('Y-m-d H:i:s');

$uploadDir = '../Local-Purchasing/documentimage/';

function generateUniqueFormattedFileName($receptionID, $category, $uploadDir, $sequence, $fileName)
{
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    do {
        $formattedName = $receptionID . "-" . str_pad($sequence, 4, "0", STR_PAD_LEFT) . "_$category." . $fileExt;
        $sequence++;
    } while (file_exists($uploadDir . $formattedName));

    return $formattedName;
}

function uploadAndUpdateDocumentImage1($fileInputNames, $uploadDir, $conn, $recid)
{
    $fileNames = [];
    $sequence = 1;
    $querySelect = "SELECT documentimage_1 FROM receptiondetail WHERE ReceptionID='$recid'";
    $result = mysqli_query($conn, $querySelect);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $existingFiles = $row['documentimage_1'];
        $existingFileNames = explode(',', $existingFiles);
    } else {
        echo "Error: Gagal mengambil data dari receptiondetail.<br>" . mysqli_error($conn);
        return;
    }
    foreach ($fileInputNames as $fileInputName) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$fileInputName];
            $tmpName = $file['tmp_name'];
            $originalName = $file['name'];
            $category = ($fileInputName === 'dokSuratJalan') ? 'SJ' : 'Invoice';
            $uniqueName = generateUniqueFormattedFileName($recid, $category, $uploadDir, $sequence, $originalName);
            $destination = $uploadDir . $uniqueName;

            if (move_uploaded_file($tmpName, $destination)) {
                foreach ($existingFileNames as $key => $existingFileName) {
                    if (strpos($existingFileName, "_$category.") !== false) {
                        unset($existingFileNames[$key]);
                        break;
                    }
                }
                $fileNames[] = $uniqueName;
                $sequence++;
            } else {
                echo "Failed to move uploaded file: $originalName<br>";
            }
        }
    }
    if (!empty($fileNames)) {
        $allFileNames = array_merge($existingFileNames, $fileNames);
        if (count($allFileNames) > 2) {
            $allFileNames = array_slice($allFileNames, -2);
        }
        $newFileNames = implode(',', $allFileNames);
        $queryUpdate = "UPDATE receptiondetail SET documentimage_1 = '$newFileNames' WHERE ReceptionID='$recid'";
        if (mysqli_query($conn, $queryUpdate)) {
            echo "File names updated successfully.<br>";
        } else {
            echo "Error: Gagal memperbarui data receptiondetail.<br>" . mysqli_error($conn);
        }
    }
}
function generateUniqueFormattedFileName2($receptionID, $category, $uploadDir, $fileName)
{
    do {
        $sequence = mt_rand(1000, 9999);
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $formattedName = $receptionID . "-" . str_pad($sequence, 4, '0', STR_PAD_LEFT) . "-brg." . $fileExt;
        $destination = $uploadDir . $formattedName;
    } while (file_exists($destination));

    return $formattedName;
}

function uploadAndUpdateDocumentImage2($fileInputName, $uploadDir, $conn, $recid)
{
    $fileNames = [];
    $sequence = 1;

    $querySelect = "SELECT documentimage_2 FROM receptiondetail WHERE ReceptionID='$recid'";
    $result = mysqli_query($conn, $querySelect);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $existingFiles = $row['documentimage_2'];
        $existingFileNames = explode(',', $existingFiles);
    } else {
        echo "Error: Gagal mengambil data dari receptiondetail.<br>" . mysqli_error($conn);
        return;
    }

    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'][0] === UPLOAD_ERR_OK) {
        foreach ($_FILES[$fileInputName]['name'] as $key => $originalName) {
            $tmpName = $_FILES[$fileInputName]['tmp_name'][$key];
            $category = 'Barang';
            do {
                $uniqueName = generateUniqueFormattedFileName2($recid, $category, $uploadDir, $originalName);
            } while (in_array($uniqueName, $existingFileNames));
            $destination = $uploadDir . $uniqueName;
            if (move_uploaded_file($tmpName, $destination)) {
                $fileNames[] = $uniqueName;
                $sequence++;
            } else {
                echo "Failed to move uploaded file: $originalName<br>";
            }
        }
    }

    if (!empty($fileNames)) {
        $newFileNames = $existingFiles ? $existingFiles . ',' . implode(',', $fileNames) : implode(',', $fileNames);
        $queryUpdate = "UPDATE receptiondetail SET documentimage_2 = '$newFileNames' WHERE ReceptionID='$recid'";
        if (mysqli_query($conn, $queryUpdate)) {
            echo "File names updated successfully.<br>";
        } else {
            echo "Error: Gagal memperbarui data receptiondetail.<br>" . mysqli_error($conn);
        }
    }
}

$queryHeader = "UPDATE receptionheader SET 
                    SuratJalan='$noSuratJalan', 
                    Invoice='$noInvoice', 
                    LastEdit='$createdOn' 
                    WHERE ReceptionID='$receptionID'";
$resultHeader = mysqli_query($conn, $queryHeader);

uploadAndUpdateDocumentImage1(['dokSuratJalan', 'dokInvoice'], $uploadDir, $conn, $receptionID, 'documentimage_1');

uploadAndUpdateDocumentImage2('dokbarang', $uploadDir, $conn, $receptionID, 'documentimage_2');

if ($resultHeader) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui dokumen penerimaan lokal', 0, $receptionID);
    header("Location:../Local-Purchasing/viewReception.php?status=approved&id=" . urlencode($receptionID));
    exit();
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui dokumen penerimaan lokal', 1, $receptionID);
    header("Location:../Local-Purchasing/viewReception.php?status=reject&id=" . urlencode($receptionID));
    exit();
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
mysqli_close($conn);

header("Location: ../Local-Purchasing/viewReception.php?status=success&id=$receptionID");
exit;

?>