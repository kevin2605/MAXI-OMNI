<!DOCTYPE html>
<html lang="en">

<!-- AJAX SCRIPT and DYNAMIC TABLE -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<!-- script sweetaler2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<head>
    <?php
    include "../headcontent.php";

    session_start();

    include "../APITokenTokopedia.php";

    include '../Process/addneworders.php';


    // Koneksi ke database
    include "../../DBConnection.php"; // Sesuaikan dengan file koneksi database Anda
    
    // Ambil ID pengguna dari sesi atau cookie
    $userID = $_COOKIE['UserID']; // Sesuaikan dengan cara Anda menyimpan ID pengguna
    
    // Ambil akses level dari database
    $query = "SELECT Kota FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Cek akses CRUD dan tentukan apakah akses diizinkan
    $hasCRUDAccess = strpos($row['Kota'], 'C') !== false || // Create
        strpos($row['Kota'], 'R') !== false || // Read
        strpos($row['Kota'], 'U') !== false || // Update
        strpos($row['Kota'], 'D') !== false;  // Delete
    
    // Jika tidak memiliki akses CRUD, tampilkan pesan dan redirect
    $accessDenied = !$hasCRUDAccess;
    ?>
</head>
<style>
    .hidden {
        display: none;
    }
</style>

<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script>
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
    </script> -->
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
                                <p><b> Selamat! </b>Kota baru berhasil disimpan ke database.</p>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            } else if ($_GET["status"] == "error") {
                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-6 ps-0">
                                <h3> Detail Pesanan Baru</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Pesanan Baru</li>
                                    <li class="breadcrumb-item">Detail</li>

                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="col-sm-12">
                        <div class="card">
                            <!-- <div class="card-header">
                                <h3>Detail </h3>
                            </div> -->
                            <div class="card-block row">
                                <div class="col-sm-12 col-lg-12 col-xl-12">
                                    <div class="table-responsive custom-scrollbar">
                                        <?php

                                        if (isset($_GET['order_id'])) {
                                            $order_id = $_GET['order_id'];

                                            $query = "SELECT SKU, ProductName, Quantity, Price, TotalPrice 
                                            FROM order_detail 
                                            WHERE OrderID = ?";
                                            $stmt = $conn->prepare($query);
                                            $stmt->bind_param("i", $order_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result->num_rows > 0) {
                                                echo '<table class="table table-dashed">
                                                    <thead>
                                                        <tr>
                                                            <th>SKU</th>
                                                            <th>ProductName</th>
                                                            <th>Quantity</th>
                                                            <th>Price</th>
                                                            <th>TotalPrice</th>
                                                            <th> </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>';

                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>
                                                                <td>' . htmlspecialchars($row['SKU']) . '</td>
                                                                <td>' . htmlspecialchars($row['ProductName']) . '</td>
                                                                <td>' . htmlspecialchars($row['Quantity']) . '</td>
                                                                <td>Rp. ' . number_format($row['Price'], 0, ',', '.') . '</td>
                                                                <td>Rp. ' . number_format($row['TotalPrice'], 0, ',', '.') . '</td>
                                                                <td> </td>
                                                            </tr>';
                                                }

                                                echo '</tbody>
                                                </table>';
                                            } else {
                                                echo "<p>Data order detail tidak ditemukan.</p>";
                                            }

                                            $stmt->close();
                                        } else {
                                            echo "<p>Order ID tidak tersedia.</p>";
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-body">
                                    <div class="dt-ext table-responsive custom-scrollbar">
                                        <table class="display" id="export-button">
                                            <thead>
                                                <tr>
                                                    <th>SKU</th>
                                                    <th>ProductName</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>TotalPrice</th>

                                                </tr>
                                            </thead>
                                            <tbody>


                                            </tbody>
                                        </table>

                                        <script>
                                            $(document).ready(function () {
                                                // Inisialisasi DataTables dengan tombol export
                                                $('#export-button').DataTable({
                                                    dom: 'Bfrtip', // Menentukan posisi tombol

                                                    buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
                                                    // Menambahkan opsi ini untuk memastikan tombol tetap muncul meskipun tidak ada data
                                                    processing: true,
                                                    serverSide: false,
                                                    ajax: false, // Karena kita menggunakan data dari API
                                                    data: [], // Data awal kosong
                                                });
                                            });
                                        </script>

                                    </div>
                                </div> -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 p-0 footer-copyright">
                            <p class="mb-0">Copyright 2024 © MAXI.</p>
                        </div>
                        <div class="col-md-6 p-0">
                            <p class="heart mb-0">Business System and Information
                            </p>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Include Scripts Only Once -->
            <script src="../../../assets/js/jquery.min.js"></script>
            <script src="../../../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
            <script src="../../../assets/js/icons/feather-icon/feather.min.js"></script>
            <script src="../../../assets/js/icons/feather-icon/feather-icon.js"></script>
            <script src="../../../assets/js/scrollbar/simplebar.js"></script>
            <script src="../../../assets/js/scrollbar/custom.js"></script>
            <script src="../../../assets/js/config.js"></script>
            <script src="../../../assets/js/sidebar-menu.js"></script>
            <script src="../../../assets/js/sidebar-pin.js"></script>
            <script src="../../../assets/js/slick/slick.min.js"></script>
            <script src="../../../assets/js/slick/slick.js"></script>
            <script src="../../../assets/js/header-slick.js"></script>
            <script src="../../../assets/js/form-validation-custom.js"></script>
            <script src="../../../assets/js/notify/bootstrap-notify.min.js"></script>
            <script src="../../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
            <script src="../../../assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
            <script src="../../../assets/js/datatable/datatable-extension/jszip.min.js"></script>
            <script src="../../../assets/js/datatable/datatable-extension/buttons.colVis.min.js"></script>
            <script src="../../../assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
            <script src="../../../assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
            <script src="../../../assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
            <script src="../../../assets/js/datatable/datatable-extension/buttons.print.min.js"></script>
            <script src="../../../assets/js/datatable/datatable-extension/custom.js"></script>
            <script src="../../../assets/js/script.js"></script>

            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <!-- DataTables JS -->
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

</body>

</html>