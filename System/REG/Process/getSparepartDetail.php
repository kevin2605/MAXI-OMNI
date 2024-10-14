<?php
include "../../DBConnection.php";

// Validasi apakah parameter 'partname' ada
if (isset($_GET["partname"])) {
    // Decode URL-encoded string menjadi teks yang sebenarnya
    $partName = urldecode($_GET["partname"]);
} else {
    die("Error: Parameter 'partname' tidak ditemukan.");
}

// Ambil data sparepart dari tabel 'sparepart' berdasarkan PartName
$query = "SELECT * FROM sparepart WHERE PartName='" . mysqli_real_escape_string($conn, $partName) . "'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Error: Sparepart dengan nama tersebut tidak ditemukan.");
}

// Menampilkan informasi detail sparepart
echo '<h3>Informasi Sparepart</h3>';
echo '<table class="table">
        <tfoot>
            <tr> 
                <td>Kode Sparepart :</td>
                <td colspan="1">' . $row["PartCD"] . '</td>
            </tr>
            <tr> 
                <td>Nama Sparepart :</td>
                <td colspan="1">' . $row["PartName"] . '</td>
            </tr>
            <tr> 
                <td>Supplier :</td>
                <td colspan="1">' . $row["SupplierNum"] . '</td>
            </tr>
            <tr> 
                <td>Deskripsi :</td>
                <td colspan="1">' . $row["Desc_1"] . ' ' . $row["Desc_2"] . ' ' . $row["Desc_3"] . '</td>
            </tr>
            <tr> 
                <td>Terakhir Diedit :</td>
                <td colspan="1">' . $row["LastEdit"] . '</td>
            </tr>
        </tfoot>
    </table>';

echo '<br><a class="btn btn-warning" href="sparepart.php">Back</a>';

mysqli_close($conn);
?>