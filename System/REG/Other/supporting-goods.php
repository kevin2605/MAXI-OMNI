<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";

    session_start();

    // Koneksi ke database
    include "../../DBConnection.php"; // Sesuaikan dengan file koneksi database Anda
    
    // Ambil ID pengguna dari sesi atau cookie
    $userID = $_COOKIE['UserID']; // Sesuaikan dengan cara Anda menyimpan ID pengguna
    
    // Ambil akses level dari database
    $query = "SELECT PenunjangProduksi FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Cek akses CRUD dan tentukan apakah akses diizinkan
    $hasCRUDAccess = strpos($row['PenunjangProduksi'], 'C') !== false || // Create
        strpos($row['PenunjangProduksi'], 'R') !== false || // Read
        strpos($row['PenunjangProduksi'], 'U') !== false || // Update
        strpos($row['PenunjangProduksi'], 'D') !== false;  // Delete
    
    // Jika tidak memiliki akses CRUD, tampilkan pesan dan redirect
    $accessDenied = !$hasCRUDAccess;
    ?>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function withPPN() {
            var cb = document.getElementById("ppnCheck");
            var bp = document.getElementById("buyprice");
            var tb = document.getElementById("usetax");

            if (cb.checked == true) {
                tb.value = Math.round(bp.value * 1.11);
            } else {
                tb.value = "-";
            }
        }

        function viewGoods(x) {
            document.location = "viewSuppGoods.php?goodscd=" + x.value;
        }

        function editGoods(str) {
            Swal.fire({
                title: "Edit Barang Penunjang Produk?",
                text: "Apakah anda yakin mengubah data dari " + str.value,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, setuju!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location = "editSuppGoods.php?goodscd=" + str.value;
                }
            });
        }
    </script>
</head>
<style>
    .hidden {
        display: none;
    }
</style>

<body>
    <?php if ($accessDenied): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            window.addEventListener('DOMContentLoaded', (event) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: 'Anda tidak memiliki akses.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../Dashboard/'; // Redirect ke halaman lain atau homepage
                    }
                });
            });
        </script>
        <div class="loader-wrapper">
            <div class="theme-loader">
                <div class="loader-p"></div>
            </div>
        </div>
        <!-- loader ends-->
        <!-- tap on top starts-->
        <div class="tap-top"><i data-feather="chevrons-up"></i></div>
        <!-- tap on tap ends-->
        <!-- page-wrapper Start-->
        <div class="page-wrapper compact-wrapper" id="pageWrapper">
            <!-- Page Header Start-->
            <div class="page-header">

                <?php include "../topmenu.php"; ?>

            </div>
            <!-- Page Header Ends-->
            <!-- Page Body Start-->
            <div class="page-body-wrapper">
                <!-- Page Sidebar Start-->

                <?php include "../sidemenu.php"; ?>

                <!-- Page Sidebar Ends-->
                <div class="page-body">
                    <div class="container-fluid">
                        <div class="page-title">
                            <?php
                            if (isset($_GET["status"])) {
                                if ($_GET["status"] == "success") {
                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Selamat! </b>Barang Penunjang Produksi baru berhasil disimpan ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "error") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Barang Penunjang Produksi ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "success-edit") {
                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Selamat! </b>Barang Penunjang Produksi berhasil disimpan ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-6 ps-0">
                                    <h3>BARANG PENUNJANG PRODUKSI</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Barang</li>
                                        <li class="breadcrumb-item">Penunjang Produksi</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Container-fluid starts-->
                    <div class="container-fluid <?php echo $accessDenied ? 'hidden' : ''; ?>">
                    <?php endif; ?>
                    <div class="loader-wrapper">
                        <div class="theme-loader">
                            <div class="loader-p"></div>
                        </div>
                    </div>
                    <!-- loader ends-->
                    <!-- tap on top starts-->
                    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
                    <!-- tap on tap ends-->
                    <!-- page-wrapper Start-->
                    <div class="page-wrapper compact-wrapper" id="pageWrapper">
                        <!-- Page Header Start-->
                        <div class="page-header">

                            <?php include "../topmenu.php"; ?>

                        </div>
                        <!-- Page Header Ends-->
                        <!-- Page Body Start-->
                        <div class="page-body-wrapper">
                            <!-- Page Sidebar Start-->

                            <?php include "../sidemenu.php"; ?>

                            <!-- Page Sidebar Ends-->
                            <div class="page-body">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <?php
                                        if (isset($_GET["status"])) {
                                            if ($_GET["status"] == "success") {
                                                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Selamat! </b>Barang Penunjang Produksi baru berhasil disimpan ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                            } else if ($_GET["status"] == "error") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Barang Penunjang Produksi ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                            } else if ($_GET["status"] == "success-edit") {
                                                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Selamat! </b>Barang Penunjang Produksi berhasil disimpan ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                            }
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-6 ps-0">
                                                <h3>BARANG PENUNJANG PRODUKSI</h3>
                                            </div>
                                            <div class="col-sm-6 pe-0">
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="index.html">
                                                            <svg class="stroke-icon">
                                                                <use
                                                                    href="../../../assets/svg/icon-sprite.svg#stroke-home">
                                                                </use>
                                                            </svg></a></li>
                                                    <li class="breadcrumb-item">Barang</li>
                                                    <li class="breadcrumb-item">Penunjang Produksi</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <?php
                                                    $canUpdate = false;
                                                    if (!empty($userID)) {
                                                        $query_access = "SELECT PenunjangProduksi FROM useraccesslevel WHERE UserID = '$userID'";
                                                        $result_access = mysqli_query($conn, $query_access);
                                                        if ($result_access) {
                                                            $row_access = mysqli_fetch_assoc($result_access);
                                                            $access_level = $row_access['PenunjangProduksi'];
                                                            if (strpos($access_level, 'C') !== false) {
                                                                $canUpdate = true;
                                                            }
                                                        } else {
                                                            die("Error: Gagal mengambil data akses pengguna.");
                                                        }
                                                    } else {
                                                        die("Error: Cookie 'UserID' tidak ada atau kosong.");
                                                    }
                                                    ?>
                                                    <div class="col-md-3">
                                                        <button class="btn btn-outline-primary" type="button"
                                                            data-bs-toggle="modal" data-bs-target=".bd-example-modal-xl"
                                                            <?php echo $canUpdate ? '' : 'disabled'; ?>>
                                                            <i class="fa fa-plus-circle"></i> New
                                                        </button>
                                                        <div class="modal fade bd-example-modal-xl" tabindex="-1"
                                                            role="dialog" aria-labelledby="myExtraLargeModal"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="myExtraLargeModal">
                                                                            Form Barang
                                                                            Penunjang</h4>
                                                                        <button class="btn-close py-0" type="button"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body dark-modal">
                                                                        <div class="card-body custom-input">
                                                                            <form class="row g-3"
                                                                                action="../Process/createSupportingGoods.php"
                                                                                method="POST">
                                                                                <div class="col-3">
                                                                                    <label class="form-label"
                                                                                        for="urutanreport">Urutan<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="urutanreport"
                                                                                        name="urutanreport" type="text"
                                                                                        placeholder="1" required>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <label class="form-label"
                                                                                        for="kodebarang">Kode
                                                                                        Barang<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="kodebarang"
                                                                                        name="kodebarang" type="text"
                                                                                        placeholder="ABXXXX"
                                                                                        aria-label="Kode Produk"
                                                                                        required>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label class="form-label"
                                                                                        for="namabarang">Nama
                                                                                        Barang<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="namabarang"
                                                                                        name="namabarang" type="text"
                                                                                        placeholder="Barang Penunjang Produksi"
                                                                                        required>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="satuanpertama">Satuan<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="satuanpertama"
                                                                                        list="satuanOptions"
                                                                                        name="satuan"
                                                                                        placeholder="Satuan" required>
                                                                                    <datalist id="satuanOptions">
                                                                                        <?php
                                                                                        $query = "SELECT UnitCD,UnitName FROM unit WHERE Status='1'";
                                                                                        $result = mysqli_query($conn, $query);
                                                                                        while ($row = mysqli_fetch_array($result)) {
                                                                                            echo '<option value="' . $row["UnitCD"] . '">' . $row["UnitName"] . '</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="group">Group<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="group" name="group"
                                                                                        list="groupOptions"
                                                                                        placeholder="Group" required>
                                                                                    <datalist id="groupOptions">
                                                                                        <?php
                                                                                        $query = "SELECT GroupCD,GroupName FROM groups WHERE Status='1'";
                                                                                        $result = mysqli_query($conn, $query);
                                                                                        while ($row = mysqli_fetch_array($result)) {
                                                                                            echo '<option value="' . $row["GroupCD"] . '">' . $row["GroupName"] . '</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="kategori">Kategori<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="kategori" name="kategori"
                                                                                        value="BPP" readonly>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="supplier">Supplier<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="supplier" name="supplier"
                                                                                        list="supplierOptions"
                                                                                        placeholder="Supplier" required>
                                                                                    <datalist id="supplierOptions">
                                                                                        <?php
                                                                                        $query = "SELECT SupplierNum, SupplierName FROM supplier WHERE Status='1'";
                                                                                        $result = mysqli_query($conn, $query);
                                                                                        while ($row = mysqli_fetch_array($result)) {
                                                                                            echo '<option value="' . $row["SupplierNum"] . '">' . $row["SupplierName"] . '</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label class="form-label"
                                                                                        for="keterangan1">Keterangan
                                                                                        1</label>
                                                                                    <input class="form-control"
                                                                                        id="keterangan1"
                                                                                        name="keterangan1" type="text"
                                                                                        placeholder="...">
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <label class="form-label"
                                                                                        for="buyprice">Harga
                                                                                        Beli<span
                                                                                            style="color:red;">*</span>
                                                                                        <i>exclude</i></label>
                                                                                    <input class="form-control"
                                                                                        id="buyprice" name="buyprice"
                                                                                        type="text" placeholder="0"
                                                                                        required>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <input id="ppnCheck" type="checkbox"
                                                                                        name="tax" value="1"
                                                                                        onclick="withPPN()">
                                                                                    <label class="form-label"
                                                                                        for="usetax">PPN</label>
                                                                                    <input class="form-control"
                                                                                        id="usetax" name="usetax"
                                                                                        type="text" value="-" readonly>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label class="form-label"
                                                                                        for="keterangan2">Keterangan
                                                                                        2</label>
                                                                                    <input class="form-control"
                                                                                        id="keterangan2"
                                                                                        name="keterangan2" type="text"
                                                                                        placeholder="...">
                                                                                </div>
                                                                                <div class="col-6"></div>
                                                                                <div class="col-6">
                                                                                    <label class="form-label"
                                                                                        for="keterangan3">Keterangan
                                                                                        3</label>
                                                                                    <input class="form-control"
                                                                                        id="keterangan3"
                                                                                        name="keterangan3" type="text"
                                                                                        placeholder="...">
                                                                                </div>
                                                                                <div class="col-6"></div>
                                                                                <hr>
                                                                                <div class="col-4">
                                                                                    <div
                                                                                        class="card-wrapper border rounded-3 checkbox-checked">
                                                                                        <h6 class="sub-title">
                                                                                            Status?<span
                                                                                                style="color:red;">*</span>
                                                                                        </h6>
                                                                                        <div class="radio-form">
                                                                                            <div class="form-check">
                                                                                                <input
                                                                                                    class="form-check-input"
                                                                                                    id="flexRadioDefault3"
                                                                                                    type="radio"
                                                                                                    name="Status"
                                                                                                    value="1"
                                                                                                    required="">
                                                                                                <label
                                                                                                    class="form-check-label"
                                                                                                    for="flexRadioDefault3">Active</label>
                                                                                            </div>
                                                                                            <div class="form-check">
                                                                                                <input
                                                                                                    class="form-check-input"
                                                                                                    id="flexRadioDefault4"
                                                                                                    type="radio"
                                                                                                    name="Status"
                                                                                                    value="0"
                                                                                                    required="">
                                                                                                <label
                                                                                                    class="form-check-label"
                                                                                                    for="flexRadioDefault4">Inactive</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <hr>
                                                                                <div class="col-12">
                                                                                    <div class="form-check form-switch">
                                                                                        <input class="form-check-input"
                                                                                            id="flexSwitchCheckDefault"
                                                                                            type="checkbox"
                                                                                            role="switch" required>
                                                                                        <label class="form-check-label"
                                                                                            for="flexSwitchCheckDefault">Apakah
                                                                                            informasi diatas sudah
                                                                                            benar?</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <button class="btn btn-primary"
                                                                                        type="submit">Submit</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <!-- BLANK -->
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <select id="status-filter" class="form-control">
                                                                    <option value="all">-- Pilih Status --</option>
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="tab-content" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="daftarBarang"
                                                        role="tabpanel">
                                                        <h3>Daftar Barang</h3>
                                                        <div class="table-responsive custom-scrollbar user-datatable">
                                                            <?php
                                                            include "../../DBConnection.php";
                                                            if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
                                                                $creator = $_COOKIE["UserID"];
                                                            } else {
                                                                die("Error: Cookie 'UserID' tidak ada atau kosong.");
                                                            }
                                                            $can_update = false;
                                                            $query_access = "SELECT PenunjangProduksi FROM useraccesslevel WHERE UserID = '$creator'";
                                                            $result_access = mysqli_query($conn, $query_access);

                                                            if ($result_access) {
                                                                $row_access = mysqli_fetch_assoc($result_access);
                                                                $access_level = $row_access['PenunjangProduksi'];
                                                                if (strpos($access_level, 'U') !== false) {
                                                                    $can_update = true;
                                                                }
                                                            } else {
                                                                die("Error: Gagal mengambil data akses pengguna.");
                                                            }
                                                            ?>
                                                            <table class="display" id="basic-12">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Urutan</th>
                                                                        <th>Kode Barang</th>
                                                                        <th>Nama Barang</th>
                                                                        <th>Stok</th>
                                                                        <th>Satuan</th>
                                                                        <th>Kategori</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="material-table-body">
                                                                    <?php
                                                                    include "../../DBConnection.php";

                                                                    // Ambil status filter dari URL, jika ada
                                                                    $status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

                                                                    // Buat query dasar
                                                                    $query = "SELECT * FROM supportinggoods";

                                                                    // Tambahkan kondisi berdasarkan status filter
                                                                    if ($status_filter == '1') {
                                                                        $query .= " WHERE Status = 1";
                                                                    } elseif ($status_filter == '0') {
                                                                        $query .= " WHERE Status = 0";
                                                                    }

                                                                    // Eksekusi query
                                                                    $result = mysqli_query($conn, $query);

                                                                    // Loop untuk menampilkan data barang
                                                                    while ($row = mysqli_fetch_array($result)) {
                                                                        echo '
                                                                        <tr>
                                                                            <td>' . $row["Sequence"] . '</td>
                                                                            <td><a href="goods-history.php?prodcd=' . urlencode($row["GoodsCD"]) . '">' . $row["GoodsCD"] . '</a></td>
                                                                            <td>' . $row["GoodsName"] . '</td>
                                                                            <td>' . $row["StockQty"] . '</td>
                                                                            <td>' . $row["UnitCD"] . '</td>
                                                                            <td>' . $row["CategoryCD"] . '</td>
                                                                            <td> 
                                                                                <ul> 
                                                                                    <button onclick="viewGoods(this)" type="button" class="light-card border-primary border b-r-10" value="' . $row["GoodsCD"] . '">
                                                                                        <i class="fa fa-eye txt-primary"></i>
                                                                                    </button>';
                                                                        if ($can_update) {
                                                                            echo '<button onclick="editGoods(this)" type="button" class="light-card border-warning border b-r-10" value="' . $row["GoodsCD"] . '">
                                                                                <i class="icon-pencil-alt txt-warning"></i>
                                                                            </button>';
                                                                        }
                                                                        echo '</ul>
                                                                            </td>
                                                                        </tr>';
                                                                    }
                                                                    ?>
                                                                </tbody>

                                                            </table>

                                                            <script>
                                                                document.getElementById('status-filter').addEventListener('change', function () {
                                                                    var status = this.value;
                                                                    var xhr = new XMLHttpRequest();
                                                                    xhr.open('GET', '../Process/filter-active-SG.php?status=' + status, true);
                                                                    xhr.onload = function () {
                                                                        if (this.status == 200) {
                                                                            document.getElementById('material-table-body').innerHTML = this.responseText;
                                                                        }
                                                                    };
                                                                    xhr.send();
                                                                });
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Container-fluid Ends-->
                        </div>
                        <!-- footer start-->
                        <footer class="footer">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6 p-0 footer-copyright">
                                        <p class="mb-0">Copyright 2023 © Dunzo theme by pixelstrap.</p>
                                    </div>
                                    <div class="col-md-6 p-0">
                                        <p class="heart mb-0">Hand crafted &amp; made with
                                            <svg class="footer-icon">
                                                <use href="../../../assets/svg/icon-sprite.svg#heart"></use>
                                            </svg>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div>
                <!-- latest jquery-->
                <script src="../../../assets/js/jquery.min.js"></script>
                <!-- Bootstrap js-->
                <script src="../../../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
                <!-- feather icon js-->
                <script src="../../../assets/js/icons/feather-icon/feather.min.js"></script>
                <script src="../../../assets/js/icons/feather-icon/feather-icon.js"></script>
                <!-- scrollbar js-->
                <script src="../../../assets/js/scrollbar/simplebar.js"></script>
                <script src="../../../assets/js/scrollbar/custom.js"></script>
                <!-- Sidebar jquery-->
                <script src="../../../assets/js/config.js"></script>
                <!-- Plugins JS start-->
                <script src="../../../assets/js/sidebar-menu.js"></script>
                <script src="../../../assets/js/sidebar-pin.js"></script>
                <script src="../../../assets/js/slick/slick.min.js"></script>
                <script src="../../../assets/js/slick/slick.js"></script>
                <script src="../../../assets/js/header-slick.js"></script>
                <script src="../../../assets/js/form-validation-custom.js"></script>
                <script src="../../../assets/js/height-equal.js"></script>
                <script src="../../../assets/js/notify/bootstrap-notify.min.js"></script>
                <script src="../../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
                <script src="../../../assets/js/datatable/datatables/datatable.custom.js"></script>
                <script src="../../../assets/js/tooltip-init.js"></script>
                <script src="../../../assets/js/modalpage/validation-modal.js"></script>
                <!-- Plugins JS Ends-->
                <!-- Theme js-->
                <script src="../../../assets/js/script.js"></script>
                <!-- Plugin used-->
</body>

</html>