<?php
include '../DBConnection.php'; // Pastikan Anda menyertakan file koneksi ke database
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}
$query_access = "SELECT Produk FROM useraccesslevel WHERE UserID = '$creator'";
$result_access = mysqli_query($conn, $query_access);
$can_update = false;
if ($result_access) {
    $row_access = mysqli_fetch_assoc($result_access);
    $access_level = $row_access['Produk'];
    if (strpos($access_level, 'U') !== false) {
        $can_update = true;
    }
} else {
    die("Error: Gagal mengambil data akses pengguna.");
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$query = "SELECT * FROM product";

if ($status_filter == '1') {
    $query .= " WHERE Status = 1";
} elseif ($status_filter == '0') {
    $query .= " WHERE Status = 0";
}

$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    echo '
        <tr>
            <td>' . $row["Sequence"] . '</td>
            <td>' . $row["ProductCD"] . '</td>
            <td><a href="product-history.php?prodcd=' . $row["ProductCD"] . '">' . $row["ProductName"] . '</a></td>
            <td>' . number_format($row["StockQty"], 0, '.', ',') . '</td>
            
            <td>' . $row["GroupCD"] . '</td>
            <td>' . $row["LastEdit"] . '</td>
            <td> 
            <ul> 
            <button onclick="viewProduct(this)" type="button" class="light-card border-primary border b-r-10" value="' . $row["ProductCD"] . '"><i class="fa fa-eye txt-primary"></i></button>';

    if ($can_update) {
        echo '<button onclick="editProduct(this)" type="button" class="light-card border-warning border b-r-10" value="' . $row["ProductCD"] . '"><i class="icon-pencil-alt txt-warning"></i></button>';
    }

    echo '</ul>
            </td>
        </tr>
    ';
}
//<button onclick="deleteProduct(this)" type="button" class="light-card border-danger border b-r-10" value="'.$row["ProductCD"].'"><i class="icon-trash txt-danger"></i></button>

?>