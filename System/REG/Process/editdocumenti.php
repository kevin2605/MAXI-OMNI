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

$receptionID = $_POST["ReceptionID"];
$posupp = explode(" | ", $_POST["poid"]);
$noInvoice = $_POST["noInvoice"];
$noPackingList = $_POST["noPackingList"];
$noBL = $_POST["noBL"];
$noInsurance = $_POST["noInsurance"];
$datetime = date('Y-m-d H:i:s');

$uploadDir = '../Import-Purchasing/documentimageI/';
function generateUniqueFormattedFileName($receptionID, $category, $uploadDir, $sequence, $fileName)
{
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $formattedName = $receptionID . "-" . str_pad($sequence, 4, "0", STR_PAD_LEFT) . "_$category." . $fileExt;
    return $formattedName;
}
function uploadAndUpdateDocumentImage1($fileInputNames, $uploadDir, $conn, $recid)
{
    $sequence = 1;

    // Ambil nama file yang sudah ada di database
    $querySelect = "SELECT `documentimageI_1` FROM importreceptiondetail WHERE ReceptionID='$recid'";
    $result = mysqli_query($conn, $querySelect);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $existingFiles = $row['documentimageI_1'];
        $existingFileNames = array_filter(explode(',', $existingFiles)); // filter to remove empty elements
    } else {
        echo "Error: Gagal mengambil data dari importreceptiondetail.<br>" . mysqli_error($conn);
        return;
    }

    foreach ($fileInputNames as $fileInputName) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$fileInputName];
            $tmpName = $file['tmp_name'];
            $originalName = $file['name'];
            switch ($fileInputName) {
                case 'dokInvoice':
                    $category = 'Invoice';
                    break;
                case 'dokPackingList':
                    $category = 'PackingList';
                    break;
                case 'dokBL':
                    $category = 'BL';
                    break;
                case 'dokInsurance':
                    $category = 'Insurance';
                    break;
                default:
                    $category = 'Unknown';
            }

            // Periksa apakah ada file lama dengan kategori yang sama
            $oldFileName = null;
            foreach ($existingFileNames as $key => $existingFileName) {
                if (strpos($existingFileName, "_$category.") !== false) {
                    $oldFileName = $existingFileName;
                    unset($existingFileNames[$key]); // Hapus file lama dari array
                    break;
                }
            }

            // Generate nama yang baru
            $uniqueName = generateUniqueFormattedFileName($recid, $category, $uploadDir, $sequence, $originalName);
            $destination = $uploadDir . $uniqueName;

            // Hapus file lama dari direktori
            if ($oldFileName) {
                $oldFilePath = $uploadDir . $oldFileName;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }

            // Pindahkan file baru ke direktori
            if (move_uploaded_file($tmpName, $destination)) {
                $existingFileNames[] = $uniqueName; // Tambahkan nama file baru ke array
                $sequence++;
            } else {
                echo "Failed to move uploaded file: $originalName<br>";
            }
        }
    }

    // Hapus elemen kosong dari array sebelum menyimpannya ke database
    $existingFileNames = array_filter($existingFileNames);

    // Update kolom di database
    if (!empty($existingFileNames)) {
        $newFileNames = implode(',', $existingFileNames);
        $queryUpdate = "UPDATE importreceptiondetail SET `documentimageI_1` = '$newFileNames' WHERE ReceptionID='$recid'";
        if (mysqli_query($conn, $queryUpdate)) {
            echo "File names updated successfully.<br>";
        } else {
            echo "Error: Gagal memperbarui data importreceptiondetail.<br>" . mysqli_error($conn);
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

    $querySelect = "SELECT documentimageI_2 FROM importreceptiondetail WHERE ReceptionID='$recid'";
    $result = mysqli_query($conn, $querySelect);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $existingFiles = $row['documentimageI_2'];
        $existingFileNames = explode(',', $existingFiles);
    } else {
        echo "Error: Gagal mengambil data dari importreceptiondetail.<br>" . mysqli_error($conn);
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
        $queryUpdate = "UPDATE importreceptiondetail SET documentimageI_2 = '$newFileNames' WHERE ReceptionID='$recid'";
        if (mysqli_query($conn, $queryUpdate)) {
            echo "File names updated successfully.<br>";
        } else {
            echo "Error: Gagal memperbarui data importreceptiondetail.<br>" . mysqli_error($conn);
        }
    }
}

$queryHeader = "UPDATE importreceptionheader SET 
                    Invoice='$noInvoice', 
                    PackingList='$noPackingList', 
                    BL='$noBL', 
                    Insurance='$noInsurance' 
                    WHERE ReceptionID='$receptionID'";
$resultHeader = mysqli_query($conn, $queryHeader);

uploadAndUpdateDocumentImage1(['dokInvoice', 'dokPackingList', 'dokBL', 'dokInsurance'], $uploadDir, $conn, $receptionID, 'documentimageI_1');

uploadAndUpdateDocumentImage2('dokbarangI', $uploadDir, $conn, $receptionID, 'documentimageI_2');

if ($resultHeader) {
    logAction($conn, $creator, 'Update', 'berhasil memperbarui dokumen penerimaan import', 0, $receptionID);
    header("Location:../Import-Purchasing/ViewIReception.php?status=approved&id=" . urlencode($receptionID));
    exit();
} else {
    logAction($conn, $creator, 'Update', 'gagal memperbarui dokumen penerimaan import', 1, $receptionID);
    header("Location:../Import-Purchasing/ViewIReception.php?status=reject&id=" . urlencode($receptionID));
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

header("Location: ../Import-Purchasing/ViewIReception.php?status=success&id=$receptionID");
exit;

?>