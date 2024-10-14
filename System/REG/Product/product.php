<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";

    session_start();

    include "../../DBConnection.php";

    // Ambil ID pengguna dari sesi atau cookie
    $userID = $_COOKIE['UserID']; // Sesuaikan dengan cara Anda menyimpan ID pengguna
    
    // Ambil akses level dari database
    $query = "SELECT Produk FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Cek akses CRUD dan tentukan apakah akses diizinkan
    $hasCRUDAccess = strpos($row['Produk'], 'C') !== false || // Create
        strpos($row['Produk'], 'R') !== false || // Read
        strpos($row['Produk'], 'U') !== false || // Update
        strpos($row['Produk'], 'D') !== false;  // Delete
    
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
                    text: 'Anda tidak memiliki akses.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>

    <script>
        function viewProduct(x) {
            document.location = "viewProduct.php?prodcd=" + x.value;
        }

        function editProduct(x) {
            Swal.fire({
                title: "Edit Produk?",
                text: "Apakah anda yakin mengubah data dari " + x.value,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, setuju!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location = "edit-product.php?prodcd=" + x.value;
                }
            });
        }

        function showProduct(x) {
            var detail = false;

            if (x != "") {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("prodDetail").innerHTML = this.responseText;
                    }
                }
                detail = true;
                xmlhttp.open("GET", "../Process/getProdDetail.php?product=" + x, true);
                xmlhttp.send();
            } else if (x == "") {
                alert("Produk kosong!");
                return;
            }

            if (detail == true) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("flowHistory").innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("GET", "../Process/getProdFlowHist.php?product=" + x, true);
                xmlhttp.send();
            }
        }
    </script>

    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                                    <p><b> Selamat! </b>Produk baru berhasil disimpan ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "error") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "success-edit") {
                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                        <p><b> Selamat! </b>Produk berhasil di edit dan disimpan ke database.</p>
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "error-edit") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                        <p><b> Error! </b>Terjadi kesalahan saat edit produk ke database.</p>
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "success-delete") {
                                    echo '<div class="alert txt-warning border-warning outline-2x alert-dismissible fade show alert-icons" role="alert">
                                        <p><b> Warning! </b>Produk berhasil di hapus dari database.</p>
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "error-delete") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Error! </b>Terjadi kesalahan saat menghapus produk ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-6 ps-0">
                                    <h3>BARANG</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Barang</li>
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
                                                  <p><b> Selamat! </b>Produk baru berhasil disimpan ke database.</p>
                                                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                            } else if ($_GET["status"] == "error") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                 <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                                                 <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                             </div>';
                                            } else if ($_GET["status"] == "success-edit") {
                                                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                    <p><b> Selamat! </b>Produk berhasil di edit dan disimpan ke database.</p>
                                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                  </div>';
                                            } else if ($_GET["status"] == "error-edit") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                 <p><b> Error! </b>Terjadi kesalahan saat edit produk ke database.</p>
                                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                  </div>';
                                            } else if ($_GET["status"] == "success-delete") {
                                                echo '<div class="alert txt-warning border-warning outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                 <p><b> Warning! </b>Produk berhasil di hapus dari database.</p>
                                                 <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                               </div>';
                                            } else if ($_GET["status"] == "error-delete") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                 <p><b> Error! </b>Terjadi kesalahan saat menghapus produk ke database.</p>
                                                 <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                               </div>';
                                            }
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-6 ps-0">
                                                <h3>BARANG</h3>
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
                                                    <div class="col-md-3">
                                                        <?php
                                                        $canUpdate = false;
                                                        if (!empty($userID)) {
                                                            $query_access = "SELECT Produk FROM useraccesslevel WHERE UserID = '$userID'";
                                                            $result_access = mysqli_query($conn, $query_access);
                                                            if ($result_access) {
                                                                $row_access = mysqli_fetch_assoc($result_access);
                                                                $access_level = $row_access['Produk'];
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
                                                                            Form Produk
                                                                            Baru</h4>
                                                                        <button class="btn-close py-0" type="button"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body dark-modal">
                                                                        <div class="card-body custom-input">
                                                                            <form class="row g-3"
                                                                                action="../Process/createProduct.php"
                                                                                method="POST">
                                                                                <h5>Header Barang</h5>
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
                                                                                        for="kodeproduk">Kode
                                                                                        Produk<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="kodeproduk"
                                                                                        name="kodeproduk" type="text"
                                                                                        placeholder="ABXXXX"
                                                                                        aria-label="Kode Produk"
                                                                                        required>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label class="form-label"
                                                                                        for="namaproduk">Nama
                                                                                        Produk<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="namaproduk"
                                                                                        name="namaproduk" type="text"
                                                                                        placeholder="barang contoh"
                                                                                        required>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="satuan">Satuan<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="satuan" list="satuanOptions"
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
                                                                                <div class="col-6">
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
                                                                                <div class="col-6">
                                                                                    <label
                                                                                        class="col-sm-12 col-form-label"
                                                                                        for="groupbarang">Group
                                                                                        Barang<span
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
                                                                                <div class="col-6">
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
                                                                                <div class="col-6">
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
                                                                                <hr>
                                                                                <ul class="simple-wrapper nav nav-tabs"
                                                                                    id="myTab" role="tablist">
                                                                                    <li class="nav-item"><a
                                                                                            class="nav-link active txt-primary"
                                                                                            id="home-tab"
                                                                                            data-bs-toggle="tab"
                                                                                            href="#home" role="tab"
                                                                                            aria-controls="home"
                                                                                            aria-selected="true">Detail</a>
                                                                                    </li>
                                                                                    <li class="nav-item"><a
                                                                                            class="nav-link txt-primary"
                                                                                            id="profile-tabs"
                                                                                            data-bs-toggle="tab"
                                                                                            href="#profile" role="tab"
                                                                                            aria-controls="profile"
                                                                                            aria-selected="false">Komponen</a>
                                                                                    </li>
                                                                                </ul>
                                                                                <div class="tab-content"
                                                                                    id="myTabContent">
                                                                                    <div class="tab-pane fade show active"
                                                                                        id="home" role="tabpanel"
                                                                                        aria-labelledby="home-tab">
                                                                                        <div class="row g-3">

                                                                                            <div class="col-4">
                                                                                                <label
                                                                                                    class="form-label"
                                                                                                    for="pcsperdos">Pcs/dos</label>
                                                                                                <input
                                                                                                    class="form-control"
                                                                                                    id="pcsperdos"
                                                                                                    name="pcsperdos"
                                                                                                    type="text"
                                                                                                    placeholder="0"
                                                                                                    aria-label="Kode Produk"
                                                                                                    required>
                                                                                            </div>
                                                                                            <h5>Dimensi Dus<span
                                                                                                    style="color:red;">*</span>
                                                                                            </h5>
                                                                                            <div class="col-4">
                                                                                                <label
                                                                                                    class="form-label"
                                                                                                    for="boxpanjang">Panjang</label>
                                                                                                <input
                                                                                                    class="form-control"
                                                                                                    id="boxpanjang"
                                                                                                    name="boxpanjang"
                                                                                                    type="text"
                                                                                                    placeholder="0"
                                                                                                    aria-label="Kode Produk"
                                                                                                    required>
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <label
                                                                                                    class="form-label"
                                                                                                    for="boxlebar">Lebar</label>
                                                                                                <input
                                                                                                    class="form-control"
                                                                                                    id="boxlebar"
                                                                                                    name="boxlebar"
                                                                                                    type="text"
                                                                                                    placeholder="0"
                                                                                                    aria-label="Kode Produk"
                                                                                                    required>
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <label
                                                                                                    class="form-label"
                                                                                                    for="boxtinggi">Tinggi</label>
                                                                                                <input
                                                                                                    class="form-control"
                                                                                                    id="boxtinggi"
                                                                                                    name="boxtinggi"
                                                                                                    type="text"
                                                                                                    placeholder="0"
                                                                                                    aria-label="Kode Produk"
                                                                                                    required>
                                                                                            </div>
                                                                                            <div class="col-sm-4">
                                                                                                <!-- checked="" -->
                                                                                                <div
                                                                                                    class="card-wrapper border rounded-3 checkbox-checked">
                                                                                                    <h6
                                                                                                        class="sub-title">
                                                                                                        Rules<span
                                                                                                            style="color:red;">*</span>
                                                                                                    </h6>
                                                                                                    <label
                                                                                                        class="d-block"
                                                                                                        for="chk-jual"></label>
                                                                                                    <input
                                                                                                        class="checkbox_animated"
                                                                                                        id="chk-jual"
                                                                                                        name="rulesJual"
                                                                                                        value="1"
                                                                                                        type="checkbox">Jual
                                                                                                    <label
                                                                                                        class="d-block"
                                                                                                        for="chk-beli"></label>
                                                                                                    <input
                                                                                                        class="checkbox_animated"
                                                                                                        id="chk-beli"
                                                                                                        name="rulesBeli"
                                                                                                        value="1"
                                                                                                        type="checkbox">Beli
                                                                                                    <label
                                                                                                        class="d-block"
                                                                                                        for="chk-produksi"></label>
                                                                                                    <input
                                                                                                        class="checkbox_animated"
                                                                                                        id="chk-produksi"
                                                                                                        name="rulesProduksi"
                                                                                                        value="1"
                                                                                                        type="checkbox">Produksi
                                                                                                    <label
                                                                                                        class="d-block"
                                                                                                        for="chk-transaksi"></label>
                                                                                                    <input
                                                                                                        class="checkbox_animated"
                                                                                                        id="chk-transaksi"
                                                                                                        name="rulesTransaksi"
                                                                                                        value="1"
                                                                                                        type="checkbox">Transaksi
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <div
                                                                                                    class="card-wrapper border rounded-3 checkbox-checked">
                                                                                                    <h6
                                                                                                        class="sub-title">
                                                                                                        Status?<span
                                                                                                            style="color:red;">*</span>
                                                                                                    </h6>
                                                                                                    <div
                                                                                                        class="radio-form">
                                                                                                        <div
                                                                                                            class="form-check">
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
                                                                                                        <div
                                                                                                            class="form-check">
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
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="tab-pane fade show"
                                                                                        id="profile" role="tabpanel">
                                                                                        <table id="dinamis"
                                                                                            class="table">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th scope="col">
                                                                                                        Produk</th>
                                                                                                    <th scope="col">
                                                                                                        Jumlah</th>
                                                                                                    <th scope="col">
                                                                                                        Satuan</th>
                                                                                                    <th scope="col">
                                                                                                        Action</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody id="dbody">
                                                                                                <tr id="row1">
                                                                                                    <td>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            class="form-control prodlist"
                                                                                                            name="products[]"
                                                                                                            list="namelist">
                                                                                                        <datalist
                                                                                                            id="namelist"
                                                                                                            style="width:3rem;">
                                                                                                            <option
                                                                                                                value="PC4">
                                                                                                                Paper
                                                                                                                Cup 4
                                                                                                                oz
                                                                                                            </option>
                                                                                                            <option
                                                                                                                value="PC7">
                                                                                                                Paper
                                                                                                                Cup 7
                                                                                                                oz
                                                                                                            </option>
                                                                                                        </datalist>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            class="form-control"
                                                                                                            name="quantities[]"
                                                                                                            placeholder="0">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            class="form-control"
                                                                                                            name="discs[]"
                                                                                                            placeholder="0">
                                                                                                    </td>
                                                                                                    <td>

                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
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
                                                                                            informasi
                                                                                            diatas sudah benar?</label>
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
                                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">Menu</button>
                                                        <ul class="dropdown-menu dropdown-block" id="myTab"
                                                            role="tablist">
                                                            <li class="nav-item"><a
                                                                    class="dropdown-item active txt-primary f-w-500 f-18"
                                                                    id="home-tab" data-bs-toggle="tab"
                                                                    href="#daftarBarang" role="tab" aria-controls="home"
                                                                    aria-selected="true">Daftar
                                                                    Produk</a></li>
                                                        </ul>
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
                                                    <div class="tab-pane fade show active" id="daftarproduk"
                                                        role="tabpanel">
                                                        <h3>Daftar Produk</h3>
                                                        <div class="table-responsive custom-scrollbar user-datatable">
                                                            <table class="display" id="basic-12">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Urutan Report</th>
                                                                        <th>Kode Produk</th>
                                                                        <th>Nama Produk</th>
                                                                        <th>Jumlah Stok</th>

                                                                        <th>Group</th>
                                                                        <th>Last Edit</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="produk-table-body">
                                                                    <?php
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
                                                                    ?>
                                                                    <?php
                                                                    $status_filter = isset($_GET['status']) ? $_GET['status'] : '1';
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
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                            <script>
                                                                document.getElementById('status-filter').addEventListener('change', function () {
                                                                    var status = this.value;
                                                                    var xhr = new XMLHttpRequest();
                                                                    xhr.open('GET', '../Process/filter-active-produk.php?status=' + status, true);
                                                                    xhr.onload = function () {
                                                                        if (this.status == 200) {
                                                                            document.getElementById('produk-table-body').innerHTML = this.responseText;
                                                                        }
                                                                    };
                                                                    xhr.send();
                                                                });
                                                            </script>
                                                            </footer>
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
                <script src="../../../assets/js/notify/bootstrap-notify.min.js"></script>
                <script src="../../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
                <script src="../../../assets/js/datatable/datatables/datatable.custom.js"></script>
                <script src="../../../assets/js/tooltip-init.js"></script>
                <script src="../../../assets/js/modalpage/validation-modal.js"></script>
                <script src="../../../assets/js/height-equal.js"></script>
                <!-- Plugins JS Ends-->

                <!-- Sweetalert2 -->
                <script>

                </script>
                <!-- end sweetaler2 -->

                <!-- DYNAMIC TABLE -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
                <script>
                    $("document").ready(function () {
                        var i = 1;

                        $(document).on('input', '.prodlist', function () {
                            i++;
                            $('#dinamis #dbody').append('<tr id="row' + i + '"><td><input type="text" class="form-control prodlist" onselect="myFunction()" name="products[]" list="namelist"><datalist id="namelist" style="width:3rem;"><option value="PC4">Paper Cup 4 oz</option><option value="PC7">Paper Cup 7 oz</option></datalist></td><td><input type="text" class="form-control" name="quantities[]" placeholder="0"></td><td><input type="text" class="form-control" name="discs[]" placeholder="0"></td><td><button id="' + i + '" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button></td></tr>');
                        });

                        $(document).on('click', '.bremove', function () {
                            i--;
                            var button_id = $(this).attr("id");
                            $('#row' + button_id + '').remove();
                        });
                    });
                </script>
                <!-- Theme js-->
                <script src="../../../assets/js/script.js"></script>
                <!-- Plugin used-->
</body>

</html>