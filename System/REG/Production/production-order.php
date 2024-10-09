<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    session_start();
    include "../DBConnection.php";
    $userID = $_COOKIE['UserID'];

    $query = "SELECT pSPK FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $hasCRUDAccess = strpos($row['pSPK'], 'C') !== false || // Create
        strpos($row['pSPK'], 'R') !== false || // Read
        strpos($row['pSPK'], 'U') !== false || // Update
        strpos($row['pSPK'], 'D') !== false;  // Delete
    
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
                    text: 'Anda tidak memiliki akses untuk edit SPK.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi untuk mendapatkan parameter dari URL
        function getQueryParams() {
            const query = window.location.search.substring(1);
            const params = new URLSearchParams(query);
            return {
                status: params.get('status')
            };
        }

        // Periksa parameter URL dan tampilkan pesan jika diperlukan
        window.addEventListener('DOMContentLoaded', (event) => {
            const params = getQueryParams();


        });
    </script>


    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function getMatStock(material) {
            var matcd = material.value.split(" - ");
            var max = 0;
            $.ajax({
                type: "POST",
                url: "../Process/getMatStock.php",
                data: "matcd=" + matcd[0],
                success: function (result) {
                    var res = JSON.parse(result);
                    $.each(res, function (index, value) {
                        max = value.StockQty;
                        document.getElementById("stok").value = value.StockQty;
                        document.getElementById("unit").value = value.UnitCD_2;
                    });
                    document.getElementById("flowout").setAttribute("max", max);
                }
            });

            getMatProduct(matcd[0]);
        }

        function getMatProduct(str) {
            $.ajax({
                type: "POST",
                url: "../Process/getMatProduct.php",
                data: "matcd=" + str,
                success: function (result) {
                    var res = JSON.parse(result);
                    $.each(res, function (index, value) {
                        document.getElementById("produk").value = value.ProductCD;
                    });
                }
            });
        }
        function viewProdHist(x) {
            document.location = "view-production-history.php?spk=" + x.value;
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
                        window.location.href = '../Dashboard/';
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
                            <div class="row">
                                <div class="col-sm-6 ps-0">
                                    <h3>SPK PRODUKSI</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Produksi</li>
                                        <li class="breadcrumb-item">SPK Produksi</li>
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
                                                        <p><b> Selamat! </b>SPK Baru berhasil disimpan ke database.</p>
                                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                            } else if ($_GET["status"] == "error") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                        <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                            } else if ($_GET["status"] == "limit-exceeded") {
                                                echo '<div class="alert txt-warning border-warning outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                        <p><b> Perhatian! </b>Telah mencapai batas maksimal SPK dengan Produk yang sama.</p>
                                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                            }
                                        }
                                        ?>


                                        <div class="row">
                                            <div class="col-sm-6 ps-0">
                                                <h3>SPK PRODUKSI</h3>
                                            </div>
                                            <div class="col-sm-6 pe-0">
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="index.html">
                                                            <svg class="stroke-icon">
                                                                <use
                                                                    href="../../assets/svg/icon-sprite.svg#stroke-home">
                                                                </use>
                                                            </svg></a></li>
                                                    <li class="breadcrumb-item">Produksi</li>
                                                    <li class="breadcrumb-item">SPK Produksi</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <?php
                                                $hasAccess = false;
                                                $userID = isset($_COOKIE["UserID"]) ? $_COOKIE["UserID"] : '';

                                                if (!empty($userID)) {
                                                    $query_access = "SELECT pSPK FROM useraccesslevel WHERE UserID = '$userID'";
                                                    $result_access = mysqli_query($conn, $query_access);

                                                    if ($result_access) {
                                                        $row_access = mysqli_fetch_assoc($result_access);
                                                        $access_level = $row_access['pSPK'];
                                                        // Periksa apakah 'C' ada dalam string akses
                                                        if (strpos($access_level, 'C') !== false) {
                                                            $hasAccess = true;
                                                        }
                                                    } else {
                                                        die("Error: Gagal mengambil data akses pengguna.");
                                                    }
                                                } else {
                                                    die("Error: Cookie 'UserID' tidak ada atau kosong.");
                                                }
                                                ?>
                                                <button class="btn btn-outline-primary" type="button" <?php echo $hasAccess ? 'data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg1"' : 'disabled'; ?>>
                                                    <i class="fa fa-plus-circle"></i> New
                                                </button>
                                                <div class="modal fade bd-example-modal-lg1" tabindex="-1" role="dialog"
                                                    aria-labelledby="myExtraLargeModal" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myExtraLargeModal">Form SPK
                                                                    Baru</h4>
                                                                <button class="btn-close py-0" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body dark-modal">
                                                                <div class="card-body custom-input">
                                                                    <form class="row g-3"
                                                                        action="../Process/createProdOrder.php"
                                                                        method="POST">
                                                                        <div class="col-6">
                                                                            <label class="form-label"
                                                                                for="machinecd">Nomor SPK<span
                                                                                    style="color:red;">*</span></label>
                                                                            <input class="form-control" id="machinecd"
                                                                                name="machinecd" type="text"
                                                                                value="auto-generate" readonly>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label class="form-label"
                                                                                for="machinename">Tanggal</label>
                                                                            <input class="form-control" id="machinename"
                                                                                name="machinename" type="date"
                                                                                value="<?php echo date('Y-m-d'); ?>"
                                                                                readonly>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="form-label"
                                                                                for="desc">Keterangan</label>
                                                                            <textarea class="form-control" id="desc"
                                                                                name="desc" rows="3"
                                                                                required></textarea>
                                                                        </div>
                                                                        <hr>
                                                                        <ul class="simple-wrapper nav nav-tabs"
                                                                            id="myTab" role="tablist">
                                                                            <li class="nav-item"><a
                                                                                    class="nav-link active txt-primary"
                                                                                    id="home-tab" data-bs-toggle="tab"
                                                                                    href="#bahan" role="tab"
                                                                                    aria-controls="home"
                                                                                    aria-selected="true">Bahan</a></li>
                                                                            <li class="nav-item"><a
                                                                                    class="nav-link txt-primary"
                                                                                    id="profile-tabs"
                                                                                    data-bs-toggle="tab" href="#mesin"
                                                                                    role="tab" aria-controls="profile"
                                                                                    aria-selected="false">Mesin</a></li>
                                                                        </ul>
                                                                        <div class="tab-content" id="myTabContent">
                                                                            <div class="tab-pane fade show active"
                                                                                id="bahan" role="tabpanel"
                                                                                aria-labelledby="home-tab">
                                                                                <div class="row custom-input">
                                                                                    <div class="row">
                                                                                        <div class="col">
                                                                                            <div class="mb-3 row">
                                                                                                <div class="col-sm-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="bahan">Bahan
                                                                                                        Baku<span
                                                                                                            style="color:red;">*</span></label>
                                                                                                </div>
                                                                                                <div class="col-sm-9">
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        id="bahan"
                                                                                                        name="bahan"
                                                                                                        list="bahanOptions"
                                                                                                        placeholder="-- Pilih Bahan --"
                                                                                                        onchange="getMatStock(this)"
                                                                                                        required>
                                                                                                    <datalist
                                                                                                        id="bahanOptions">
                                                                                                        <?php
                                                                                                        $queryc = "SELECT MaterialCD, MaterialName FROM material WHERE substr(MaterialCD,1,3) != 'BTM'";
                                                                                                        $resultc = mysqli_query($conn, $queryc);
                                                                                                        while ($rowc = mysqli_fetch_array($resultc)) {
                                                                                                            echo '<option value="' . $rowc["MaterialCD"] . ' - ' . $rowc["MaterialName"] . '"></option>';
                                                                                                        }
                                                                                                        ?>
                                                                                                    </datalist>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="mb-3 row">
                                                                                                <label class="col-sm-3"
                                                                                                    for="stok">Stok
                                                                                                    Tersedia</label>
                                                                                                <div class="col-sm-9">
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        id="stok"
                                                                                                        name="stok"
                                                                                                        type="text"
                                                                                                        placeholder="0"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="mb-3 row">
                                                                                                <label class="col-sm-3"
                                                                                                    for="unit">Satuan</label>
                                                                                                <div class="col-sm-9">
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        id="unit"
                                                                                                        name="unit"
                                                                                                        type="text"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="mb-3 row">
                                                                                                <label class="col-sm-3"
                                                                                                    for="flowout">Bahan
                                                                                                    Keluar<span
                                                                                                        style="color:red;">*</span></label>
                                                                                                <div class="col-sm-9">
                                                                                                    <input
                                                                                                        class="form-control digits"
                                                                                                        id="flowout"
                                                                                                        name="flowout"
                                                                                                        type="number"
                                                                                                        placeholder="0"
                                                                                                        required>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="mb-3 row">
                                                                                                <div class="col-sm-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="produk">Produk
                                                                                                        Jadi<span
                                                                                                            style="color:red;">*</span></label>
                                                                                                </div>
                                                                                                <div class="col-sm-9">
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        id="produk"
                                                                                                        name="produk"
                                                                                                        placeholder="-"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="mb-3 row">
                                                                                                <label class="col-sm-3"
                                                                                                    for="estimate">Estimasi
                                                                                                    Hasil<span
                                                                                                        style="color:red;">*</span></label>
                                                                                                <div class="col-sm-9">
                                                                                                    <input
                                                                                                        class="form-control digits"
                                                                                                        id="estimate"
                                                                                                        name="estimate"
                                                                                                        type="number"
                                                                                                        placeholder="0"
                                                                                                        required>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade show" id="mesin"
                                                                                role="tabpanel">
                                                                                <div class="row custom-input">
                                                                                    <div class="row">
                                                                                        <div class="col">
                                                                                            <div class="mb-3 row">
                                                                                                <div class="col-sm-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="machine">Mesin<span
                                                                                                            style="color:red;">*</span></label>
                                                                                                </div>
                                                                                                <div class="col-sm-9">
                                                                                                    <input
                                                                                                        class="form-control"
                                                                                                        id="machine"
                                                                                                        name="machine"
                                                                                                        list="macOptions"
                                                                                                        placeholder="-- Pilih Mesin --"
                                                                                                        required>
                                                                                                    <datalist
                                                                                                        id="macOptions">
                                                                                                        <?php
                                                                                                        $queryc = "SELECT MachineCD, MachineName FROM machine";
                                                                                                        $resultc = mysqli_query($conn, $queryc);
                                                                                                        while ($rowc = mysqli_fetch_array($resultc)) {
                                                                                                            echo '<option value="' . $rowc["MachineCD"] . ' - ' . $rowc["MachineName"] . '"></option>';
                                                                                                        }
                                                                                                        ?>
                                                                                                    </datalist>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="col-12">
                                                                            <div class="form-check form-switch">
                                                                                <input class="form-check-input"
                                                                                    id="flexSwitchCheckDefault"
                                                                                    type="checkbox" role="switch"
                                                                                    required>
                                                                                <label class="form-check-label"
                                                                                    for="flexSwitchCheckDefault">Are you
                                                                                    sure above
                                                                                    information are true</label>
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
                                                    data-bs-toggle="dropdown" aria-expanded="false">Menu</button>
                                                <ul class="dropdown-menu dropdown-block" id="myTab" role="tablist">
                                                    <li class="nav-item"><a
                                                            class="dropdown-item active txt-primary f-w-500 f-18"
                                                            id="home-tab" data-bs-toggle="tab" href="#spkproses"
                                                            role="tab" aria-controls="home" aria-selected="true">SPK
                                                            Proses</a></li>
                                                    <li class="nav-item"><a
                                                            class="dropdown-item txt-primary f-w-500 f-18" id="home-tab"
                                                            data-bs-toggle="tab" href="#spkselesai" role="tab"
                                                            aria-controls="home" aria-selected="true">SPK Selesai</a>
                                                    </li>
                                                </ul>
                                                <hr>
                                                <div class="tab-content" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="spkproses"
                                                        role="tabpanel">
                                                        <h3>Daftar SPK</h3><small>Status : Proses</small>
                                                        <br><br>
                                                        <div class="table-responsive custom-scrollbar user-datatable">
                                                            <table class="display" id="basic-12">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-2">No. SPK</th>
                                                                        <th class="col-2">Tanggal</th>
                                                                        <th class="col-1">Mesin</th>
                                                                        <th class="col-3">Bahan Baku</th>
                                                                        <th class="col-1">Keluar</th>
                                                                        <th class="col-1">Satuan</th>
                                                                        <th class="col-1">Status</th>
                                                                        <th class="col-1">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
                                                                        $creator = $_COOKIE["UserID"];
                                                                    } else {
                                                                        die("Error: Cookie 'UserID' tidak ada atau kosong.");
                                                                    }
                                                                    $query_access = "SELECT pSPK FROM useraccesslevel WHERE UserID = '$creator'";
                                                                    $result_access = mysqli_query($conn, $query_access);
                                                                    $can_update = false;
                                                                    if ($result_access) {
                                                                        $row_access = mysqli_fetch_assoc($result_access);
                                                                        $access_level = $row_access['pSPK'];
                                                                        if (strpos($access_level, 'R') !== false) {
                                                                            $can_update = true;
                                                                        }
                                                                    } else {
                                                                        die("Error: Gagal mengambil data akses pengguna.");
                                                                    }
                                                                    $query = "SELECT p.ProductionOrderID, p.CreatedOn, m.MachineName, mt.MaterialName, p.MaterialOut, p.UnitCD, p.Status
                                                                    FROM productionorder p, machine m, material mt
                                                                    WHERE p.Status='0'
                                                                        AND p.MachineCD=m.MachineCD
                                                                        AND p.MaterialCD=mt.MaterialCD";
                                                                    $result = mysqli_query($conn, $query);
                                                                    while ($row = mysqli_fetch_array($result)) {
                                                                        echo '
                                                                <tr>
                                                                    <td class="col-2">' . $row["ProductionOrderID"] . '</td>
                                                                    <td class="col-2">' . $row["CreatedOn"] . '</td>
                                                                    <td class="col-1">' . $row["MachineName"] . '</td>
                                                                    <td class="col-3">' . $row["MaterialName"] . '</td>
                                                                    <td class="col-1">' . $row["MaterialOut"] . '</td>
                                                                    <td class="col-1">' . $row["UnitCD"] . '</td>
                                                                    <td class="col-1"><span class="badge badge-light-warning">Proses</span></td>
                                                                    <td> ';
                                                                        if ($can_update) {
                                                                            echo '<button onclick="viewProdHist(this)" type="button" class="light-card border-primary border b-r-10" value="' . $row["ProductionOrderID"] . '"><i class="fa fa-eye txt-primary"></i></button>';
                                                                        }
                                                                        echo ' 
                                                                        
                                                                    </td>        
                                                                </tr>
                                                            ';
                                                                    }

                                                                    ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade show" id="spkselesai" role="tabpanel">
                                                        <h3>Daftar SPK</h3><small>Status : Selesai</small>
                                                        <br><br>
                                                        <div class="table-responsive custom-scrollbar user-datatable">
                                                            <table class="display" id="basic-1">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-2">No. SPK</th>
                                                                        <th class="col-2">Tanggal</th>
                                                                        <th class="col-1">Mesin</th>
                                                                        <th class="col-3">Bahan Baku</th>
                                                                        <th class="col-1">Keluar</th>
                                                                        <th class="col-1">Satuan</th>
                                                                        <th class="col-1">Status</th>
                                                                        <th class="col-1">Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $query = "SELECT p.ProductionOrderID, p.CreatedOn, m.MachineName, mt.MaterialName, p.MaterialOut, p.UnitCD, p.Status
                                                                            FROM productionorder p, machine m, material mt
                                                                            WHERE p.Status='1'
                                                                                AND p.MachineCD=m.MachineCD
                                                                                AND p.MaterialCD=mt.MaterialCD";
                                                                    $result = mysqli_query($conn, $query);
                                                                    while ($row = mysqli_fetch_array($result)) {
                                                                        echo '
                                                                        <tr>
                                                                            <td class="col-2">' . $row["ProductionOrderID"] . '</td>
                                                                            <td class="col-2">' . $row["CreatedOn"] . '</td>
                                                                            <td class="col-1">' . $row["MachineName"] . '</td>
                                                                            <td class="col-3">' . $row["MaterialName"] . '</td>
                                                                            <td class="col-1">' . $row["MaterialOut"] . '</td>
                                                                            <td class="col-1">' . $row["UnitCD"] . '</td>
                                                                            <td class="col-1"><span class="badge badge-light-success">Selesai</span></td>
                                                                            <td> 
                                                                                <button onclick="viewProdHist(this)" type="button" class="light-card border-primary border b-r-10" value="' . $row["ProductionOrderID"] . '"><i class="fa fa-eye txt-primary"></i></button>
                                                                            </td>        
                                                                        </tr>
                                                                    ';
                                                                    }

                                                                    ?>
                                                            </table>
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
                                                <use href="../../assets/svg/icon-sprite.svg#heart"></use>
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
                <script src="../../assets/js/height-equal.js"></script>
                <script src="../../assets/js/notify/bootstrap-notify.min.js"></script>
                <script src="../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
                <script src="../../assets/js/datatable/datatables/datatable.custom.js"></script>
                <script src="../../assets/js/tooltip-init.js"></script>
                <script src="../../assets/js/modalpage/validation-modal.js"></script>
                <!-- Plugins JS Ends-->
                <!-- Theme js-->
                <script src="../../assets/js/script.js"></script>
                <!-- Plugin used-->
</body>

</html>