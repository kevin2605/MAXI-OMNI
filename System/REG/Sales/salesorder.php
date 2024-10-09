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
    $query = "SELECT tSalesOrder FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Cek akses CRUD dan tentukan apakah akses diizinkan
    $hasCRUDAccess = strpos($row['tSalesOrder'], 'C') !== false || // Create
        strpos($row['tSalesOrder'], 'R') !== false || // Read
        strpos($row['tSalesOrder'], 'U') !== false || // Update
        strpos($row['tSalesOrder'], 'D') !== false;  // Delete
    
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
                    text: 'Anda tidak memiliki akses untuk Approve.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var i = 1;
        function appendProductTable(x) {
            i++;
            $('#dinamis #dbody').append(`
                <tr id="row${i}">
                    <td>
                        <input type="text" class="form-control prodlist" onChange="appendProductTable(this)" name="products[]" list="namelist" required>
                            <datalist id="namelist" style="width:3rem;">
                                <?php $queryp = "SELECT * FROM product";
                                $resultp = mysqli_query($conn, $queryp);
                                while ($rowp = mysqli_fetch_array($resultp)) {
                                    echo '<option value="' . $rowp["ProductCD"] . '">' . $rowp["ProductName"] . '</option>';
                                } ?>
                            </datalist>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="prices[]" placeholder="0" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control digits" name="quantities[]" placeholder="0" required>
                    </td>
                    <td>
                        <input type="number" class="form-control digits" name="discounts[]" placeholder="0">
                    </td>
                    <td>
                        <button id="${i}" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button>
                    </td>
                </tr>`);

            //get price
            var plcd = document.getElementById("pricelistcd").value;
            $.ajax({
                type: "POST",
                url: "../Process/getProductPrice.php",
                data: "plcd=" + plcd + "&prodcd=" + x.value,
                success: function (result) {
                    var res = JSON.parse(result);
                    $.each(res, function (index, value) {
                        x.parentElement.parentElement.cells[1].getElementsByTagName("input")[0].value = value.Price;
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

        function viewSales(str) {
            document.location = "viewSalesOrder.php?id=" + str.value;
        }

        function editSales(str) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Sales Order dengan kode " + str.value + " akan di edit!",
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
    </script>


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
                                <div class="col-sm-6 ps-0">
                                    <h3>SALES ORDER</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Penjualan</li>
                                        <li class="breadcrumb-item">Sales Order</li>
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
                                            <div class="col-sm-6 ps-0">
                                                <h3>SALES ORDER</h3>
                                            </div>
                                            <div class="col-sm-6 pe-0">
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="index.html">
                                                            <svg class="stroke-icon">
                                                                <use
                                                                    href="../../assets/svg/icon-sprite.svg#stroke-home">
                                                                </use>
                                                            </svg></a></li>
                                                    <li class="breadcrumb-item">Penjualan</li>
                                                    <li class="breadcrumb-item">Sales Order</li>
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
                                                        $hasAccess = false;
                                                        $userID = isset($_COOKIE["UserID"]) ? $_COOKIE["UserID"] : '';
                                                        if (!empty($userID)) {
                                                            $query_access = "SELECT tSalesOrder FROM useraccesslevel WHERE UserID = '$userID'";
                                                            $result_access = mysqli_query($conn, $query_access);
                                                            if ($result_access) {
                                                                $row_access = mysqli_fetch_assoc($result_access);
                                                                $access_level = $row_access['tSalesOrder'];
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
                                                        <!-- Tombol untuk membuka modal -->
                                                        <button class="btn btn-outline-primary" type="button"
                                                            data-bs-toggle="modal" data-bs-target=".modal-sales-order"
                                                            <?php echo $hasAccess ? '' : 'disabled'; ?>>
                                                            <i class="fa fa-plus-circle"></i> New Sales
                                                            Order
                                                        </button>
                                                        <div class="modal fade modal-sales-order" tabindex="-1"
                                                            role="dialog" aria-labelledby="myExtraLargeModal"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="myExtraLargeModal">
                                                                            Sales Order
                                                                            Baru</h4>
                                                                        <button class="btn-close py-0" type="button"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body dark-modal">
                                                                        <div class="card-body custom-input">
                                                                            <form class="row g-3"
                                                                                action="../Process/createSalesOrder.php"
                                                                                method="POST">
                                                                                <div class="col-4">
                                                                                    <label class="form-label"
                                                                                        for="soid">SO ID<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="soid" type="text"
                                                                                        placeholder="auto-generated"
                                                                                        aria-label="First name"
                                                                                        readonly>
                                                                                </div>
                                                                                <div class="col-4">
                                                                                    <label class="form-label"
                                                                                        for="exampleFormControlInput1">Tanggal<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="exampleFormControlInput1"
                                                                                        type="date"
                                                                                        value="<?php echo date('Y-m-d'); ?>"
                                                                                        readonly>
                                                                                </div>
                                                                                <div class="col-4">
                                                                                    <label class="form-label"
                                                                                        for="creator">Pembuat
                                                                                        SO<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="creator" name="creator"
                                                                                        type="text"
                                                                                        value="<?php echo $_COOKIE["UserID"] . ' - ' . $_COOKIE["Name"] ?>"
                                                                                        readonly>
                                                                                </div>
                                                                                <div class="col-4">
                                                                                    <label class="form-label"
                                                                                        for="customer">Pelanggan<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="customer" name="customer"
                                                                                        list="custOptions"
                                                                                        placeholder="-- Pilih Pelanggan --"
                                                                                        onchange="getPLGroup(this.value)"
                                                                                        required>
                                                                                    <datalist id="custOptions">
                                                                                        <?php
                                                                                        $queryc = "SELECT * FROM customer";
                                                                                        $resultc = mysqli_query($conn, $queryc);
                                                                                        while ($rowc = mysqli_fetch_array($resultc)) {
                                                                                            echo '<option value="' . $rowc["CustID"] . '">' . $rowc["CustName"] . '</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
                                                                                </div>
                                                                                <div class="col-4">
                                                                                    <label class="form-label"
                                                                                        for="pricelist"><i>Price
                                                                                            List</i></label>
                                                                                    <div id="pricelist">
                                                                                        -
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-4">
                                                                                    <label class="form-label"
                                                                                        for="marketing">Marketing<span
                                                                                            style="color:red;">*</span></label>
                                                                                    <input class="form-control"
                                                                                        id="marketing" name="marketing"
                                                                                        list="marketingOptions"
                                                                                        placeholder="-- Pilih Marketing --"
                                                                                        required>
                                                                                    <datalist id="marketingOptions">
                                                                                        <?php
                                                                                        $querym = "SELECT * FROM systemuser WHERE SUBSTR(UserID,1,3) = 'MKT'";
                                                                                        $resultm = mysqli_query($conn, $querym);
                                                                                        while ($rowm = mysqli_fetch_array($resultm)) {
                                                                                            echo '<option value="' . $rowm["UserID"] . '">' . $rowm["Name"] . '</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <label class="form-label"
                                                                                        for="logo">Logo</label>
                                                                                    <input class="form-control"
                                                                                        id="logo" name="logo"
                                                                                        list="logoOptions">
                                                                                    <datalist id="logoOptions">
                                                                                        <?php
                                                                                        $querym = "SELECT * FROM logo";
                                                                                        $resultm = mysqli_query($conn, $querym);
                                                                                        while ($rowm = mysqli_fetch_array($resultm)) {
                                                                                            echo '<option value="' . $rowm["LogoName"] . '"></option>';
                                                                                        }
                                                                                        ?>
                                                                                    </datalist>
                                                                                </div>
                                                                                <div class="col-9">
                                                                                    <label class="form-label"
                                                                                        for="desc">Keterangan</label>
                                                                                    <input class="form-control"
                                                                                        id="desc" name="desc"
                                                                                        type="text" placeholder="...">
                                                                                </div>
                                                                                <hr>
                                                                                <h3>Detil Order</h3>
                                                                                <table id="dinamis" class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th scope="col">Produk</th>
                                                                                            <th scope="col">Harga</th>
                                                                                            <th scope="col">Jumlah</th>
                                                                                            <th scope="col">Diskon</th>
                                                                                            <th scope="col">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="dbody">
                                                                                        <tr id="row1">
                                                                                            <td>
                                                                                                <input type="text"
                                                                                                    class="form-control prodlist"
                                                                                                    name="products[]"
                                                                                                    list="prodOptions"
                                                                                                    onChange="appendProductTable(this)"
                                                                                                    required>
                                                                                                <datalist
                                                                                                    id="prodOptions">
                                                                                                    <?php
                                                                                                    $queryp = "SELECT * FROM product";
                                                                                                    $resultp = mysqli_query($conn, $queryp);
                                                                                                    while ($rowp = mysqli_fetch_array($resultp)) {
                                                                                                        echo '<option value="' . $rowp["ProductCD"] . '">' . $rowp["ProductName"] . '</option>';
                                                                                                    }
                                                                                                    ?>
                                                                                                </datalist>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    name="prices[]"
                                                                                                    placeholder="0"
                                                                                                    readonly>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="number"
                                                                                                    class="form-control digits"
                                                                                                    name="quantities[]"
                                                                                                    placeholder="0"
                                                                                                    required>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="number"
                                                                                                    class="form-control digits"
                                                                                                    name="discounts[]"
                                                                                                    placeholder="0">
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
                                                                                        type="submit"
                                                                                        name="submitSO">Submit</button>
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
                                                                    id="home-tab" data-bs-toggle="tab" href="#SOPending"
                                                                    role="tab" aria-controls="home"
                                                                    aria-selected="true">SO Pending</a></li>
                                                            <li class="nav-item"><a
                                                                    class="dropdown-item txt-primary f-w-500 f-18"
                                                                    id="profile-tabs" data-bs-toggle="tab"
                                                                    href="#SOComplete" role="tab"
                                                                    aria-controls="profile" aria-selected="false">SO
                                                                    Complete</a></li>
                                                            <li class="nav-item"><a
                                                                    class="dropdown-item txt-primary f-w-500 f-18"
                                                                    id="close-tabs" data-bs-toggle="tab" href="#SOClose"
                                                                    role="tab" aria-controls="close"
                                                                    aria-selected="false">SO Closed</a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <!-- Form Tanggal -->
                                                        <form method="GET" action=""
                                                            class="d-flex flex-wrap align-items-center">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <div class="mb-3 row">
                                                                        <label class="col-sm-3">Start Date:</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="date" class="form-control me-2"
                                                                                id="startDate" name="startDate"
                                                                                value="<?php echo isset($_GET['startDate']) ? htmlspecialchars($_GET['startDate']) : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="mb-3 row">
                                                                        <label class="col-sm-3">End Date:</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="date" class="form-control me-2"
                                                                                id="endDate" name="endDate"
                                                                                value="<?php echo isset($_GET['endDate']) ? htmlspecialchars($_GET['endDate']) : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button class="btn btn-primary"
                                                                        onclick="resetDates()"><i
                                                                            class="icofont icofont-refresh"
                                                                            id="resetDatesButton"></i></button>
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
                                                            var url = "../Sales/salesorder.php?startDate=" + startDateValue + "&endDate=" + endDateValue;
                                                            window.location.href = url;
                                                        }
                                                    }

                                                    function resetDates() {
                                                        // Mengatur nilai input tanggal kembali kosong
                                                        startDate.value = '';
                                                        endDate.value = '';

                                                        // Memuat ulang halaman untuk menghapus parameter tanggal dari URL
                                                        window.location.href = "../Sales/salesorder.php";
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="tab-content" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="SOPending"
                                                        role="tabpanel">
                                                        <h3>Sales Order</h3><small>Status : Pending</small>
                                                        <br><br>
                                                        <div class="table-responsive custom-scrollbar user-datatable">
                                                            <!-- Isi tabel Anda -->
                                                            <table class="display" id="basic-12">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sales Order ID</th>
                                                                        <th>Tanggal</th>
                                                                        <th>Pelanggan</th>
                                                                        <th>Approval</th>
                                                                        <th>Status?</th>
                                                                        <th>Complete</th>
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
                                                                    $query_access = "SELECT tSalesOrder FROM useraccesslevel WHERE UserID = '$creator'";
                                                                    $result_access = mysqli_query($conn, $query_access);
                                                                    $can_update = false;
                                                                    if ($result_access) {
                                                                        $row_access = mysqli_fetch_assoc($result_access);
                                                                        $access_level = $row_access['tSalesOrder'];
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
                                                                        $whereClause = "AND (substr(soh.CreatedOn,1,10) >= '$startDate' AND substr(soh.CreatedOn,1,10) <= '$endDate')";
                                                                    }

                                                                    $querySO = "SELECT soh.SalesOrderID, soh.CreatedOn, c.CustName, soh.Approval, soh.ApprovalStatus, soh.Finish
                                                                    FROM salesorderheader soh
                                                                    JOIN customer c ON soh.CustID=c.CustID
                                                                    WHERE soh.Finish = 0 $whereClause";
                                                                    $resultSO = mysqli_query($conn, $querySO);
                                                                    while ($rowSO = mysqli_fetch_array($resultSO)) {
                                                                        echo '
                                                                        <tr>
                                                                            <td>' . $rowSO["SalesOrderID"] . '</td>
                                                                            <td>' . $rowSO["CreatedOn"] . '</td>
                                                                            <td>' . $rowSO["CustName"] . '</td>';

                                                                        //approval
                                                                        if ($rowSO["Approval"] == 0) {
                                                                            echo '<td><span class="badge badge-light-success">No</span></td>';
                                                                        } else {
                                                                            echo '<td><span class="badge badge-light-danger">Yes</span></td>';
                                                                        }

                                                                        if ($rowSO["ApprovalStatus"] == "Approved") {
                                                                            echo '<td><span class="badge badge-light-success">Approved</span></td>';
                                                                        } else if ($rowSO["ApprovalStatus"] == "Pending") {
                                                                            echo '<td><span class="badge badge-light-warning">Pending</span></td>';
                                                                        } else if ($rowSO["ApprovalStatus"] == "Reject") {
                                                                            echo '<td><span class="badge badge-light-danger">Reject</span></td>';
                                                                        }

                                                                        //complete or not
                                                                        if ($rowSO["Finish"] == 1) {
                                                                            echo '<td><span class="badge badge-light-success">Complete</span></td>';
                                                                        } else if ($rowSO["Finish"] == 0) {
                                                                            echo '<td><span class="badge badge-light-danger">Pending</span></td>';
                                                                        } else if ($rowSO["Finish"] == 2) {
                                                                            echo '<td><span class="badge badge-light-danger">Cancel</span></td>';
                                                                        }

                                                                        echo '<td> 
                                                                            <ul>';
                                                                        if ($can_update) {
                                                                            echo '<button onclick="viewSales(this)" type="button" class="light-card border-primary border b-r-10" value="' . $rowSO["SalesOrderID"] . '"><i class="fa fa-eye txt-primary"></i></button>';
                                                                        }

                                                                        echo ' 
                                                                                    
                                                                                    <button onclick="editSales(this)" type="button" class="light-card border-warning border b-r-10" value="' . $rowSO["SalesOrderID"] . '"><i class="fa fa-pencil-square-o txt-warning"></i></button>
                                                                                    <button onclick="printSales(this)" type="button" class="light-card border-info border b-r-10" value="' . $rowSO["SalesOrderID"] . '"><i class="fa fa-print txt-info"></i></button>
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
                                                    <div class="tab-pane fade" id="SOComplete" role="tabpanel">
                                                        <h3>Sales Order</h3><small>Status : Complete</small>
                                                        <br><br>
                                                        <div class="table-responsive custom-scrollbar">
                                                            <table class="display" id="basic-1">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sales Order ID</th>
                                                                        <th>Tanggal</th>
                                                                        <th>Pelanggan</th>
                                                                        <th>Status</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $querySO = "SELECT soh.SalesOrderID, soh.CreatedOn, c.CustName, soh.LastEdit, soh.Finish
                                                                     FROM (salesorderheader soh JOIN customer c ON soh.CustID=c.CustID) WHERE soh.Finish=1 $whereClause";
                                                                    $resultSO = mysqli_query($conn, $querySO);
                                                                    while ($rowSO = mysqli_fetch_array($resultSO)) {
                                                                        echo '
                                                                            <tr>
                                                                                <td>' . $rowSO["SalesOrderID"] . '</td>
                                                                                <td>' . $rowSO["CreatedOn"] . '</td>
                                                                                <td>' . $rowSO["CustName"] . '</td>';
                                                                        //complete or not
                                                                        if ($rowSO["Finish"] == 1) {
                                                                            echo '<td><span class="badge badge-light-success">Complete</span></td>';
                                                                        } else {
                                                                            echo '<td><span class="badge badge-light-danger">Pending</span></td>';
                                                                        }
                                                                        echo '<td> 
                                                                                <ul> 
                                                                                    <button onclick="viewSales(this)" type="button" class="light-card border-primary border b-r-10" value="' . $rowSO["SalesOrderID"] . '"><i class="fa fa-eye txt-primary"></i></button>
                                                                                    <button onclick="printSales(this)" type="button" class="light-card border-info border b-r-10" value="' . $rowSO["SalesOrderID"] . '"><i class="fa fa-print txt-info"></i></button>
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
                                                    <div class="tab-pane fade" id="SOClose" role="tabpanel">
                                                        <h3>Sales Order</h3><small>Status : Closed</small>
                                                        <br><br>
                                                        <div class="table-responsive custom-scrollbar">
                                                            <table class="display" id="basic-6">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sales Order ID</th>
                                                                        <th>Tanggal</th>
                                                                        <th>Pelanggan</th>
                                                                        <th>Status</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $querySO = "SELECT soh.SalesOrderID, soh.CreatedOn, c.CustName, soh.LastEdit, soh.Finish
                                                                     FROM (salesorderheader soh JOIN customer c ON soh.CustID=c.CustID) WHERE soh.Finish=2 $whereClause";
                                                                    $resultSO = mysqli_query($conn, $querySO);
                                                                    while ($rowSO = mysqli_fetch_array($resultSO)) {
                                                                        echo '
                                                                    <tr>
                                                                        <td>' . $rowSO["SalesOrderID"] . '</td>
                                                                        <td>' . $rowSO["CreatedOn"] . '</td>
                                                                        <td>' . $rowSO["CustName"] . '</td>
                                                                        <td><span class="badge badge-light-danger">Closed</span></td>
                                                                        <td> 
                                                                            <ul> 
                                                                                <button onclick="viewSales(this)" type="button" class="light-card border-primary border b-r-10" value="' . $rowSO["SalesOrderID"] . '"><i class="fa fa-eye txt-primary"></i></button>
                                                                                <button onclick="printSales(this)" type="button" class="light-card border-info border b-r-10" value="' . $rowSO["SalesOrderID"] . '"><i class="fa fa-print txt-info"></i></button>
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