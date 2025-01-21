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

    include "../../DBConnection.php";
    $userID = $_COOKIE['UserID'];
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

    // Dummy data untuk order_header
    $orderHeader = [
        [
            'OrderID' => '282826',
            'OrderDate' => '2023-05-31 21:20:35',
            'Marketplace' => 'Shopee',
            'TotalAmount' => 'MYR57.40',
            'OrderStatus' => 'Completed',
            'OrderStatusDesc' => 'Completed',
            'OrderRefNum' => '2305316X1VKM24',
            'ShippingAgent' => 'Ninjavan',
            'TrackingNumber' => 'SPE2855587996',
            'Address' => 'Selangor, Malaysia',
            'Phone' => 'A******D',
        ],
        [
            'OrderID' => '280552',
            'OrderDate' => '2023-05-23 14:43:46',
            'Marketplace' => 'Shopee',
            'TotalAmount' => 'MYR63.70',
            'OrderStatus' => 'Completed',
            'OrderStatusDesc' => 'Completed',
            'OrderRefNum' => '230523F7F979FU',
            'ShippingAgent' => 'Ninjavan',
            'TrackingNumber' => 'SPE9768566548',
            'Address' => 'Selangor, Malaysia',
            'Phone' => 'F******A',
        ],
    ];

    // Dummy data untuk order_detail
    $orderDetail = [
        '282826' => [
            [
                'ProductID' => '310437',
                'SKU' => 'Glycolic 240ml Full Size',
                'ProductName' => 'The Ordinary Glycolic Acid 7% Toning Solution (240ml)',
                'Quantity' => 1,
                'Price' => 'MYR54.50',
                'TotalPrice' => 'MYR54.50',
            ],
        ],
        '280552' => [
            [
                'ProductID' => '307829',
                'SKU' => 'COSRX AHA/BHA Clarifying Toner 150ml',
                'ProductName' => 'COSRX Toner and Cleanser Collection Blackhead/Whitehead',
                'Quantity' => 1,
                'Price' => 'MYR35.90',
                'TotalPrice' => 'MYR35.90',
            ],
            [
                'ProductID' => '307829',
                'SKU' => 'GoodMorning Cleanser 150ml',
                'ProductName' => 'COSRX Toner and Cleanser Collection Blackhead/Whitehead',
                'Quantity' => 1,
                'Price' => 'MYR25.90',
                'TotalPrice' => 'MYR25.90',
            ],
        ],
    ];
    ?>
    ?>
</head>
<style>
    .hidden {
        display: none;
    }

    .table th {
        border-bottom: 2px solid #000;
        /* Garis bawah pada header tabel */
    }

    .table td {
        border: none;
        /* Menghapus border pada sel tabel */
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
                                <h3>Pesanan Selesai</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Pesanan Selesai</li>

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
                                    <!-- Tombol untuk membuka modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#filterModal">
                                        Open Filter
                                    </button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exportModal">
                                        Export
                                    </button>
                                </div>
                            </div>
                            <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="filterModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="filterModalLabel">Filter</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="filterForm">
                                                <div class="row mb-3">
                                                    <label for="startDate" class="col-sm-2 col-form-label">Start
                                                        Date:</label>
                                                    <div class="col-sm-4">
                                                        <input type="date" class="form-control" id="startDate"
                                                            name="startDate">
                                                    </div>
                                                    <label for="endDate" class="col-sm-2 col-form-label">End
                                                        Date:</label>
                                                    <div class="col-sm-4">
                                                        <input type="date" class="form-control" id="endDate"
                                                            name="endDate">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-secondary">Reset</button>
                                                    <button class="btn btn-success">Apply</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="filterModalLabel">Filter</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="filterForm">
                                                <div class="row mb-3">
                                                    <label for="startDate" class="col-sm-2 col-form-label">Start
                                                        Date:</label>
                                                    <div class="col-sm-4">
                                                        <input type="date" class="form-control" id="startDate"
                                                            name="startDate">
                                                    </div>
                                                    <label for="endDate" class="col-sm-2 col-form-label">End
                                                        Date:</label>
                                                    <div class="col-sm-4">
                                                        <input type="date" class="form-control" id="endDate"
                                                            name="endDate">
                                                    </div>
                                                </div>
                                                <!-- Komponen tambahan -->
                                                <div class="form-group">
                                                    <label>Payment Method</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Something">
                                                </div>
                                                <div class="form-group">
                                                    <label>Package Type</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Something">
                                                </div>

                                                <div class="form-group">
                                                    <label>iSKU</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Something">
                                                </div>
                                                <div class="form-group">
                                                    <label>Parent iSKU</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Something">
                                                </div>
                                                <div class="form-group">
                                                    <label>SKU</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Something">
                                                </div>
                                                <div class="form-group">
                                                    <label>Rack</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Something">
                                                </div>
                                                <div class="form-group">
                                                    <label>Rack Group</label>
                                                    <select class="form-control">
                                                        <option>Please Select</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Order Weight (KG)</label>
                                                    <div class="d-flex">
                                                        <input type="text" class="form-control" placeholder="From">
                                                        <input type="text" class="form-control" placeholder="To">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Comment</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Something">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="orderWithComments">
                                                        <label class="form-check-label" for="orderWithComments">Any
                                                            Order with Comments</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="orderWithoutComments">
                                                        <label class="form-check-label" for="orderWithoutComments">Any
                                                            Order
                                                            without
                                                            Comments</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Packing Note</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Something">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="orderWithPackingNote">
                                                        <label class="form-check-label" for="orderWithPackingNote">Any
                                                            Order
                                                            with Packing
                                                            Note</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Order Type</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="orderType"
                                                            id="singleProduct">
                                                        <label class="form-check-label" for="singleProduct">Single
                                                            Product</label>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-secondary">Reset</button>
                                                    <button class="btn btn-success">Apply</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Item details</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Payment Info</th>
                                                <th>Coment</th>
                                                <th>Printed</th>
                                                <th>Action</th>


                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <?php
                                error_reporting(E_ALL);
                                ini_set('display_errors', 1);
                                require_once '../Process/addneworders.php';
                                include '../RequestAPI/tokopedia-get-new-order.php';
                                $addNewOrders = new AddNewOrders();
                                $currentDate = date('Y-m-d');
                                $currentTime = time();
                                $threeDaysAgo = date('Y-m-d H:i:s', strtotime('-3 days'));
                                $from_date = strtotime($threeDaysAgo);
                                $to_date = $currentTime;

                                $page = 1;
                                $per_page = 50;

                                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
                                    $orderToAdd = json_decode($_POST['order_data'], true);
                                    error_log("Received order data: " . print_r($orderToAdd, true));
                                    $result = processNewOrder($orderToAdd);
                                }

                                $allOrders = getNewOrders($from_date, $to_date, $page, $per_page);
                                error_log("All Orders: " . print_r($allOrders, true));


                                $query = "SELECT OrderID, OrderDate, OrderRefNum, TotalAmount, OrderStatusDesc, ShippingAgent 
                                          FROM order_header 
                                          WHERE OrderStatus IN (600,700)";
                                $result = $conn->query($query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $order_id = $row['OrderID'];
                                        $RefNum = $row['OrderRefNum'];
                                        $order_date = date('Y-m-d', strtotime($row['OrderDate']));
                                        $total_amount = number_format($row['TotalAmount'], 0, ',', '.');
                                        $order_status_desc = $row['OrderStatusDesc'];
                                        $shipping_agent = $row['ShippingAgent'];
                                        ?>
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <!-- Tabel Header Order -->
                                                        <table class="table no-border">
                                                            <thead>
                                                                <tr>
                                                                    <th>Order ID</th>
                                                                    <th></th>
                                                                    <th>Order Date</th>
                                                                    <th>Total Amount</th>
                                                                    <th>Shipping Agent</th>
                                                                    <th>Customer</th>
                                                                    <th>Phone</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>#<?= $order_id ?></td>
                                                                    <td>#<?= $RefNum ?></td>
                                                                    <td><?= $order_date ?></td>
                                                                    <td>Rp. <?= $total_amount ?></td>
                                                                    <td><?= $shipping_agent ?></td>
                                                                    <td>A*****</td>
                                                                    <td>62834********</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <!-- Tabel Detail Order -->
                                                        <?php if (isset($row['OrderID'])): ?>
                                                            <?php
                                                            $detail_query = "SELECT SKU, ProductName, Quantity, Price, TotalPrice 
                                                            FROM order_detail 
                                                            WHERE OrderID = ?";
                                                            $stmt = $conn->prepare($detail_query);
                                                            $stmt->bind_param("i", $order_id);
                                                            $stmt->execute();
                                                            $detail_result = $stmt->get_result();
                                                            ?>
                                                            <table class="table no-border mt-4">
                                                                <thead>
                                                                    <tr>
                                                                        <th>SKU</th>
                                                                        <th>Product Name</th>
                                                                        <th>Quantity</th>
                                                                        <th>Price</th>
                                                                        <th>Total Price</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if ($detail_result->num_rows > 0): ?>
                                                                        <?php while ($detail_row = $detail_result->fetch_assoc()): ?>
                                                                            <tr>
                                                                                <td><?= htmlspecialchars($detail_row['SKU']) ?></td>
                                                                                <td><?= htmlspecialchars($detail_row['ProductName']) ?></td>
                                                                                <td><?= htmlspecialchars($detail_row['Quantity']) ?></td>
                                                                                <td>Rp.
                                                                                    <?= number_format($detail_row['Price'], 0, ',', '.') ?>
                                                                                </td>
                                                                                <td>Rp.
                                                                                    <?= number_format($detail_row['TotalPrice'], 0, ',', '.') ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endwhile; ?>
                                                                    <?php else: ?>
                                                                        <tr>
                                                                            <td colspan="5" class="text-center">No details found for
                                                                                this order.</td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                            </table>
                                                            <?php $stmt->close(); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <?php
                                    }
                                } else {
                                    echo "<div class='col-md-12'><div class='card'><div class='card-body'>No orders found in the database.</div></div></div>";
                                }

                                // Tutup koneksi database
                                $conn->close();
                                ?>
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