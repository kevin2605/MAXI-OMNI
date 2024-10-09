<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    session_start();
    include "../DBConnection.php";
    $userID = $_COOKIE['UserID'];

    $query = "SELECT tPOPPSP FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $hasCRUDAccess = strpos($row['tPOPPSP'], 'C') !== false || // Create
        strpos($row['tPOPPSP'], 'R') !== false || // Read
        strpos($row['tPOPPSP'], 'U') !== false || // Update
        strpos($row['tPOPPSP'], 'D') !== false;  // Delete
    
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
                    text: 'Anda tidak memiliki akses untuk Approve.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var i = 1;
        function appendProductTable(x) {
            i++;
            $('#dinamis #dbody').append('<tr id="row' + i + '"><td><input type="text" class="form-control prodlist" name="items[]" list="itemOptions" onChange="appendProductTable(this)" required></td><td><input type="text" class="form-control" name="quantities[]" placeholder="0" onChange="countSubtotal(this)" required></td><td><input type="text" class="form-control" name="units[]" style="border-style:none;" readonly></td><td><input type="text" class="form-control" name="prices[]" style="border-style:none;" readonly></td><td><input type="text" class="form-control" name="subtotals[]" style="border-style:none;" placeholder="0" readonly></td><td><button id="' + i + '" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button></td></tr>');

            $.ajax({
                type: "POST",
                url: "../Process/getSGSP.php",
                data: "cd=" + x.value,
                success: function (result) {
                    var res = JSON.parse(result);
                    $.each(res, function (index, value) {
                        x.parentElement.parentElement.cells[2].getElementsByTagName("input")[0].value = value.UnitCD;
                        x.parentElement.parentElement.cells[3].getElementsByTagName("input")[0].value = value.BuyPrice;
                    });
                }
            });
        }

        $("document").ready(function () {
            $(document).on('click', '.bremove', function () {
                i--;
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });

        function viewPO(str) {
            document.location = "viewPOOther.php?id=" + str.value;
        }

        function editPurchase(str) {
            //document.location = "editSalesOrder.php?id=" + str.value;
        }
        function printPO(str) {
            var invoiceID = str.value;
            var url = "../Process/generate_POLppsp_pdf.php?PurchaseOrderID=" + invoiceID;
            window.open(url, '_blank'); // Membuka URL di tab baru
        }

        function deletePurchase(str) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Sales Order dengan kode " + str.value + " akan dihapus dari database!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, setuju!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    alert(str.value);
                    //document.location = "";
                }
            });
        }

        function countSubtotal(x) {
            var jumlah = x.parentElement.parentElement.cells[1].getElementsByTagName("input")[0].value;
            var harga = x.parentElement.parentElement.cells[3].getElementsByTagName("input")[0].value;
            let subtotal = harga * jumlah;

            x.parentElement.parentElement.cells[4].getElementsByTagName("input")[0].value = numeral(subtotal).format("0,0");
        }

        function mainAddress() {
            var cb = document.getElementById("chk-Add");
            var ta = document.getElementById("shipadd");

            if (cb.checked == true) {
                shipadd.value = "Pergudangan Safe N Lock";
            } else {
                shipadd.value = null;
            }
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
                                if ($_GET["status"] == "success-po") {
                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                        <p><b> Selamat! </b>Purchase Order baru berhasil disimpan ke database.</p>
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                                } else if ($_GET["status"] == "error-po") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                        <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Purchase Order ke database.</p>
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-6 ps-0">
                                    <h3>PURCHASE ORDER (PENUNJANG & SPARE PART)</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Pembelian (Lokal)</li>
                                        <li class="breadcrumb-item">PO (PPSP)</li>
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
                                            if ($_GET["status"] == "success-po") {
                                                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                    <p><b> Selamat! </b>Purchase Order baru berhasil disimpan ke database.</p>
                                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                            } else if ($_GET["status"] == "error-po") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                    <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Purchase Order ke database.</p>
                                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                            }
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-6 ps-0">
                                                <h3>PURCHASE ORDER (PENUNJANG & SPARE PART)</h3>
                                            </div>
                                            <div class="col-sm-6 pe-0">
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="index.html">
                                                            <svg class="stroke-icon">
                                                                <use
                                                                    href="../../assets/svg/icon-sprite.svg#stroke-home">
                                                                </use>
                                                            </svg></a></li>
                                                    <li class="breadcrumb-item">Pembelian (Lokal)</li>
                                                    <li class="breadcrumb-item">PO (PPSP)</li>
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
                                                    $query_access = "SELECT tPOPPSP FROM useraccesslevel WHERE UserID = '$userID'";
                                                    $result_access = mysqli_query($conn, $query_access);

                                                    if ($result_access) {
                                                        $row_access = mysqli_fetch_assoc($result_access);
                                                        $access_level = $row_access['tPOPPSP'];
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
                                                <button class="btn btn-outline-primary" type="button" <?php echo $hasAccess ? 'data-bs-toggle="modal" data-bs-target=".bd-example-modal-xl"' : 'disabled'; ?>>
                                                    <i class="fa fa-plus-circle"></i> New PO
                                                </button>
                                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog"
                                                    aria-labelledby="myExtraLargeModal" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myExtraLargeModal">
                                                                    Purchase Order Baru
                                                                </h4>
                                                                <button class="btn-close py-0" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body dark-modal">
                                                                <div class="card-body custom-input">
                                                                    <form class="row g-3"
                                                                        action="../Process/createPOOther.php"
                                                                        method="POST">
                                                                        <div class="col-3">
                                                                            <label class="form-label"
                                                                                for="first-name">Purchase
                                                                                Order ID</label>
                                                                            <input class="form-control" id="first-name"
                                                                                type="text" placeholder="auto-generated"
                                                                                aria-label="First name" readonly>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <label class="form-label"
                                                                                for="orderdate">Tanggal
                                                                                Order</label>
                                                                            <input class="form-control" id="orderdate"
                                                                                type="date"
                                                                                value="<?php echo date('Y-m-d'); ?>"
                                                                                readonly>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <label class="form-label"
                                                                                for="deliverydate">Tanggal
                                                                                Kirim<span
                                                                                    style="color:red;">*</span></label>
                                                                            <input class="form-control"
                                                                                id="deliverydate" name="deliverydate"
                                                                                type="date" required>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <label class="form-label"
                                                                                for="kategori">Kategori
                                                                                Pembelian<span
                                                                                    style="color:red;">*</span></label>
                                                                            <input class="form-control" id="kategori"
                                                                                name="kategori" list="kategoriOptions"
                                                                                placeholder="kategori" required>
                                                                            <datalist id="kategoriOptions">
                                                                                <option
                                                                                    value="BPP - Bahan Penunjang Produksi">
                                                                                </option>
                                                                                <option value="SPR - Spare Part">
                                                                                </option>
                                                                            </datalist>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <label class="form-label"
                                                                                for="creator">Pembuat
                                                                                PO</label>
                                                                            <input class="form-control" id="creator"
                                                                                name="creator" type="text"
                                                                                value="<?php echo $_COOKIE["UserID"] . ' - ' . $_COOKIE["Name"] ?>"
                                                                                readonly>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label class="form-label"
                                                                                for="supplier">Supplier<span
                                                                                    style="color:red;">*</span></label>
                                                                            <input class="form-control" id="supplier"
                                                                                name="supplier" list="supplierOptions"
                                                                                placeholder="supplier" required>
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
                                                                        <div class="col-3">
                                                                            <label class="form-label"
                                                                                for="termin">Termin
                                                                                (Hari)<span
                                                                                    style="color:red;">*</span></label>
                                                                            <input class="form-control" id="termin"
                                                                                name="termin" list="terminOptions"
                                                                                placeholder="Termin" required>
                                                                            <datalist id="terminOptions">
                                                                                <option value="5"></option>
                                                                                <option value="10"></option>
                                                                                <option value="15"></option>
                                                                                <option value="20"></option>
                                                                                <option value="25"></option>
                                                                                <option value="30"> </option>
                                                                            </datalist>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="form-label"
                                                                                for="shipadd">Alamat
                                                                                Pengiriman<span
                                                                                    style="color:red;">*</span></label>
                                                                            <textarea class="form-control" id="shipadd"
                                                                                name="shipadd" rows="3"
                                                                                required></textarea>
                                                                            <input class="checkbox_animated"
                                                                                id="chk-Add" type="checkbox"
                                                                                style="margin-top:5px;"
                                                                                onclick="mainAddress()"> Alamat
                                                                            Utama
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="form-label"
                                                                                for="desc">Keterangan</label>
                                                                            <textarea class="form-control" id="desc"
                                                                                name="desc" rows="2"></textarea>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="d-flex pb-0">
                                                                            <h3>Detil Order</h3>
                                                                        </div>
                                                                        <table id="dinamis" class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th scope="col">Barang</th>
                                                                                    <th scope="col">Jumlah</th>
                                                                                    <th scope="col">Satuan</th>
                                                                                    <th scope="col">Harga (exclude)</th>
                                                                                    <th scope="col">Subtotal</th>
                                                                                    <th scope="col">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="dbody">
                                                                                <tr id="row1">
                                                                                    <td>
                                                                                        <input type="text"
                                                                                            class="form-control prodlist"
                                                                                            name="items[]"
                                                                                            list="itemOptions"
                                                                                            onChange="appendProductTable(this)"
                                                                                            required>
                                                                                        <datalist id="itemOptions">
                                                                                            <?php
                                                                                            //penunjang produksi
                                                                                            $queryp = "SELECT GoodsCD,GoodsName FROM supportinggoods";
                                                                                            $resultp = mysqli_query($conn, $queryp);
                                                                                            while ($rowp = mysqli_fetch_array($resultp)) {
                                                                                                echo '<option value="' . $rowp["GoodsCD"] . '">' . $rowp["GoodsName"] . '</option>';
                                                                                            }

                                                                                            //spare part
                                                                                            $queryp = "SELECT PartCD,PartName FROM sparepart";
                                                                                            $resultp = mysqli_query($conn, $queryp);
                                                                                            while ($rowp = mysqli_fetch_array($resultp)) {
                                                                                                echo '<option value="' . $rowp["PartCD"] . '">' . $rowp["PartName"] . '</option>';
                                                                                            }
                                                                                            ?>
                                                                                        </datalist>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="quantities[]"
                                                                                            placeholder="0"
                                                                                            onChange="countSubtotal(this)"
                                                                                            required>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="units[]"
                                                                                            style="border-style:none;"
                                                                                            readonly>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="prices[]"
                                                                                            style="border-style:none;"
                                                                                            readonly>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="subtotals[]"
                                                                                            style="border-style:none;"
                                                                                            placeholder="0" readonly>
                                                                                    </td>
                                                                                    <td>

                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <hr>
                                                                        <div class="col-12">
                                                                            <div class="form-check form-switch">
                                                                                <input class="form-check-input"
                                                                                    id="flexSwitchCheckDefault"
                                                                                    type="checkbox" role="switch"
                                                                                    required>
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
                                                    data-bs-toggle="dropdown" aria-expanded="false">Menu</button>
                                                <ul class="dropdown-menu dropdown-block" id="myTab" role="tablist">
                                                    <li class="nav-item"><a
                                                            class="dropdown-item active txt-primary f-w-500 f-18"
                                                            id="home-tab" data-bs-toggle="tab" href="#daftarPO"
                                                            role="tab" aria-controls="home" aria-selected="true">PO
                                                            Pending</a></li>
                                                    <li class="nav-item"><a
                                                            class="dropdown-item txt-primary f-w-500 f-18"
                                                            id="profile-tabs" data-bs-toggle="tab"
                                                            href="#penerimaanBarang" role="tab" aria-controls="profile"
                                                            aria-selected="false">PO
                                                            Selesai</a>
                                                    </li>
                                                </ul>
                                                <hr>
                                                <div class="tab-content" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="daftarPO"
                                                        role="tabpanel">
                                                        <h3>Purchase Order</h3><small>Status : Pending</small>
                                                        <br>
                                                        <div class="table-responsive custom-scrollbar user-datatable">
                                                            <table class="display" id="basic-12">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Purchase Order</th>
                                                                        <th>Tanggal</th>
                                                                        <th>Termin</th>
                                                                        <th>Supplier</th>
                                                                        <th>Nominal</th>
                                                                        <th>Approve?</th>
                                                                        <th>Status?</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php

                                                                    if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
                                                                        $creator = $_COOKIE["UserID"];
                                                                    } else {
                                                                        die("Error: Cookie 'UserID' tidak ada atau kosong.");
                                                                    }

                                                                    // Mengambil akses level dari tPOPPSP untuk bahan lain-lain
                                                                    $query_access = "SELECT tPOPPSP FROM useraccesslevel WHERE UserID = '$creator'";
                                                                    $result_access = mysqli_query($conn, $query_access);
                                                                    $can_update = false;
                                                                    $can_print = false; // Menambah variabel untuk logika print
                                                                    
                                                                    if ($result_access) {
                                                                        $row_access = mysqli_fetch_assoc($result_access);
                                                                        $access_level = $row_access['tPOPPSP'];
                                                                        if (strpos($access_level, 'R') !== false) {
                                                                            $can_update = true;
                                                                        }
                                                                        if (strpos($access_level, 'U') !== false) {
                                                                            $can_print = true;
                                                                        }
                                                                    } else {
                                                                        die("Error: Gagal mengambil data akses pengguna.");
                                                                    }
                                                                    $querySO = "SELECT po.PurchaseOrderID, po.CreatedOn, po.Termin, s.SupplierName, po.ApprovalStatus, po.TotalPurchase, po.Finish, po.ApprovalBy
                                                                    FROM (purchaseorderheader po JOIN supplier s ON po.SupplierNum=s.SupplierNum)
                                                                    WHERE po.Finish=0
                                                                    AND po.CategoryCD != 'BB'";
                                                                    $resultSO = mysqli_query($conn, $querySO);
                                                                    while ($rowPO = mysqli_fetch_array($resultSO)) {
                                                                        echo '
                                                                        <tr>
                                                                            <td>' . $rowPO["PurchaseOrderID"] . '</td>
                                                                            <td>' . $rowPO["CreatedOn"] . '</td>
                                                                            <td>' . $rowPO["Termin"] . '</td>
                                                                            <td>' . $rowPO["SupplierName"] . '</td>
                                                                            <td>' . number_format($rowPO["TotalPurchase"], 0, '.', ',') . '</td>';
                                                                        if ($rowPO["ApprovalStatus"] == "Approved") {
                                                                            echo '<td><span class="badge badge-light-success">Approved</span></td>';
                                                                        } else if ($rowPO["ApprovalStatus"] == "Pending") {
                                                                            echo '<td><span class="badge badge-light-warning">Pending</span></td>';
                                                                        } else if ($rowPO["ApprovalStatus"] == "Reject") {
                                                                            echo '<td><span class="badge badge-light-danger">Reject</span></td>';
                                                                        }
                                                                        if ($rowPO["Finish"] == 1) {
                                                                            echo '<td><span class="badge badge-light-success">Complete</span></td>';
                                                                        } else if ($rowPO["Finish"] == 0) {
                                                                            echo '<td><span class="badge badge-light-danger">Pending</span></td>';
                                                                        } else if ($rowPO["Finish"] == 2) {
                                                                            echo '<td><span class="badge badge-light-danger">Cancel</span></td>';
                                                                        }
                                                                        echo '<td> 
                                                                             <ul> ';
                                                                        if ($can_update) {
                                                                            echo '<button onclick="viewPO(this)" type="button" class="light-card border-primary border b-r-10" value="' . $rowPO["PurchaseOrderID"] . '"><i class="fa fa-eye txt-primary"></i></button>';
                                                                        }
                                                                        echo ' 
                                                                        <button onclick="editPO(this)" type="button" style="margin-right: 5px;" class="light-card border-warning border b-r-10" value="' . $rowPO["PurchaseOrderID"] . '"><i class="fa fa-pencil-square-o txt-warning"></i></button>';
                                                                        if ($can_print && strpos($creator, 'ADM') !== false && !is_null($rowPO['ApprovalBy'])) {
                                                                            echo '<button onclick="printPO(this)" type="button" class="light-card border-info border b-r-10" value="' . $rowPO['PurchaseOrderID'] . '"><i class="fa fa-print txt-info"></i></button>';
                                                                        }
                                                                        echo '</ul></td></tr>';
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="penerimaanBarang" role="tabpanel">
                                                        <h3>Purchase Order</h3><small>Status : Complete</small>
                                                        <div class="table-responsive custom-scrollbar user-datatable">
                                                            <table class="display" id="basic-1">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Purchase Order</th>
                                                                        <th>Tanggal</th>
                                                                        <th>Termin</th>
                                                                        <th>Supplier</th>
                                                                        <th>Nominal</th>
                                                                        <th>Approve?</th>
                                                                        <th>Status?</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $querySO = "SELECT po.PurchaseOrderID, po.CreatedOn, po.Termin, s.SupplierName, po.ApprovalStatus, po.TotalPurchase, po.Finish
                                                                            FROM (purchaseorderheader po JOIN supplier s ON po.SupplierNum=s.SupplierNum)
                                                                            WHERE po.Finish=1
                                                                                    AND po.CategoryCD != 'BB'";
                                                                    $resultSO = mysqli_query($conn, $querySO);
                                                                    while ($rowPO = mysqli_fetch_array($resultSO)) {
                                                                        echo '
                                                                                <tr>
                                                                                    <td>' . $rowPO["PurchaseOrderID"] . '</td>
                                                                                    <td>' . $rowPO["CreatedOn"] . '</td>
                                                                                    <td>' . $rowPO["Termin"] . '</td>
                                                                                    <td>' . $rowPO["SupplierName"] . '</td>
                                                                                    <td>' . number_format($rowPO["TotalPurchase"], 0, '.', ',') . '</td>';
                                                                        if ($rowPO["ApprovalStatus"] == "Approved") {
                                                                            echo '<td><span class="badge badge-light-success">Approved</span></td>';
                                                                        } else if ($rowPO["ApprovalStatus"] == "Pending") {
                                                                            echo '<td><span class="badge badge-light-warning">Pending</span></td>';
                                                                        } else if ($rowPO["ApprovalStatus"] == "Reject") {
                                                                            echo '<td><span class="badge badge-light-danger">Reject</span></td>';
                                                                        }
                                                                        if ($rowPO["Finish"] == 1) {
                                                                            echo '<td><span class="badge badge-light-success">Complete</span></td>';
                                                                        } else if ($rowPO["Finish"] == 0) {
                                                                            echo '<td><span class="badge badge-light-danger">Pending</span></td>';
                                                                        } else if ($rowPO["Finish"] == 2) {
                                                                            echo '<td><span class="badge badge-light-danger">Cancel</span></td>';
                                                                        }
                                                                        echo '<td> 
                                                                                    <ul> 
                                                                                        <button onclick="viewPO(this)" type="button" class="light-card border-primary border b-r-10" value="' . $rowPO["PurchaseOrderID"] . '"><i class="fa fa-eye txt-primary"></i></button>
                                                                                        <button onclick="editPO(this)" type="button" class="light-card border-warning border b-r-10" value="' . $rowPO["PurchaseOrderID"] . '"><i class="fa fa-pencil-square-o txt-warning"></i></button>
                                                                                    </ul>
                                                                                    </td>
                                                                                </tr>
                                                                            ';
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
                <!-- DYNAMIC TABLE -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
                <script>
                    $("document").ready(function () {
                        var i = 0;
                        var x = 0;
                        $('#add_item').click(function () {
                            i++;
                            x++;
                            $('#dinamis #dbody').append('<tr id="row' + i + '"><td><input type="text" class="form-control" name="products[]" list="namelist"><datalist id="namelist" style="width:3rem;"><option value="PC4">Paper Cup 4 oz</option><option value="PC7">Paper Cup 7 oz</option></datalist></td><td><input type="text" class="form-control" name="prices[]" placeholder="0"></td><td><input type="text" class="form-control" name="quantities[]" placeholder="0"></td><td><input type="text" class="form-control" name="discs[]" placeholder="0"></td><td><button id="' + i + '" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button></td></tr>');
                        });

                        $(document).on('click', '.bremove', function () {
                            x--;
                            var button_id = $(this).attr("id");
                            $('#row' + button_id + '').remove();
                        });
                    });
                </script>
                <!-- Theme js-->
                <script src="../../assets/js/script.js"></script>
                <!-- Plugin used-->
</body>

</html>