<?php
include "../../DBConnection.php";

// Memeriksa apakah parameter 'prodcd' ada di URL
if (isset($_GET["prodcd"]) && !empty($_GET["prodcd"])) {
    $goodsCD = $_GET["prodcd"];
} else {
    die("Error: Parameter 'prodcd' tidak ditemukan.");
}

echo '
            <h3>Histori Barang Penunjang</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Stok Awal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Stok Akhir</th>
                        <th>Nomor</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>';

$stokawal = 0;
$stokakhir = 0;
$query = "SELECT * FROM otherflowhistory WHERE ItemCD='" . mysqli_real_escape_string($conn, $goodsCD) . "'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr>
                <td>' . substr($row["Date"], 0, 10) . '</td>
                <td>' . number_format($stokawal, 0, '.', ',') . '</td>
                <td>' . number_format($row["FlowIn"], 0, '.', ',') . '</td>
                <td>' . number_format($row["FlowOut"], 0, '.', ',') . '</td>';
        $stokakhir = $stokawal + $row["FlowIn"] - $row["FlowOut"];
        echo '  <td>' . number_format($stokakhir, 0, '.', ',') . '</td>
                <td>' . $row["ReferenceKey"] . '</td>
                <td>' . $row["Description"] . '</td>
            </tr>';

        $stokawal = $stokakhir;
    }
} else {
    echo '<tr><td colspan="7">Tidak ada data</td></tr>';
}

echo '  </tbody>
        </table>';

?>