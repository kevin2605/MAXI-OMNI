<?php
include '../../DBConnection.php'; // Sesuaikan dengan lokasi file koneksi database
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}
$query_access = "SELECT Satuan FROM useraccesslevel WHERE UserID = '$creator'";
$result_access = mysqli_query($conn, $query_access);
$can_updatee = false;
if ($result_access) {
    $row_access = mysqli_fetch_assoc($result_access);
    $access_level = $row_access['Satuan'];
    if (strpos($access_level, 'U') !== false) {
        $can_updatee = true;
    }
} else {
    die("Error: Gagal mengambil data akses pengguna.");
}
$status = isset($_GET['status']) ? $_GET['status'] : '1';
$query = "SELECT * FROM unit WHERE Status = '$status'";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    echo '
    <tr>
        <td>' . $row["UnitCD"] . '</td>
        <td>' . $row["UnitName"] . '</td>
        <td>' . $row["Description"] . '</td>
        <td>' . $row["LastEdit"] . '</td>
        <td>
        <ul class="action">';
    if ($can_updatee) {
        echo '<button onclick="editSatuan(this)" type="button" class="light-card border-warning border b-r-10" value="' . $row["UnitCD"] . '"><i class="icon-pencil-alt txt-warning"></i></button>';
    }
    echo '
        </ul>
      </td>
    </tr>';
}

mysqli_close($conn);
?>