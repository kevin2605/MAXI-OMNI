<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";

    session_start();

    // Koneksi ke database
    include "../DBConnection.php"; // Sesuaikan dengan file koneksi database Anda
    
    // Ambil ID pengguna dari sesi atau cookie
    $userID = $_COOKIE['UserID']; // Sesuaikan dengan cara Anda menyimpan ID pengguna
    
    // Ambil akses level dari database
    $query = "SELECT BahanBaku FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Cek akses CRUD dan tentukan apakah akses diizinkan
    $hasCRUDAccess = strpos($row['BahanBaku'], 'C') !== false || // Create
        strpos($row['BahanBaku'], 'R') !== false || // Read
        strpos($row['BahanBaku'], 'U') !== false || // Update
        strpos($row['BahanBaku'], 'D') !== false;  // Delete
    
    // Jika tidak memiliki akses CRUD, tampilkan pesan dan redirect
    $accessDenied = !$hasCRUDAccess;
    ?>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Function to handle URL parameters
        function getQueryParams() {
            const query = window.location.search.substring(1);
            const params = new URLSearchParams(query);
            return {
                error: params.get('error')
            };
        }

        // Check URL parameters and show alert if needed
        window.addEventListener('DOMContentLoaded', (event) => {
            const params = getQueryParams();

            if (params.error === 'access_denied') {
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: 'Anda tidak memiliki akses untuk mengubah data bahan baku.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>

    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showProduct(str) {
            if (str == "") {
                document.getElementById("txtHint").innerHTML = "No data thrown!";
                return;
            } else {
                alert(str);
                /*var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (this.readyState==4 && this.status==200) {
                        document.getElementById("txtHint").innerHTML=this.responseText;
                    }
                }
                xmlhttp.open("GET","testJSPHP2.php?q="+str,true);
                xmlhttp.send();*/
            }
        }

        function viewMaterial(x) {
            document.location = "viewMaterial.php?matcd=" + x.value;
        }

        function editMaterial(x) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Bahan Baku dengan kode " + x.value + " akan diedit!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, setuju!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location = "edit-material.php?matcd=" + x.value;
                }
            });
        }

        /*function deleteMaterial(str) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Produk dengan kode " + str.value + " akan dihapus dari database!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, setuju!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location = "../Process/deleteMaterial.php?matcd="+str.value;
                }
            });
        }*/
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
        <!-- loader starts-->
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
                                    <p><b> Selamat! </b>Bahan Baku baru berhasil disimpan ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "error") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "success-edit") {
                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                        <p><b> Selamat! </b>Bahan Baku berhasil di edit dan disimpan ke database.</p>
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "error-edit") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                            <p><b> Error! </b>Terjadi kesalahan saat edit bahan baku ke database.</p>
                                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                                } else if ($_GET["status"] == "success-delete") {
                                    echo '<div class="alert txt-warning border-warning outline-2x alert-dismissible fade show alert-icons" role="alert">
                                            <p><b> Selamat! </b>Bahan Baku berhasil di hapus dari database.</p>
                                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                                } else if ($_GET["status"] == "error-delete") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Error! </b>Terjadi kesalahan saat delete bahan baku ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-6 ps-0">
                                    <h3>BAHAN BAKU</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Bahan Baku</li>
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
                                            <p><b> Selamat! </b>Bahan Baku baru berhasil disimpan ke database.</p>
                                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                                            } else if ($_GET["status"] == "error") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                            <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                                            } else if ($_GET["status"] == "success-edit") {
                                                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Selamat! </b>Bahan Baku berhasil di edit dan disimpan ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                                            } else if ($_GET["status"] == "error-edit") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Error! </b>Terjadi kesalahan saat edit bahan baku ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                                            } else if ($_GET["status"] == "success-delete") {
                                                echo '<div class="alert txt-warning border-warning outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Selamat! </b>Bahan Baku berhasil di hapus dari database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                                            } else if ($_GET["status"] == "error-delete") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Error! </b>Terjadi kesalahan saat delete bahan baku ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                                            }
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-6 ps-0">
                                                <h3>BAHAN BAKU</h3>
                                            </div>
                                            <div class="col-sm-6 pe-0">
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="index.html">
                                                            <svg class="stroke-icon">
                                                                <use
                                                                    href="../../assets/svg/icon-sprite.svg#stroke-home">
                                                                </use>
                                                            </svg></a></li>
                                                    <li class="breadcrumb-item">Bahan Baku</li>
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
                                                        $query_access = "SELECT BahanBaku FROM useraccesslevel WHERE UserID = '$userID'";
                                                        $result_access = mysqli_query($conn, $query_access);
                                                        if ($result_access) {
                                                            $row_access = mysqli_fetch_assoc($result_access);
                                                            $access_level = $row_access['BahanBaku'];
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
                                                                            Form
                                                                            Bahan
                                                                            Baku</h4>
                                                                        <button class="btn-close py-0" type="button"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body dark-modal">
                                                                        <div class="card-body custom-input">
                                                                            <form class="row g-3"
                                                                                action="../Process/createMaterial.php"
                                                                                method="POST">
                                                                                <div class="col-2">
                                                                                    <label class="form-label"
                                                                                        for="urutanreport">Urutan<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="urutanreport"
                                                                                        name="urutanreport" type="text"
                                                                                        placeholder="1" required>
                                                                                </div>
                                                                                <div class="col-4">
                                                                                    <label class="form-label"
                                                                                        for="kodebahan">Kode
                                                                                        Bahan<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="kodebahan" name="kodebahan"
                                                                                        type="text" placeholder="ABXXXX"
                                                                                        aria-label="Kode Produk"
                                                                                        required>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label class="form-label"
                                                                                        for="namabahan">Nama
                                                                                        Bahan<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="namabahan" name="namabahan"
                                                                                        type="text"
                                                                                        placeholder="barang contoh"
                                                                                        required>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="satuanpertama">Satuan
                                                                                        Pertama<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="satuanpertama"
                                                                                        list="satuanOptions"
                                                                                        name="satuanpertama"
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
                                                                                <div class="col-6">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="satuankedua">Satuan
                                                                                        Kedua<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="satuankedua"
                                                                                        list="satuanOptions"
                                                                                        name="satuankedua"
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
                                                                                        for="kategori">Kategori<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="kategori" name="kategori"
                                                                                        list="kategoriOptions"
                                                                                        placeholder="Kategori" required>
                                                                                    <datalist id="kategoriOptions">
                                                                                        <?php
                                                                                        $query = "SELECT CategoryCD,CategoryName FROM category WHERE Status='1'";
                                                                                        $result = mysqli_query($conn, $query);
                                                                                        while ($row = mysqli_fetch_array($result)) {
                                                                                            echo '<option value="' . $row["CategoryCD"] . '">' . $row["CategoryName"] . '</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="groupbarang">Group
                                                                                        Bahan<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="groupbarang" name="group"
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
                                                                                        for="gudang">Gudang<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="gudang" name="gudang"
                                                                                        list="gudangOptions"
                                                                                        placeholder="Gudang" required>
                                                                                    <datalist id="gudangOptions">
                                                                                        <?php
                                                                                        $query = "SELECT WarehCD,WarehName FROM warehouse WHERE Status='1'";
                                                                                        $result = mysqli_query($conn, $query);
                                                                                        while ($row = mysqli_fetch_array($result)) {
                                                                                            echo '<option value="' . $row["WarehCD"] . '">' . $row["WarehName"] . '</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="supplier">Supplier</label>
                                                                                    <input class="form-control"
                                                                                        id="supplier" name="supplier"
                                                                                        list="supplierOptions"
                                                                                        placeholder="Supplier">
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
                                                                                        for="produk">Produk</label>
                                                                                    <input class="form-control"
                                                                                        id="produk" name="produk"
                                                                                        list="produkOptions"
                                                                                        placeholder="Produk Jadi">
                                                                                    <datalist id="produkOptions">
                                                                                        <?php
                                                                                        $query = "SELECT ProductCD, ProductName FROM product WHERE Status='1'";
                                                                                        $result = mysqli_query($conn, $query);
                                                                                        while ($row = mysqli_fetch_array($result)) {
                                                                                            echo '<option value="' . $row["ProductCD"] . ' - ' . $row["ProductName"] . '"></option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
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
                                                                                    <label class="form-label"
                                                                                        for="avgprice">Harga
                                                                                        Avg</label>
                                                                                    <input class="form-control"
                                                                                        id="avgprice" name="avgprice"
                                                                                        type="text" placeholder="0"
                                                                                        readonly>
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
                                                                                <div class="col-6"></div>
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
                                                                                <div class="col-sm-4">
                                                                                    <!-- checked="" -->
                                                                                    <div
                                                                                        class="card-wrapper border rounded-3 checkbox-checked">
                                                                                        <h6 class="sub-title">Rules<span
                                                                                                style="color:red;">*</span>
                                                                                        </h6>
                                                                                        <label class="d-block"
                                                                                            for="chk-jual"></label>
                                                                                        <input class="checkbox_animated"
                                                                                            id="chk-jual"
                                                                                            name="rulesJual" value="1"
                                                                                            type="checkbox">Jual
                                                                                        <label class="d-block"
                                                                                            for="chk-beli"></label>
                                                                                        <input class="checkbox_animated"
                                                                                            id="chk-beli"
                                                                                            name="rulesBeli" value="1"
                                                                                            type="checkbox">Beli
                                                                                        <label class="d-block"
                                                                                            for="chk-produksi"></label>
                                                                                        <input class="checkbox_animated"
                                                                                            id="chk-produksi"
                                                                                            name="rulesProduksi"
                                                                                            value="1"
                                                                                            type="checkbox">Produksi
                                                                                        <label class="d-block"
                                                                                            for="chk-transaksi"></label>
                                                                                        <input class="checkbox_animated"
                                                                                            id="chk-transaksi"
                                                                                            name="rulesTransaksi"
                                                                                            value="1"
                                                                                            type="checkbox">Transaksi
                                                                                    </div>
                                                                                </div>
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
                                                                                                    name="produkStatus"
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
                                                                                                    name="produkStatus"
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
                                                            <div class="col-md-12" style="padding-top: 5px;">
                                                                <select id="status-filter" class="form-control">

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
                                                        <h3>Daftar Bahan Baku</h3>
                                                        <div class="table-responsive custom-scrollbar user-datatable">
                                                            <?php
                                                            include "../DBConnection.php";

                                                            // Ambil UserID dari cookie
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
                                                            ?>
                                                            <table class="display" id="basic-12">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Urutan</th>
                                                                        <th>Kode Bahan</th>
                                                                        <th>Nama Bahan</th>
                                                                        <th>Gudang</th>
                                                                        <th>Jumlah Stok</th>
                                                                        <th>Satuan</th>
                                                                        <th>Last Edit</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="material-table-body">
                                                                    <?php
                                                                    $status_filter = isset($_GET['status']) ? $_GET['status'] : '1';
                                                                    $query = "SELECT * FROM material";

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
                                                                        echo '
                                                                    </ul>
                                                                        </td>
                                                                    </tr>
                                                                    ';
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>

                                                            <script>
                                                                document.getElementById('status-filter').addEventListener('change', function () {
                                                                    var status = this.value;
                                                                    var xhr = new XMLHttpRequest();
                                                                    xhr.open('GET', '../Process/filter-active-material.php?status=' + status, true);
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
                                                <use href="../assets/svg/icon-sprite.svg#heart"></use>
                                            </svg>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div>
                <!-- latest jquery-->
                <script src="../../assets/js/jquery.min.js"></script>
                <!-- Bootstrap js-->
                <script src="../../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
                <!-- feather icon js-->
                <script src="../../assets/js/icons/feather-icon/feather.min.js"></script>
                <script src="../../assets/js/icons/feather-icon/feather-icon.js"></script>
                <!-- scrollbar js-->
                <script src="../../assets/js/scrollbar/simplebar.js"></script>
                <script src="../../assets/js/scrollbar/custom.js"></script>
                <!-- Sidebar jquery-->
                <script src="../../assets/js/config.js"></script>
                <!-- Plugins JS start-->
                <script src="../../assets/js/sidebar-menu.js"></script>
                <script src="../../assets/js/sidebar-pin.js"></script>
                <script src="../../assets/js/slick/slick.min.js"></script>
                <script src="../../assets/js/slick/slick.js"></script>
                <script src="../../assets/js/header-slick.js"></script>
                <script src="../../assets/js/form-validation-custom.js"></script>
                <script src="../../assets/js/notify/bootstrap-notify.min.js"></script>
                <script src="../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
                <script src="../../assets/js/datatable/datatables/datatable.custom.js"></script>
                <script src="../../assets/js/tooltip-init.js"></script>
                <script src="../../assets/js/modalpage/validation-modal.js"></script>
                <script src="../../assets/js/height-equal.js"></script>
                <!-- Plugins JS Ends-->

                <!-- Theme js-->
                <script src="../../assets/js/script.js"></script>
                <!-- Plugin used-->
</body>

</html>