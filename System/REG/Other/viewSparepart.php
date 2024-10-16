<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    include "../../DBConnection.php";
    ?>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function withPPN() {
            var cb = document.getElementById("ppnCheck");
            var bp = document.getElementById("buyprice");
            var tb = document.getElementById("usetax");

            if (cb.checked == true) {
                tb.value = Math.round(bp.value * 1.11);
            } else {
                tb.value = "-";
            }
        }

        function editPart(x) {
            document.location = "editSparepart.php?partcd=" + x.value;
        }
    </script>
</head>

<body>
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
                                    <p><b> Selamat! </b>Spare Part baru berhasil disimpan ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                            } else if ($_GET["status"] == "error") {
                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                    <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Spare Part ke database.</p>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-6 ps-0">
                                <h3>SPARE PART</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Barang</li>
                                    <li class="breadcrumb-item">Spare Part</li>
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
                                <div class="card-body">
                                    <?php
                                    $queryp = "SELECT * FROM sparepart WHERE PartCD='" . $_GET["partcd"] . "'";
                                    $resultp = mysqli_query($conn, $queryp);
                                    $part = mysqli_fetch_assoc($resultp);
                                    ?>
                                    <form class="row g-3" action="../Process/editSparepart.php" method="POST">
                                        <div class="col-3">
                                            <label class="form-label" for="urutanreport">Urutan<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="urutanreport" name="urutanreport"
                                                type="text" value="<?php echo $part["Sequence"]; ?>" placeholder="1"
                                                readonly>
                                        </div>
                                        <div class="col-3">
                                            <label class="form-label" for="kodebarang">Kode Barang<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="kodebarang" name="kodebarang" type="text"
                                                value="<?php echo $part["PartCD"]; ?>" placeholder="ABXXXX" readonly>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="namabarang">Nama Barang<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="namabarang" name="namabarang" type="text"
                                                value="<?php echo $part["PartName"]; ?>" placeholder="Spare Part"
                                                readonly>
                                        </div>
                                        <div class="col-3">
                                            <label class="col-sm-12 col-form-label" for="satuanpertama">Satuan<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="satuanpertama" list="satuanOptions"
                                                name="satuan" value="<?php echo $part["UnitCD"]; ?>"
                                                placeholder="Satuan" readonly>
                                            <datalist id="satuanOptions">
                                                <?php
                                                $query = "SELECT UnitCD,UnitName FROM unit WHERE Status='1'";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo '<option value="' . $row["UnitCD"] . '">' . $row["UnitName"] . '</option>';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                        <div class="col-3">
                                            <label class="col-sm-12 col-form-label" for="group">Group<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="group" name="group" list="groupOptions"
                                                value="<?php echo $part["GroupCD"]; ?>" placeholder="Group" readonly>
                                            <datalist id="groupOptions">
                                                <?php
                                                $query = "SELECT GroupCD,GroupName FROM groups WHERE Status='1'";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo '<option value="' . $row["GroupCD"] . '">' . $row["GroupName"] . '</option>';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                        <div class="col-3">
                                            <label class="col-sm-12 col-form-label" for="kategori">Kategori<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="kategori" name="kategori"
                                                list="kategoriOptions" value="<?php echo $part["CategoryCD"]; ?>"
                                                placeholder="Kategori" readonly>
                                            <datalist id="kategoriOptions">
                                                <?php
                                                $query = "SELECT CategoryCD,CategoryName FROM category WHERE Status='1'";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo '<option value="' . $row["CategoryCD"] . '">' . $row["CategoryName"] . '</option>';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                        <div class="col-3">
                                            <label class="col-sm-12 col-form-label" for="supplier">Supplier<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="supplier" name="supplier"
                                                list="supplierOptions" value="<?php echo $part["SupplierNum"]; ?>"
                                                placeholder="Supplier" readonly>
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
                                        <div class="col-6">
                                            <label class="form-label" for="keterangan1">Keterangan 1</label>
                                            <input class="form-control" id="keterangan1" name="keterangan1" type="text"
                                                value="<?php echo $part["Desc_1"]; ?>" placeholder="..." readonly>
                                        </div>
                                        <div class="col-3">
                                            <label class="form-label" for="buyprice">Harga Beli<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="buyprice" name="buyprice" type="text"
                                                value="<?php echo $part["BuyPrice"]; ?>" placeholder="0" readonly>
                                        </div>
                                        <div class="col-3">
                                            <input id="ppnCheck" type="checkbox" name="tax" value="1" <?php if ($part["Tax"] == 1) {
                                                echo "checked";
                                            } ?> onclick="withPPN()" disabled>
                                            <label class="form-label" for="usetax">PPN</label>
                                            <input class="form-control" id="usetax" name="usetax" type="text" <?php if ($part["Tax"] == 0) {
                                                echo 'value="-"';
                                            } else {
                                                echo 'value="' . $part["BuyPrice"] * 1.11 . '"';
                                            } ?> readonly>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="keterangan2">Keterangan 2</label>
                                            <input class="form-control" id="keterangan2" name="keterangan2" type="text"
                                                value="<?php echo $part["Desc_2"]; ?>" placeholder="..." readonly>
                                        </div>
                                        <div class="col-6"></div>
                                        <div class="col-6">
                                            <label class="form-label" for="keterangan3">Keterangan 3</label>
                                            <input class="form-control" id="keterangan3" name="keterangan3" type="text"
                                                value="<?php echo $part["Desc_3"]; ?>" placeholder="..." readonly>
                                        </div>
                                        <div class="col-6"></div>
                                        <hr>
                                        <div class="col-4">
                                            <div class="card-wrapper border rounded-3 checkbox-checked">
                                                <h6 class="sub-title">Status?<span style="color:red;">*</span></h6>
                                                <div class="radio-form">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="flexRadioDefault3"
                                                            type="radio" name="Status" value="1" <?php if ($part["Status"] == 1) {
                                                                echo "checked";
                                                            } ?> disabled>
                                                        <label class="form-check-label"
                                                            for="flexRadioDefault3">Active</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="flexRadioDefault4"
                                                            type="radio" name="Status" value="0" <?php if ($part["Status"] == 0) {
                                                                echo "checked";
                                                            } ?> disabled>
                                                        <label class="form-check-label"
                                                            for="flexRadioDefault4">Inactive</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" id="flexSwitchCheckDefault"
                                                    type="checkbox" role="switch" required>
                                                <label class="form-check-label" for="flexSwitchCheckDefault">Apakah
                                                    informasi diatas sudah benar?</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <a class="btn btn-warning" href="sparepart.php">Back</a>
                                            <button class="btn btn-info" type="button" onclick="editPart(this)"
                                                value="<?php echo $_GET["partcd"] ?>">Edit</button>
                                        </div>
                                    </form>
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
                                    <use href="../../../assets/svg/icon-sprite.svg#heart"></use>
                                </svg>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
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
    <script src="../../../assets/js/height-equal.js"></script>
    <script src="../../../assets/js/notify/bootstrap-notify.min.js"></script>
    <script src="../../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../assets/js/datatable/datatables/datatable.custom.js"></script>
    <script src="../../../assets/js/tooltip-init.js"></script>
    <script src="../../../assets/js/modalpage/validation-modal.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="../../../assets/js/script.js"></script>
    <!-- Plugin used-->
</body>

</html>