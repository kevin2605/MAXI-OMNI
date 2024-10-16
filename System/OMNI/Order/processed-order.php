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
                                <h3>Pesanan Diproses</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Pesanan Diproses</li>

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
                                    <form class="form theme-form" method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="mb-3 row">
                                                        <label class="col-sm-2">Tanggal Awal Faktur</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control" id="startdatefaktur"
                                                                name="startdatefaktur" type="date">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label class="col-sm-2">Tanggal Akhir Faktur</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control" id="enddatefaktur"
                                                                name="enddatefaktur" type="date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<button class="btn btn-primary" type="button" onclick="submitFilter()"><i class="fa fa-search"></i> Search</button>-->
                                        <button class="btn btn-primary" name="btnSearch"><i class="fa fa-search"></i>
                                            Search</button>
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
                                                    <th>OrderID</th>
                                                    <th>Tanggal</th>
                                                    <th>Omset</th>
                                                    <th>Status</th>
                                                    <th>Courir</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_POST["btnSearch"])) {
                                                    $query = "SELECT rih.RCV_InvoiceID, rih.TaxInvoiceNumber, rih.TaxInvoiceDate, s.SupplierName, rid.ItemCD, rih.DPP, rih.PPN,
                                                                         rih.TotalAmount
                                                                  FROM receptioninvoiceheader rih, receptioninvoicedetail rid, receptionheader rh, purchaseorderheader po,
                                                                       supplier s
                                                                  WHERE rih.RCV_InvoiceID=rid.RCV_InvoiceID
                                                                        AND rih.ReceptionID=rh.ReceptionID
                                                                        AND rh.PurchaseOrderID=po.PurchaseOrderID
                                                                        AND po.SupplierNum=s.SupplierNum";

                                                    if ($_POST["supplier"] != '') {
                                                        $suppliers = explode(" - ", $_POST["supplier"]);
                                                        $query .= " AND po.SupplierNum ='" . $suppliers[0] . "'";
                                                    }
                                                    if ($_POST["startdatefaktur"] != '') {
                                                        $query .= " AND rih.TaxInvoiceDate >='" . $_POST["startdatefaktur"] . "'";
                                                    }
                                                    if ($_POST["enddatefaktur"] != '') {
                                                        $query .= " AND rih.TaxInvoiceDate <='" . $_POST["enddatefaktur"] . "'";
                                                    }

                                                    $result = mysqli_query($conn, $query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        echo ' <tr>
                                                                    <td>' . $row["RCV_InvoiceID"] . '</td>
                                                                    <td>' . $row["TaxInvoiceNumber"] . '</td>
                                                                    <td>' . $row["TaxInvoiceDate"] . '</td>
                                                                    <td>' . $row["SupplierName"] . '</td>
                                                                    <td>' . $row["ItemCD"] . '</td>
                                                                    <td>' . number_format($row["DPP"], 0, '.', ',') . '</td>
                                                                    <td> </td>
                                                                    <td> </td>
                                                                    <td>' . number_format($row["PPN"], 0, '.', ',') . '</td>
                                                                    <td>' . number_format($row["TotalAmount"], 0, '.', ',') . '</td>
                                                                </tr>';
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <script src="../../../assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
                <script src="../../../assets/js/datatable/datatable-extension/jszip.min.js"></script>
                <script src="../../../assets/js/datatable/datatable-extension/buttons.colVis.min.js"></script>
                <script src="../../../assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
                <script src="../../../assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
                <script src="../../../assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
                <script src="../../../assets/js/datatable/datatable-extension/custom.js"></script>
                <!-- Plugins JS Ends-->
                <!-- Theme js-->
                <script src="../../../assets/js/script.js"></script>
                <!-- Plugin used-->
                <!-- Plugin used-->
</body>

</html>