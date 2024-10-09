<?php
include "../DBConnection.php";

// Memeriksa apakah parameter 'prodcd' ada di URL
if (isset($_GET["prodcd"])) {
    $goodsCD = $_GET["prodcd"];
} else {
    die("Error: Parameter 'prodcd' tidak ditemukan.");
}

echo '<h3>Informasi Barang Penunjang</h3>';
$query = "SELECT * FROM supportinggoods WHERE GoodsCD='" . mysqli_real_escape_string($conn, $goodsCD) . "'";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    echo '<table class="table">
            <tfoot>
                <tr> 
                <td>Kode Barang :</td>
                <td colspan="1">' . $row["GoodsCD"] . '</td>
                </tr>
                <tr> 
                <td>Nama Barang :</td>
                <td colspan="1">' . $row["GoodsName"] . '</td>
                </tr>
                <tr> 
                <td>Supplier :</td>
                <td colspan="1">' . $row["SupplierNum"] . '</td>
                </tr>
                <td>Terakhir Diedit :</td>
                <td colspan="1">' . $row["LastEdit"] . '</td>
                </tr>
                
            </tfoot>
        </table>';
} else {
    echo 'Tidak ada data untuk barang penunjang.';
}

echo '<br><a class="btn btn-warning" href="supporting-goods.php">Back</a>';
?>