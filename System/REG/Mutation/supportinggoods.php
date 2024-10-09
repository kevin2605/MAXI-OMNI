<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";

    session_start();
    include "../DBConnection.php";
    $userID = $_COOKIE['UserID'];

    $query = "SELECT mutasispp FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $hasCRUDAccess = strpos($row['mutasispp'], 'C') !== false || // Create
        strpos($row['mutasispp'], 'R') !== false || // Read
        strpos($row['mutasispp'], 'U') !== false || // Update
        strpos($row['mutasispp'], 'D') !== false;  // Delete
    
    $accessDenied = !$hasCRUDAccess;
    ?>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        var i = 1;

        function appendProductTable(x) {
            i++;
            $('#dinamis #dbody').append(`
                <tr id="row${i}">
                    <td style="width:30%">
                        <input type="text" class="form-control prodlist" name="products[]" list="prodOptions" onChange="appendProductTable(this)" required>
                        <datalist id="prodOptions">
                            
                        </datalist>
                    </td>
                    <td style="width:15%">
                        <input type="number" class="form-control flowin" name="flowin[]" placeholder="0" oninput="disableOtherInput(this)">
                    </td>
                    <td style="width:15%">
                        <input type="number" class="form-control flowout" name="flowout[]" placeholder="0" oninput="disableOtherInput(this)">
                    </td>
                    <td style="width:10%">
                        <input type="text" class="form-control" name="units[]" readonly>
                    </td>
                    <td style="width:35%">
                        <input type="text" class="form-control" name="descriptions[]">
                    </td>
                    <td>
                        <button id="${i}" type="button" class="btn btn-danger bremove" onclick="removeRow(${i})">
                            <i class="icofont icofont-close-line-circled"></i>
                        </button>
                    </td>
                </tr>
            `);

            $.ajax({
                type: "POST",
                url: "../Process/getunitbpp.php",
                data: { prodcd: x.value },
                success: function (result) {
                    var res = JSON.parse(result);
                    $.each(res, function (index, value) {
                        x.parentElement.parentElement.cells[3].getElementsByTagName("input")[0].value = value.UnitCD;
                    });
                }
            });
        }
        $(document).ready(function () {
            $(document).on('click', '.bremove', function () {
                var button_id = $(this).attr("id");
                $('#row' + button_id).remove();
                i--;
            });
        });
        function disableOtherInput(currentInput) {
            let row = currentInput.closest('tr');
            let flowinInput = row.querySelector('.flowin');
            let flowoutInput = row.querySelector('.flowout');

            if (currentInput === flowinInput && flowinInput.value !== '') {
                flowoutInput.disabled = true;
            } else if (currentInput === flowoutInput && flowoutInput.value !== '') {
                flowinInput.disabled = true;
            }
            if (flowinInput.value === '') {
                flowoutInput.disabled = false;
            }
            if (flowoutInput.value === '') {
                flowinInput.disabled = false;
            }
        }

        function viewMut(x) {
            document.location = "view-mutation.php?id=" + x.value;
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
        </script> <!-- loader starts-->
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
                                if ($_GET["status"] == "new-success") {
                                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Selamat! </b>Mutasi baru berhasil disimpan ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                } else if ($_GET["status"] == "error") {
                                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Mutasi ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-6 ps-0">
                                    <h3>MUTASI BOTTOM</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Mutasi</li>
                                        <li class="breadcrumb-item">Bottom</li>
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
                                            if ($_GET["status"] == "new-success") {
                                                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Selamat! </b>Mutasi baru berhasil disimpan ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                            } else if ($_GET["status"] == "error") {
                                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                                <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Mutasi ke database.</p>
                                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                            }
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-6 ps-0">
                                                <h3>MUTASI PENUNJANG PRODUKSI</h3>
                                            </div>
                                            <div class="col-sm-6 pe-0">
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="index.html">
                                                            <svg class="stroke-icon">
                                                                <use
                                                                    href="../../assets/svg/icon-sprite.svg#stroke-home">
                                                                </use>
                                                            </svg></a></li>
                                                    <li class="breadcrumb-item">Mutasi</li>
                                                    <li class="breadcrumb-item">Penunjang Produksi</li>
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
                                                $canUpdate = false;
                                                if (!empty($userID)) {
                                                    $query_access = "SELECT mutasispp FROM useraccesslevel WHERE UserID = '$userID'";
                                                    $result_access = mysqli_query($conn, $query_access);
                                                    if ($result_access) {
                                                        $row_access = mysqli_fetch_assoc($result_access);
                                                        $access_level = $row_access['mutasispp'];
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

                                                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                                                    aria-labelledby="myExtraLargeModal" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myExtraLargeModal">Form
                                                                    Mutasi Penunjang Produksi
                                                                </h4>
                                                                <button class="btn-close py-0" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body dark-modal">
                                                                <div class="card-body custom-input">
                                                                    <form class="row g-3"
                                                                        action="../Process/createmutationbpp.php"
                                                                        method="POST">
                                                                        <div class="col-4">
                                                                            <label class="form-label" for="mutid">Mutasi
                                                                                ID<span
                                                                                    style="color:red;">*</span></label>
                                                                            <input class="form-control" id="mutid"
                                                                                name="mutid" type="text"
                                                                                placeholder="auto-generated" readonly>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <label class="form-label"
                                                                                for="tglmutasi">Tanggal<span
                                                                                    style="color:red;">*</span></label>
                                                                            <input class="form-control" id="tglmutasi"
                                                                                name="tglmutasi" type="text"
                                                                                value="<?php echo date('Y-m-d'); ?>"
                                                                                readonly>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <label class="form-label"
                                                                                for="creator">Dibuat Oleh<span
                                                                                    style="color:red;">*</span></label>
                                                                            <input class="form-control" id="creator"
                                                                                name="creator" type="text"
                                                                                value="<?php echo $_COOKIE["UserID"] . ' - ' . $_COOKIE["Name"] ?>"
                                                                                readonly>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="form-label"
                                                                                for="desc">Keterangan</label>
                                                                            <textarea class="form-control" id="desc"
                                                                                name="desc" rows="2"></textarea>
                                                                        </div>
                                                                        <hr>
                                                                        <h3>Detil Mutasi</h3>
                                                                        <table id="dinamis" class="table"
                                                                            style="width:100%">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="width:30%">Nama Barang
                                                                                    </th>
                                                                                    <th style="width:15%">Masuk</th>
                                                                                    <th style="width:15%">Keluar</th>
                                                                                    <th style="width:10%">Satuan</th>
                                                                                    <th style="width:35%">Keterangan
                                                                                    </th>
                                                                                    <th style="width:10%">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="dbody">
                                                                                <tr id="row1">
                                                                                    <td style="width:30%">
                                                                                        <input type="text"
                                                                                            class="form-control prodlist"
                                                                                            name="products[]"
                                                                                            list="prodOptions"
                                                                                            onChange="appendProductTable(this)"
                                                                                            required>
                                                                                        <datalist id="prodOptions">
                                                                                            <?php
                                                                                            $queryp = "SELECT GoodsName FROM supportinggoods WHERE Status=1";
                                                                                            $resultp = mysqli_query($conn, $queryp);
                                                                                            while ($rowp = mysqli_fetch_array($resultp)) {
                                                                                                echo '<option value="' . $rowp["GoodsName"] . '">' . $rowp["GoodsName"] . '</option>';
                                                                                            }
                                                                                            ?>
                                                                                        </datalist>
                                                                                    </td>
                                                                                    <td style="width:15%">
                                                                                        <input type="number"
                                                                                            class="form-control flowin"
                                                                                            name="flowin[]"
                                                                                            placeholder="0"
                                                                                            oninput="disableOtherInput(this)">
                                                                                    </td>
                                                                                    <td style="width:15%">
                                                                                        <input type="number"
                                                                                            class="form-control flowout"
                                                                                            name="flowout[]"
                                                                                            placeholder="0"
                                                                                            oninput="disableOtherInput(this)">
                                                                                    </td>
                                                                                    <td style="width:10%">
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="units[]"
                                                                                            placeholder="" readonly>
                                                                                    </td>
                                                                                    <td style="width:35%">
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="descriptions[]">
                                                                                    </td>
                                                                                    <td style="width:10%">
                                                                                        <!-- Action buttons if needed -->
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
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
                                                <hr>
                                                <h3>Daftar Mutasi Barang</h3>
                                                <div class="table-responsive custom-scrollbar user-datatable">
                                                    <table class="display" id="basic-12">
                                                        <thead>
                                                            <tr>
                                                                <th>Mutasi ID</th>
                                                                <th>Tanggal</th>
                                                                <th>Keterangan</th>
                                                                <th>Dibuat Oleh</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $queryM = "SELECT muh.MutationID, muh.CreatedOn, muh.Description, su.Name
                                                            FROM (mutationheader muh 
                                                            JOIN systemuser su ON muh.CreatedBy = su.UserID)
                                                            WHERE muh.CategoryCD = 'BPP'";
                                                            $resultM = mysqli_query($conn, $queryM);
                                                            while ($rowM = mysqli_fetch_array($resultM)) {
                                                                echo '<tr>
                                                                <td>' . $rowM["MutationID"] . '</td>
                                                                <td>' . $rowM["CreatedOn"] . '</td>
                                                                <td>' . $rowM["Description"] . '</td>
                                                                <td>' . $rowM["Name"] . '</td>
                                                                <td> 
                                                                    <ul class="action"> 
                                                                        <button onclick="viewMut(this)" type="button" class="light-card border-primary border b-r-10" value="' . $rowM["MutationID"] . '"><i class="fa fa-eye txt-primary"></i></button>
                                                                    </ul>
                                                                </td>
                                                            </tr>';
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