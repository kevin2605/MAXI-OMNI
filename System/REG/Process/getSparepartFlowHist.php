<?php
include "../../DBConnection.php";

// Validasi apakah parameter 'partname' ada
if (isset($_GET["partname"])) {
    // Decode URL-encoded string menjadi teks yang sebenarnya
    $partName = urldecode($_GET["partname"]);
} else {
    die("Error: Parameter 'partname' tidak ditemukan.");
}

// Ambil data sparepart dari tabel 'sparepart' berdasarkan PartName untuk mendapatkan PartCD
$query = "SELECT * FROM sparepart WHERE PartName='" . mysqli_real_escape_string($conn, $partName) . "'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Error: Sparepart dengan nama tersebut tidak ditemukan.");
}

// Menampilkan histori sparepart dari tabel 'sparepartflowhistory'
echo '<h3>Histori Sparepart</h3>';
echo '<table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Stok Awal</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Stok Akhir</th>
                <th>Nomor Referensi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>';

$stokawal = $row["StockQty"];
$stokakhir = $stokawal;

// Ambil data histori sparepart berdasarkan PartCD dari tabel 'sparepartflowhistory'
$query = "SELECT * FROM sparepartflowhistory WHERE PartCD='" . mysqli_real_escape_string($conn, $row["PartCD"]) . "' ORDER BY Date ASC";
$result = mysqli_query($conn, $query);

while ($rowHistory = mysqli_fetch_array($result)) {
    echo '<tr>
            <td>' . substr($rowHistory["Date"], 0, 10) . '</td>
            <td>' . number_format($stokawal, 0, '.', ',') . '</td>
            <td>' . number_format($rowHistory["FlowIn"], 0, '.', ',') . '</td>
            <td>' . number_format($rowHistory["FlowOut"], 0, '.', ',') . '</td>';

    // Hitung stok akhir berdasarkan FlowIn dan FlowOut
    $stokakhir = $stokawal + $rowHistory["FlowIn"] - $rowHistory["FlowOut"];

    echo '  <td>' . number_format($stokakhir, 0, '.', ',') . '</td>
            <td>' . $rowHistory["ReferenceKey"] . '</td>
            <td>' . $rowHistory["Description"] . '</td>
        </tr>';

    // Update stok awal untuk iterasi berikutnya
    $stokawal = $stokakhir;
}

echo '  </tbody>
      </table>';

mysqli_close($conn);
?>