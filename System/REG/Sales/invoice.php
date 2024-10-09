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
    $query = "SELECT tInvoice FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Cek akses CRUD dan tentukan apakah akses diizinkan
    $hasCRUDAccess = strpos($row['tInvoice'], 'C') !== false || // Create
        strpos($row['tInvoice'], 'R') !== false || // Read
        strpos($row['tInvoice'], 'U') !== false || // Update
        strpos($row['tInvoice'], 'D') !== false;  // Delete
    
    // Jika tidak memiliki akses CRUD, tampilkan pesan dan redirect
    $accessDenied = !$hasCRUDAccess;
    ?>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        function viewInv(str) {
            document.location = "viewInvoice.php?id=" + str.value;
        }

        function editInv(str) {
            document.location = "editInvoice.php?id=" + str.value;
        }
        function getQueryParams() {
            const query = window.location.search.substring(1);
            const params = new URLSearchParams(query);
            return {
                error: params.get('error')
            };
        }
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
        function printInv(button) {
            var invoiceID = button.value;
            var url = "../Process/generate_invoice_pdf.php?InvoiceID=" + invoiceID;
            window.open(url, '_blank');
        }
    </script>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var i = 1;

        $(document).ready(function () {
            $(document).on('click', '.bremove', function () {
                i--;
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });

            $("#buttonGen").click(function () {
                // Get customer data
                var socust = document.getElementById("salesorders").value;
                var soid = socust.split(" - ");

                // Call function to get customer data and then get amount and details
                getCustomerData(soid[0]);

                // After getting SO details, now get PL group
                // Ensure custid is set before calling getPLGroup
                setTimeout(function () {
                    var custId = document.getElementById("custid").value; // Assuming custid is already set
                    getPLGroup(custId);
                }, 500); // Adjust the timeout as needed to ensure `custid` is set
            });

            function getCustomerData(salesOrderID) {
                $.ajax({
                    type: "POST",
                    url: "../Process/getSOCust.php",
                    data: "id=" + salesOrderID,
                    success: function (result) {
                        var res = JSON.parse(result);
                        $.each(res, function (index, value) {
                            i++;
                            document.getElementById("custid").value = value.CustID;
                            document.getElementById("custname").value = value.CustName;
                        });
                    }
                });

                // Call function to get amount
                getAmount(salesOrderID);
            }

            function getAmount(salesOrderID) {
                $.ajax({
                    type: "POST",
                    url: "../Process/getDPCust.php",
                    data: "id=" + salesOrderID,
                    success: function (result) {
                        var res = JSON.parse(result);
                        console.log(res); // For debugging, remove in production

                        var amountField = document.getElementById("amountField");
                        amountField.value = res.map(item => item.Amount).join(', ');
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });

                // After getting amount, now get SO details
                getSODetail(salesOrderID);
            }



            function getSODetail(salesOrderID) {
                $.ajax({
                    type: "POST",
                    url: "../Process/getSODetail.php",
                    data: "id=" + salesOrderID,
                    success: function (result) {
                        $("#tInv #tInvBody tr").remove();
                        var res = JSON.parse(result);
                        $.each(res, function (index, value) {
                            i++;
                            $('#tInv #tInvBody').append(`
                                <tr id="row${i}">
                                    <td style="width:30%">
                                        <input type="text" class="form-control prodlist" name="products[]" value="${value.ProductCD + " - " + value.ProductName}" readonly>
                                    </td>
                                    <td style="width:10%">
                                        <input type="text" class="form-control" name="prices[]" placeholder="0" value="${value.Price}" readonly>
                                    </td>
                                    <td style="width:20%">
                                        <input type="number" class="form-control digits" name="quantities[]" min="1" max="${value.Quantity - value.QuantitySent}" placeholder="Max. ${value.Quantity - value.QuantitySent}" onChange="countSubtotal(this)" required>
                                    </td>
                                    <td style="width:10%">
                                        <input type="text" class="form-control" name="discounts[]" value="${value.Discount}" placeholder="0" readonly>
                                    </td>
                                    <td style="width:20%">
                                        <input type="text" class="form-control" style="border-style:none;" placeholder="0" readonly>
                                    </td>
                                    <td style="width:10%">
                                        <button id="${i}" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button>
                                    </td>
                                </tr>`);
                        });
                    }
                });
            }

            function countSubtotal(x) {
                var harga = x.parentElement.parentElement.cells[1].getElementsByTagName("input")[0].value;
                var jumlah = x.parentElement.parentElement.cells[2].getElementsByTagName("input")[0].value;
                var discount = x.parentElement.parentElement.cells[3].getElementsByTagName("input")[0].value;
                let subtotal = (harga - discount) * jumlah;

                x.parentElement.parentElement.cells[4].getElementsByTagName("input")[0].value = numeral(subtotal).format("0,0.00");
            }

            function getPLGroup(cust) {
                if (cust == "") {
                    document.getElementById("pricelist").innerHTML = "No customer data thrown!";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("pricelist").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "../Process/getPLGroup.php?id=" + cust, true);
                    xmlhttp.send();
                }
            }


            function deleteInv(str) {
                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Invoice dengan kode " + str.value + " akan dihapus dari database!",
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
        });

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
                                if ($_GET["status"] == "success-so") {
                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Selamat! </b>Sales Order baru berhasil disimpan ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "error-so") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Sales Order ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                }
                                if ($_GET["status"] == "success-inv") {
                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                        <p><b> Selamat! </b>Invoice baru berhasil disimpan ke database.</p>
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "error-inv") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Invoice ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "stock-minus") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Fail! </b>Stock produk tidak cukup untuk membuat Invoice</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-6 ps-0">
                                    <h3>INVOICE</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Penjualan</li>
                                        <li class="breadcrumb-item">Invoice</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Container-fluid starts-->
                    <div class="container-fluid <?php echo $accessDenied ? 'hidden' : ''; ?>">
                    <?php endif; ?>
                    <div class="">
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
                                                if ($_GET["status"] == "success-so") {
                                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                    <p><b> Selamat! </b>Sales Order baru berhasil disimpan ke database.</p>
                                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                                } else if ($_GET["status"] == "error-so") {
                                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                    <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Sales Order ke database.</p>
                                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                                }
                                                if ($_GET["status"] == "success-inv") {
                                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                        <p><b> Selamat! </b>Invoice baru berhasil disimpan ke database.</p>
                                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                                } else if ($_GET["status"] == "error-inv") {
                                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                    <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Invoice ke database.</p>
                                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                                }
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <?php
                                                                    $hasAccess = false;
                                                                    $userID = isset($_COOKIE["UserID"]) ? $_COOKIE["UserID"] : '';

                                                                    if (!empty($userID)) {
                                                                        $query_access = "SELECT tInvoice FROM useraccesslevel WHERE UserID = '$userID'";
                                                                        $result_access = mysqli_query($conn, $query_access);

                                                                        if ($result_access) {
                                                                            $row_access = mysqli_fetch_assoc($result_access);
                                                                            $access_level = $row_access['tInvoice'];
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
                                                                    <button class="btn btn-outline-primary"
                                                                        type="button" data-bs-toggle="modal"
                                                                        data-bs-target=".modal-sales-order" <?php echo $hasAccess ? '' : 'disabled'; ?>>
                                                                        <i class="fa fa-plus-circle"></i> New Invoice
                                                                    </button>

                                                                    <div class="modal fade modal-sales-order"
                                                                        tabindex="-1" role="dialog"
                                                                        aria-labelledby="myExtraLargeModal"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-xl">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title" id="myExtraLargeModal">Invoice Baru</h4>
                                                                                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body dark-modal">
                                                                                    <div class="card-body custom-input">
                                                                                        <form class="row g-3" action="../Process/createInvoice.php" method="POST">
                                                                                            <div class="col-3">
                                                                                                <label class="form-label" for="exampleFormControlInput1">Tanggal</label>
                                                                                                <input class="form-control" id="exampleFormControlInput1" type="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                                                                                            </div>
                                                                                            <div class="col-6">
                                                                                                <label class="form-label" for="salesorders">Pilih Sales Order</label>
                                                                                                <input class="form-control" id="salesorders" name="salesorder" list="soOptions" placeholder="Pilih Sales Order --" required>
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
                                                                                                        echo '<option value="' . $row["SalesOrderID"] . ' - ' . $row["CustName"] . '"></option>';
                                                                                                    }
                                                                                                    ?>
                                                                                                </datalist>
                                                                                            </div>
                                                                                            <div class="col-2">
                                                                                                <label class="form-label" for="buttonGen"><i>Generate</i></label>
                                                                                                <button class="form-control btn btn-primary" type="button" id="buttonGen">Generate</button>
                                                                                            </div>
                                                                                            <div class="col-3">
                                                                                                <label class="form-label" for="custid">ID Pelanggan<span style="color:red;">*</span></label>
                                                                                                <input class="form-control" id="custid" name="custid" type="text" readonly>
                                                                                            </div>
                                                                                            <div class="col-3">
                                                                                                <label class="form-label" for="custname">Nama Pelanggan<span style="color:red;">*</span></label>
                                                                                                <input class="form-control" id="custname" name="custname" type="text" readonly>
                                                                                            </div>
                                                                                            <script>
                                                                                                function formatRupiah(element) {
                                                                                                    let angka = element.value.replace(/[^,\d]/g, '');
                                                                                                    let rupiah = angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                                                                    element.value = 'Rp. ' + rupiah;
                                                                                                }
                                                                                                function removeFormat(element) {
                                                                                                    let angka = element.value.replace(/[^\d]/g, '');
                                                                                                    element.value = angka;
                                                                                                }
                                                                                            </script>
                                                                                            <div class="col-3">
                                                                                                <label
                                                                                                    class="form-label"
                                                                                                    for="amountField">
                                                                                                    Jumlah DP <span
                                                                                                        style="color:red;">*</span>
                                                                                                </label>
                                                                                                <input class="form-control" id="amountField" name="amountField" type="text" readonly oninput="formatRupiah(this)" onfocus="removeFormat(this)" onblur="formatRupiah(this)">
                                                                                            </div>
                                                                                            <div class="col-2">
                                                                                                <label class="form-label" for="pricelist"><i>Price List</i></label>
                                                                                                <div id="pricelist">
                                                                                                    -
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <label class="form-label" for="kodejurnal">Kode Akun</label>
                                                                                                <input class="form-control" id="kodeAkun" name="kodeAkun" value="4-1000 | Penjualan" readonly>
                                                                                            </div>

                                                                                            <div class="col-4">
                                                                                                <label class="form-label" for="tipepembayaran">Tipe Pembayaran</label>
                                                                                                <input class="form-control" id="tipepembayaran" name="tipepembayaran" list="payOptions" placeholder="Pembayaran" required>
                                                                                                <datalist id="payOptions">
                                                                                                    <?php
                                                                                                    $query = "SELECT PaymentCD, PaymentName FROM payment";
                                                                                                    $result = mysqli_query($conn, $query);
                                                                                                    while ($row = mysqli_fetch_array($result)) {
                                                                                                        echo '<option value="' . $row["PaymentCD"] . '">' . $row["PaymentName"] . '</option>';
                                                                                                    }
                                                                                                    ?>
                                                                                                </datalist>
                                                                                            </div>

                                                                                            <div class="col-4">
                                                                                                <label class="form-label" for="gudang">Gudang</label>
                                                                                                <input class="form-control" id="gudang" name="gudang" list="gudangOptions" placeholder="Gudang" required>
                                                                                                <datalist id="gudangOptions">
                                                                                                    <?php
                                                                                                    $query = "SELECT WarehCD, WarehName FROM warehouse";
                                                                                                    $result = mysqli_query($conn, $query);
                                                                                                    while ($row = mysqli_fetch_array($result)) {
                                                                                                        echo '<option value="' . $row["WarehCD"] . '">' . $row["WarehName"] . '</option>';
                                                                                                    }
                                                                                                    ?>
                                                                                                </datalist>
                                                                                            </div>
                                                                                            <div class="col-12">
                                                                                                <label class="form-label" for="desc">Keterangan</label>
                                                                                                <input class="form-control" id="desc" name="desc" type="text">
                                                                                            </div>
                                                                                            <hr>
                                                                                            <h3>Detil Order</h3>
                                                                                            <table id="tInv"
                                                                                                class="table"
                                                                                                style="width:100%">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th style="width:30%">Produk</th>
                                                                                                        <th style="width:10%">Harga</th>
                                                                                                        <th style="width:20%">Jumlah</th>
                                                                                                        <th style="width:10%">Discount</th>
                                                                                                        <th style="width:20%">Subtotal</th>
                                                                                                        <th style="width:10%">Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody id="tInvBody">
                                                                                                    <!-- APPEND BY AJAX -->
                                                                                                </tbody>
                                                                                            </table>
                                                                                            <div class="col-12">
                                                                                                <button
                                                                                                    class="btn btn-primary"
                                                                                                    type="submit"
                                                                                                    name="submitInv">Submit</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button class="btn btn-primary dropdown-toggle"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">Menu</button>
                                                                    <ul class="dropdown-menu dropdown-block" id="myTab"
                                                                        role="tablist">
                                                                        <li class="nav-item"><a
                                                                                class="dropdown-item active txt-primary f-w-500 f-18"
                                                                                id="profile-tabs" data-bs-toggle="tab"
                                                                                href="#daftarInv" role="tab"
                                                                                aria-controls="profile"
                                                                                aria-selected="false">Invoice
                                                                                Belum Lunas</a></li>
                                                                        <li class="nav-item"><a
                                                                                class="dropdown-item txt-primary f-w-500 f-18"
                                                                                id="contact-tab" data-bs-toggle="tab"
                                                                                href="#invLunas" role="tab"
                                                                                aria-controls="contact"
                                                                                aria-selected="false">Invoice
                                                                                Lunas</a></li>

                                                                    </ul>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <!-- Form Tanggal -->
                                                                    <form method="GET" action=""
                                                                        class="d-flex flex-wrap align-items-center">
                                                                        <div class="row">
                                                                            <div class="col-md-5">
                                                                                <div class="mb-3 row">
                                                                                    <label class="col-sm-3">Start
                                                                                        Date:</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="date"
                                                                                            class="form-control me-2"
                                                                                            id="startDate"
                                                                                            name="startDate"
                                                                                            value="<?php echo isset($_GET['startDate']) ? htmlspecialchars($_GET['startDate']) : ''; ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <div class="mb-3 row">
                                                                                    <label class="col-sm-3">End
                                                                                        Date:</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="date"
                                                                                            class="form-control me-2"
                                                                                            id="endDate" name="endDate"
                                                                                            value="<?php echo isset($_GET['endDate']) ? htmlspecialchars($_GET['endDate']) : ''; ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <button class="btn btn-primary"
                                                                                    onclick="resetDates()"><i
                                                                                        class="icofont icofont-refresh"
                                                                                        id="resetDates"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <style>
                                                                .btn-outline-secondary {
                                                                    border: none;
                                                                    background: none;
                                                                    padding: 0px;
                                                                }

                                                                .form-group.row {
                                                                    margin-right: 0;
                                                                    margin: 0px;
                                                                    /* Jarak atas */
                                                                }

                                                                @media (max-width: 576px) {
                                                                    .form-group.row {
                                                                        flex-basis: 100%;
                                                                    }
                                                                }
                                                            </style>
                                                            <script>
                                                                var startDate = document.getElementById("startDate");
                                                                var endDate = document.getElementById("endDate");

                                                                // Tambahkan event listener untuk input tanggal
                                                                startDate.addEventListener("change", filterData);
                                                                endDate.addEventListener("change", filterData);

                                                                function filterData() {
                                                                    var startDateValue = startDate.value;
                                                                    var endDateValue = endDate.value;

                                                                    // Validasi apakah kedua tanggal sudah dipilih
                                                                    if (startDateValue && endDateValue) {
                                                                        // Redirect ke halaman dengan parameter GET startDate dan endDate
                                                                        var url = "../Sales/invoice.php?startDate=" + startDateValue + "&endDate=" + endDateValue;
                                                                        window.location.href = url;
                                                                    }
                                                                }

                                                                function resetDates() {
                                                                    // Mengatur nilai input tanggal kembali kosong
                                                                    startDate.value = '';
                                                                    endDate.value = '';
                                                                    // Memuat ulang halaman untuk menghapus parameter tanggal dari URL
                                                                    window.location.href = "../Sales/invoice.php";
                                                                }
                                                            </script>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="tab-content" id="myTabContent">
                                                                <div class="tab-pane fade show active" id="daftarInv"
                                                                    role="tabpanel">
                                                                    <h3>Invoice</h3><small>Status : Belum Lunas</small>
                                                                    <br>
                                                                    <div
                                                                        class="table-responsive custom-scrollbar user-datatable">
                                                                        <table class="display" id="basic-12">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Invoice ID</th>
                                                                                    <th>Tanggal</th>
                                                                                    <th>Pelanggan</th>
                                                                                    <th>Pembayaran</th>
                                                                                    <th>Nominal</th>
                                                                                    <th>Status</th>
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
                                                                                $query_access = "SELECT tInvoice FROM useraccesslevel WHERE UserID = '$creator'";
                                                                                $result_access = mysqli_query($conn, $query_access);
                                                                                $can_update = false;
                                                                                if ($result_access) {
                                                                                    $row_access = mysqli_fetch_assoc($result_access);
                                                                                    $access_level = $row_access['tInvoice'];
                                                                                    if (strpos($access_level, 'R') !== false) {
                                                                                        $can_update = true;
                                                                                    }
                                                                                } else {
                                                                                    die("Error: Gagal mengambil data akses pengguna.");
                                                                                }
                                                                                $whereClause = "";
                                                                                if ($_GET['startDate'] != "" && $_GET['endDate'] != "") {
                                                                                    $startDate = $_GET['startDate'];
                                                                                    $endDate = $_GET['endDate'];
                                                                                    $whereClause = "AND (substr(invh.CreatedOn,1,10) >= '$startDate' AND substr(invh.CreatedOn,1,10) <= '$endDate')";
                                                                                }

                                                                                if (isset($_GET['startDate']) && isset($_GET['endDate']) && $_GET['startDate'] != "" && $_GET['endDate'] != "") {
                                                                                    $startDate = $_GET['startDate'];
                                                                                    $endDate = $_GET['endDate'];
                                                                                    $whereClause = "AND (substr(invh.CreatedOn,1,10) >= '$startDate' AND substr(invh.CreatedOn,1,10) <= '$endDate')";
                                                                                }

                                                                                $queryINV = "SELECT invh.InvoiceID, invh.SalesOrderID, invh.CreatedOn, c.CustName, p.PaymentName, invh.TotalInvoice, invh.InvoiceStatus
                                                                                FROM (invoiceheader invh JOIN customer c JOIN payment p ON invh.CustID=c.CustID AND invh.PaymentCD=p.PaymentCD)
                                                                                WHERE invh.InvoiceStatus=0 $whereClause";
                                                                                $resultINV = mysqli_query($conn, $queryINV);
                                                                                while ($rowINV = mysqli_fetch_array($resultINV)) {
                                                                                    echo '
                                                                                    <tr>
                                                                                        <td>' . $rowINV["InvoiceID"] . '</td>
                                                                                        <td>' . $rowINV["CreatedOn"] . '</td>
                                                                                        <td>' . $rowINV["CustName"] . '</td>
                                                                                        <td>' . $rowINV["PaymentName"] . '</td>
                                                                                        <td>Rp' . number_format($rowINV["TotalInvoice"], 0, '.', ',') . '</td>';
                                                                                    if ($rowINV["InvoiceStatus"] == 0) {
                                                                                        echo '<td><span class="badge badge-light-danger">Belum Lunas</span></td>';
                                                                                    } else if ($rowINV["InvoiceStatus"] == 1) {
                                                                                        echo '<td><span class="badge badge-light-warning">Uang Muka</span></td>';
                                                                                    }

                                                                                    echo '  <td> 
                                                                                        <ul> ';
                                                                                    if ($can_update) {
                                                                                        echo '<button onclick="viewInv(this)" type="button" class="light-card border-primary border b-r-10" value="' . $rowINV["InvoiceID"] . '"><i class="fa fa-eye txt-primary"></i></button>';
                                                                                    }
                                                                                    echo '
                                                                                                <button onclick="editInv(this)" type="button" class="light-card border-warning border b-r-10" value="' . $rowINV["InvoiceID"] . '"><i class="fa fa-pencil-square-o txt-warning"></i></button>
                                                                                                <button onclick="printInv(this)" type="button" class="light-card border-info border b-r-10" value="' . $rowINV["InvoiceID"] . '"><i class="fa fa-print txt-info"></i></button>
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
                                                                <div class="tab-pane fade" id="invLunas"
                                                                    role="tabpanel">
                                                                    <h3>Invoice</h3><small>Status : Lunas</small>
                                                                    <br>
                                                                    <div
                                                                        class="table-responsive custom-scrollbar user-datatable">
                                                                        <table class="display" id="basic-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Invoice ID</th>
                                                                                    <th>Tanggal</th>
                                                                                    <th>Pelanggan</th>
                                                                                    <th>Pembayaran</th>
                                                                                    <th>Nominal</th>
                                                                                    <th>Status</th>
                                                                                    <th>Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $queryINV = "SELECT invh.InvoiceID, invh.SalesOrderID, invh.CreatedOn, c.CustName, p.PaymentName, invh.TotalInvoice, invh.InvoiceStatus
                                                                                FROM (invoiceheader invh JOIN customer c JOIN payment p ON invh.CustID=c.CustID AND invh.PaymentCD=p.PaymentCD)
                                                                                WHERE invh.InvoiceStatus=1 $whereClause";
                                                                                $resultINV = mysqli_query($conn, $queryINV);
                                                                                while ($rowINV = mysqli_fetch_array($resultINV)) {
                                                                                    echo '
                                                                                        <tr>
                                                                                            <td>' . $rowINV["InvoiceID"] . '</td>
                                                                                            <td>' . $rowINV["CreatedOn"] . '</td>
                                                                                            <td>' . $rowINV["CustName"] . '</td>
                                                                                            <td>' . $rowINV["PaymentName"] . '</td>
                                                                                            <td>Rp' . number_format($rowINV["TotalInvoice"], 0, '.', ',') . '</td>';

                                                                                    //if 0 = belum lunas
                                                                                    if ($rowINV["InvoiceStatus"] == 0) {
                                                                                        echo '<td><span class="badge badge-light-danger">Belum Lunas</span></td>';
                                                                                    } else if ($rowINV["InvoiceStatus"] == 1) {
                                                                                        echo '<td><span class="badge badge-light-success">Lunas</span></td>';
                                                                                    }

                                                                                    echo '  <td> 
                                                                                        <ul> 
                                                                                            <button onclick="viewInv(this)" type="button" class="light-card border-primary border b-r-10" value="' . $rowINV["InvoiceID"] . '"><i class="fa fa-eye txt-primary"></i></button>
                                                                                            <button onclick="editInv(this)" type="button" class="light-card border-warning border b-r-10" value="' . $rowINV["InvoiceID"] . '"><i class="fa fa-pencil-square-o txt-warning"></i></button>
                                                                                            <button onclick="printInv(this)" type="button" class="light-card border-info border b-r-10" value="' . $rowINV["InvoiceID"] . '"><i class="fa fa-print txt-info"></i></button>
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
                        <script src="../../assets/js/select2/custom-inputsearch.js"></script>
                        <!-- Plugins JS Ends-->

                        <!-- JS FOR NOTF -->
                        <script src="../../assets/js/notify/index.js"></script>
                        <!-- Theme js-->
                        <script src="../../assets/js/script.js"></script>
                        <!-- Plugin used-->
</body>

</html>