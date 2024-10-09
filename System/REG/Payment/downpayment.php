<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    session_start();
    include "../DBConnection.php";
    $userID = $_COOKIE['UserID'];

    $query = "SELECT dp FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $hasCRUDAccess = strpos($row['dp'], 'C') !== false || // Create
        strpos($row['dp'], 'R') !== false || // Read
        strpos($row['dp'], 'U') !== false || // Update
        strpos($row['dp'], 'D') !== false;  // Delete
    
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
                    text: 'Anda tidak memiliki akses untuk mengubah NPWP .',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
    <style>
        .hidden {
            display: none;
        }
    </style>

</head>

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
                            <div class="row">
                                <div class="col-sm-6 ps-0">
                                    <h3>NOMOR SERI PAJAK</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Pajak</li>
                                        <li class="breadcrumb-item">Nomor Seri Pajak</li>
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
                                                <p><b> Selamat! </b>DP baru berhasil disimpan ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                            } else if ($_GET["status"] == "error") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                            } else if ($_GET["status"] == "success-edit") {
                                                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                    <p><b> Selamat! </b>DP berhasil di edit dan disimpan ke database.</p>
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
                                                <h3>UANG MUKA</h3>
                                            </div>
                                            <div class="col-sm-6 pe-0">
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="index.html">
                                                            <svg class="stroke-icon">
                                                                <use
                                                                    href="../../assets/svg/icon-sprite.svg#stroke-home">
                                                                </use>
                                                            </svg></a></li>
                                                    <li class="breadcrumb-item">Uang Muka</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex">
                                                        <?php
                                                        $canUpdate = false;
                                                        if (!empty($userID)) {
                                                            $query_access = "SELECT dp FROM useraccesslevel WHERE UserID = '$userID'";
                                                            $result_access = mysqli_query($conn, $query_access);
                                                            if ($result_access) {
                                                                $row_access = mysqli_fetch_assoc($result_access);
                                                                $access_level = $row_access['dp'];
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
                                                        <button class="btn btn-outline-primary" type="button" <?php echo !$canUpdate ? 'disabled' : 'data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg"'; ?>>
                                                            <i class="fa fa-plus-circle"></i> New
                                                        </button>
                                                        <div class="modal fade bd-example-modal-lg" tabindex="-1"
                                                            role="dialog" aria-labelledby="myExtraLargeModal"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="myExtraLargeModal">
                                                                            Uang Muka Baru</h4>
                                                                        <button class="btn-close py-0" type="button"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body dark-modal">
                                                                        <div class="card-body custom-input">
                                                                            <form class="row g-3"
                                                                                action="../Process/createdownpayment.php"
                                                                                method="POST">
                                                                                <div class="col-3">
                                                                                    <label class="form-label"
                                                                                        for="exampleFormControlInput1">Tanggal</label>
                                                                                    <input class="form-control"
                                                                                        id="exampleFormControlInput1"
                                                                                        type="date"
                                                                                        value="<?php echo date('Y-m-d'); ?>"
                                                                                        readonly>
                                                                                </div>
                                                                                <div class="col-9">
                                                                                    <label class="form-label"
                                                                                        for="salesorders">Pilih
                                                                                        Sales Order</label>
                                                                                    <input class="form-control"
                                                                                        id="salesorders"
                                                                                        name="salesorder"
                                                                                        list="soOptions"
                                                                                        placeholder="Pilih Sales Order --"
                                                                                        required>
                                                                                    <datalist id="soOptions">
                                                                                        <?php
                                                                                        $query = "SELECT soh.SalesOrderID, c.CustName 
                                                                                                    FROM salesorderheader soh, customer c 
                                                                                                    WHERE soh.ApprovalStatus='Approved' 
                                                                                                            AND soh.Finish=0 
                                                                                                            AND soh.CustID=c.CustID
                                                                                                            ORDER BY soh.SalesOrderID";
                                                                                        $result = mysqli_query($conn, $query);
                                                                                        while ($row = mysqli_fetch_array($result)) {
                                                                                            echo '<option value="' . $row["SalesOrderID"] . '"></option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
                                                                                </div>
                                                                                <div class="col-4">
                                                                                    <label class="form-label"
                                                                                        for="amount">Jumlah DP</label>
                                                                                    <input class="form-control"
                                                                                        id="amount" name="amount"
                                                                                        placeholder="Rp." required
                                                                                        oninput="formatRupiah(this)"
                                                                                        onfocus="removeFormat(this)"
                                                                                        onblur="formatRupiah(this)">
                                                                                </div>
                                                                                <script>
                                                                                    function formatRupiah(element) {
                                                                                        let angka = element.value.replace(/[^,\d]/g, ''); // Hanya ambil angka dan koma
                                                                                        let split = angka.split(',');
                                                                                        let ribuan = split[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format ribuan
                                                                                        let hasil = split[1] != undefined ? ribuan + ',' + split[1] : ribuan; // Format desimal jika ada
                                                                                        element.value = 'Rp. ' + hasil; // Tambahkan simbol Rp
                                                                                    }

                                                                                    function removeFormat(element) {
                                                                                        let angka = element.value.replace(/[^\d]/g, ''); // Hapus format dan simpan angka
                                                                                        element.value = angka;
                                                                                    }
                                                                                </script>
                                                                                <div class="col-12">
                                                                                    <label class="form-label"
                                                                                        for="desc">Keterangan</label>
                                                                                    <input class="form-control"
                                                                                        id="desc" name="desc"
                                                                                        type="text">
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <button class="btn btn-primary"
                                                                                        type="submit"
                                                                                        name="submitInv">Submit</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <br>
                                                <h3>Daftar Uang Muka</h3>
                                                <div class="table-responsive custom-scrollbar user-datatable">
                                                    <table class="display" id="basic-12">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="2">ID</th>
                                                                <th rowspan="2">Tanggal</th>
                                                                <th rowspan="2">Nomor Sales Order
                                                                </th>
                                                                <th rowspan="2">Keterangan</th>
                                                                <th rowspan="2">Jumlah DP</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id="unit-table-body">
                                                            <?php
                                                            $query = "
                                                                SELECT 
                                                                    dh.DPID,
                                                                    dh.CreatedOn AS tanggal,
                                                                    dh.SalesOrderID AS nomorSalesOrder,
                                                                    dh.Description AS keterangan,
                                                                    dd.Amount AS jumlahDP
                                                                FROM 
                                                                    downpaymentheader dh
                                                                JOIN 
                                                                    downpaymentdetail dd ON dh.DPID = dd.DPID
                                                                ORDER BY 
                                                                    dh.CreatedOn DESC
                                                            ";
                                                            $result = mysqli_query($conn, $query);
                                                            if (!$result) {
                                                                die('Query Error: ' . mysqli_error($conn));
                                                            }
                                                            ?>
                                                            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                                                <tr>
                                                                    <td style="padding-left:18px;">
                                                                        <?php echo htmlspecialchars($row['DPID']); ?>
                                                                    </td>
                                                                    <td style="padding-left:18px;">
                                                                        <?php echo htmlspecialchars($row['tanggal']); ?>
                                                                    </td>
                                                                    <td style="padding-left:18px;">
                                                                        <?php echo htmlspecialchars($row['nomorSalesOrder']); ?>
                                                                    </td>
                                                                    <td style="padding-left:18px;">
                                                                        <?php echo htmlspecialchars($row['keterangan']); ?>
                                                                    </td>
                                                                    <td style="padding-left:18px;">Rp.
                                                                        <?php echo number_format($row['jumlahDP'], 0, ',', '.'); ?>
                                                                    </td>
                                                                </tr>

                                                            <?php endwhile; ?>
                                                            <?php
                                                            if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
                                                                $creator = $_COOKIE["UserID"];
                                                            } else {
                                                                die("Error: Cookie 'UserID' tidak ada atau kosong.");
                                                            }
                                                            $query_access = "SELECT TipePembayaran FROM useraccesslevel WHERE UserID = '$creator'";
                                                            $result_access = mysqli_query($conn, $query_access);
                                                            $can_updatee = false;
                                                            if ($result_access) {
                                                                $row_access = mysqli_fetch_assoc($result_access);
                                                                $access_level = $row_access['TipePembayaran'];
                                                                if (strpos($access_level, 'U') !== false) {
                                                                    $can_updatee = true;
                                                                }
                                                            } else {
                                                                die("Error: Gagal mengambil data akses pengguna.");
                                                            }
                                                            $query = "SELECT * FROM payment";
                                                            $result = mysqli_query($conn, $query);
                                                            while ($row = mysqli_fetch_array($result)) {
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <script>
                                                        document.getElementById('status-filter').addEventListener('change', function () {
                                                            var status = this.value;
                                                            var xhr = new XMLHttpRequest();
                                                            xhr.open('GET', '../Process/filter-active-payment.php?status=' + status, true);
                                                            xhr.onload = function () {
                                                                if (this.status == 200) {
                                                                    console.log(this.responseText); // Memastikan data yang dikembalikan benar
                                                                    document.getElementById('unit-table-body').innerHTML = this.responseText;
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