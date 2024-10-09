<?php
include '../DBConnection.php'; // Pastikan Anda menyertakan file koneksi ke database
$userID = isset($_COOKIE["UserID"]) ? $_COOKIE["UserID"] : '';

// Default tidak bisa update
$canUpdate = false;

if (!empty($userID)) {
    // Query untuk mengambil nilai BahanBaku dari tabel useraccesslevel berdasarkan UserID
    $query_access = "SELECT BahanBaku FROM useraccesslevel WHERE UserID = '$userID'";
    $result_access = mysqli_query($conn, $query_access);

    if ($result_access) {
        $row_access = mysqli_fetch_assoc($result_access);
        $access_level = $row_access['BahanBaku'];

        // Periksa apakah BahanBaku mengandung 'U' untuk Update
        if (strpos($access_level, 'U') !== false) {
            $canUpdate = true;
        }
    } else {
        die("Error: Gagal mengambil data akses pengguna.");
    }
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$query = "SELECT * FROM material";

if ($status_filter == '1') {
    $query .= " WHERE Status = 1";
} elseif ($status_filter == '0') {
    $query .= " WHERE Status = 0";
}

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    echo '<tr>
            <td>' . $row["Sequence"] . '</td>
            <td>' . $row["MaterialCD"] . '</td>
            <td><a href="material-history.php?matcd=' . $row["MaterialCD"] . '">' . $row["MaterialName"] . '</a></td>
            <td>' . $row["WarehCD"] . '</td>
            <td>' . $row["StockQty"] . '</td>
            <td>' . $row["UnitCD_2"] . '</td>
            <td>' . $row["LastEdit"] . '</td>
            <td> 
            <ul> 
            <button onclick="viewMaterial(this)" type="button" class="light-card border-primary border b-r-10" value="' . $row["MaterialCD"] . '"><i class="fa fa-eye txt-primary"></i></button>';
    if ($canUpdate) {
        echo '<button onclick="editMaterial(this)" type="button" class="light-card border-warning border b-r-10" value="' . $row["MaterialCD"] . '"><i class="icon-pencil-alt txt-warning"></i></button>';
    }
    echo '</ul>
        </td>
    </tr>';
}
?>