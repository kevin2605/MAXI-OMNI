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

    include "../../APITokenTokopedia.php";

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
                                <h3>Pesanan Baru</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Pesanan Baru</li>

                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3>FILTER</h3>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="" class="d-flex flex-wrap align-items-center">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="mb-3 row">
                                                    <div class="d-flex align-items-center justify-content-left">
                                                        <label class="col-sm-5" style="padding-right: 5px;">Start
                                                            Date:</label>
                                                        <div class="col-sm-7">
                                                            <input type="date" class="form-control me-2" id="startDate"
                                                                name="startDate"
                                                                value="<?php echo isset($_GET['startDate']) ? htmlspecialchars($_GET['startDate']) : ''; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="mb-3 row">
                                                    <div class="d-flex align-items-center justify-content-left">
                                                        <label class="col-sm-5 text-center"
                                                            style="padding-right: 13px;">End Date:</label>
                                                        <div class="col-sm-7">
                                                            <input type="date" class="form-control me-2" id="endDate"
                                                                name="endDate"
                                                                value="<?php echo isset($_GET['endDate']) ? htmlspecialchars($_GET['endDate']) : ''; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-primary" onclick="resetDates()"><i
                                                        class="icofont icofont-refresh"
                                                        id="resetDatesButton"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-body">
                                    <div class="dt-ext table-responsive custom-scrollbar">
                                        <table class="display" id="export-button">
                                            <thead>
                                                <tr>
                                                    <th>RefNumber</th>
                                                    <th>Tanggal</th>
                                                    <th>Omset</th>
                                                    <th>Status</th>
                                                    <th>Courier</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php


                                                $addNewOrders = new AddNewOrders();

                                                $fs_id = 15239; // Fulfillment Service ID
                                                $shop_ids = [5312174, 8664717]; // Array of Shop IDs
                                                $currentDate = date('Y-m-d');
                                                $currentTime = time();

                                                $twoDaysAgo = date('Y-m-d', strtotime('-2 days'));

                                                $from_date = strtotime($twoDaysAgo . ' 00:00:00');

                                                $to_date = $currentTime;
                                                $page = 1;
                                                $per_page = 10;
                                                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
                                                    $orderToAdd = json_decode($_POST['order_data'], true);
                                                    error_log("Received order data: " . print_r($orderToAdd, true));
                                                    $result = $addNewOrders->processOrders([$orderToAdd]);

                                                    // if ($result['status'] == 'success') {
                                                    //     echo "<div class='alert alert-success'>" . $result['message'] . "</div>";
                                                    // } else {
                                                    //     echo "<div class='alert alert-danger'>" . $result['message'] . "</div>";
                                                    // }
                                                }
                                                $apiToken = new APITokenTokopedia();
                                                $headers = $apiToken->getHeaders();

                                                $allOrders = [];

                                                foreach ($shop_ids as $shop_id) {
                                                    $url = "https://fs.tokopedia.net/v2/order/list?fs_id={$fs_id}&shop_id={$shop_id}&from_date={$from_date}&to_date={$to_date}&page={$page}&per_page={$per_page}";

                                                    error_log("URL API untuk Shop ID $shop_id: " . $url);

                                                    $curl = curl_init();
                                                    curl_setopt($curl, CURLOPT_URL, $url);
                                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                                                    $response = curl_exec($curl);

                                                    if (curl_errno($curl)) {
                                                        $error_message = 'Error: ' . curl_error($curl);
                                                        error_log("Error pada Shop ID $shop_id: " . $error_message);
                                                        echo '<tr><td colspan="6">' . $error_message . '</td></tr>';
                                                    } else {
                                                        error_log("Response dari API untuk Shop ID $shop_id: " . $response);

                                                        $responseData = json_decode($response, true);
                                                        if (isset($responseData['data']) && !empty($responseData['data'])) {
                                                            $allOrders = array_merge($allOrders, $responseData['data']);
                                                        } else {
                                                            error_log("Data tidak ditemukan untuk Shop ID $shop_id.");
                                                        }
                                                    }

                                                    curl_close($curl);
                                                }

                                                if (!empty($allOrders)) {
                                                    foreach ($allOrders as $order) {
                                                        $order_id = $order['order_id'];
                                                        $tanggal = date('Y-m-d', strtotime($order['payment_date']));
                                                        $omset = number_format($order['amt']['ttl_amount'], 0, ',', '.');
                                                        $status = $order['order_status'] == 400 ? "Seller accept order" : "Unknown status";
                                                        $courier = $order['logistics']['shipping_agency'];

                                                        echo "<tr>
                                                                <td>{$order_id}</td>
                                                                <td>{$tanggal}</td>
                                                                <td>Rp. {$omset}</td>
                                                                <td>{$status}</td>
                                                                <td>{$courier}</td>
                                                                <td>
                                                                    <form method='POST'>
                                                                        <input type='hidden' name='action' value='add'>
                                                                        <input type='hidden' name='order_data' value='" . htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8') . "'>
                                                                        <button type='submit' class='action-button'>Add</button>
                                                                    </form>
                                                                    <a href='detail-order.php?order_id={$order_id}' class='action-button'>Detail</a>
                                                                </td>
                                                              </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='6'>No orders found for both shop IDs.</td></tr>";
                                                }


                                                echo "</tbody></table>";



                                                ?>


                                            </tbody>
                                        </table>
                                        <!-- 
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
                                        </script> -->

                                    </div>
                                </div>

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
                                <script
                                    src="../../../assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
                                <script src="../../../assets/js/datatable/datatable-extension/jszip.min.js"></script>
                                <script
                                    src="../../../assets/js/datatable/datatable-extension/buttons.colVis.min.js"></script>
                                <script src="../../../assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
                                <script
                                    src="../../../assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
                                <script
                                    src="../../../assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
                                <script
                                    src="../../../assets/js/datatable/datatable-extension/buttons.print.min.js"></script>
                                <script src="../../../assets/js/datatable/datatable-extension/custom.js"></script>
                                <script src="../../../assets/js/script.js"></script>

                                <!-- jQuery -->
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                                <!-- DataTables JS -->
                                <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
                                <script
                                    src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                                <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
                                <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

</body>

</html>