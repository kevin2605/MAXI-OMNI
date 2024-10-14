<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    include "../../DBConnection.php";
    ?>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- script sweetaler2 -->
    <script>
        function editProduct(str) {
            document.location = "edit-product.php?prodcd=" + str.value;
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
                        <div class="row">
                            <div class="col-sm-6 ps-0">
                                <h3>EDIT BARANG</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Edit Barang</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card height-equal">
                                <div class="card-body custom-input">
                                    <?php
                                    $queryp = "SELECT * FROM product WHERE ProductCD='" . $_GET["prodcd"] . "'";
                                    $resultp = mysqli_query($conn, $queryp);
                                    $product = mysqli_fetch_assoc($resultp);
                                    ?>
                                    <form class="row g-3" action="../Process/editProduct.php" method="POST">
                                        <h5>Header Barang</h5>
                                        <div class="col-2">
                                            <label class="form-label" for="urutanreport">Urutan<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="urutanreport" name="urutanreport"
                                                type="text" placeholder="1" value="<?php echo $product["Sequence"]; ?>"
                                                readonly>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label" for="kodeproduk">Kode Produk<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="kodeproduk" name="kodeproduk" type="text"
                                                placeholder="ABXXXX" value="<?php echo $product["ProductCD"]; ?>"
                                                readonly>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="namaproduk">Nama Produk<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="namaproduk" name="namaproduk" type="text"
                                                placeholder="barang contoh"
                                                value="<?php echo $product["ProductName"]; ?>" readonly>
                                        </div>
                                        <div class="col-6">
                                            <label class="col-sm-12 col-form-label" for="satuan">Satuan<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="satuan" list="satuanOptions" name="satuan"
                                                placeholder="Satuan" value="<?php echo $product["UnitCD"]; ?>" readonly>
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
                                        <div class="col-6">
                                            <label class="col-sm-12 col-form-label" for="kategori">Kategori<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="kategori" name="kategori"
                                                list="kategoriOptions" placeholder="Kategori"
                                                value="<?php echo $product["CategoryCD"]; ?>" readonly>
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
                                        <div class="col-6">
                                            <label class="col-sm-12 col-form-label" for="groupbarang">Group Barang<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="groupbarang" name="group"
                                                list="groupOptions" placeholder="Group"
                                                value="<?php echo $product["GroupCD"]; ?>" readonly>
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
                                        <div class="col-6">
                                            <label class="col-sm-12 col-form-label" for="gudang">Gudang<span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" id="gudang" name="gudang" list="gudangOptions"
                                                placeholder="Gudang" value="<?php echo $product["WarehCD"]; ?>"
                                                readonly>
                                            <datalist id="gudangOptions">
                                                <?php
                                                $query = "SELECT WarehCD,WarehName FROM warehouse WHERE Status='1'";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo '<option value="' . $row["WarehCD"] . '">' . $row["WarehName"] . '</option>';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                        <div class="col-6">
                                            <label class="col-sm-12 col-form-label" for="supplier">Supplier</label>
                                            <input class="form-control" id="supplier" name="supplier"
                                                list="supplierOptions" <?php if ($product["SupplierNum"] != 0) {
                                                    echo 'value="', $product["SupplierNum"], '"';
                                                } else {
                                                    echo 'placeholder="No Supplier --"';
                                                } ?> readonly>
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
                                        <hr>
                                        <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item"><a class="nav-link active txt-primary" id="home-tab"
                                                    data-bs-toggle="tab" href="#home" role="tab" aria-controls="home"
                                                    aria-selected="true">Detail</a></li>
                                            <li class="nav-item"><a class="nav-link txt-primary" id="profile-tabs"
                                                    data-bs-toggle="tab" href="#profile" role="tab"
                                                    aria-controls="profile" aria-selected="false">Komponen</a></li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                                aria-labelledby="home-tab">
                                                <div class="row g-3">
                                                    <h5>Dimensi Dus<span style="color:red;">*</span></h5>
                                                    <div class="col-4">
                                                        <label class="form-label" for="boxpanjang">Panjang</label>
                                                        <input class="form-control" id="boxpanjang" name="boxpanjang"
                                                            type="text" placeholder="ABXXXX"
                                                            value="<?php echo $product["BoxLength"]; ?>" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label" for="boxlebar">Lebar</label>
                                                        <input class="form-control" id="boxlebar" name="boxlebar"
                                                            type="text" placeholder="ABXXXX"
                                                            value="<?php echo $product["BoxWidth"]; ?>" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label" for="boxtinggi">Tinggi</label>
                                                        <input class="form-control" id="boxtinggi" name="boxtinggi"
                                                            type="text" placeholder="ABXXXX"
                                                            value="<?php echo $product["BoxHeight"]; ?>" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label" for="pcsperdos">Pcs/dos</label>
                                                        <input class="form-control" id="pcsperdos" name="pcsperdos"
                                                            type="text" placeholder="ABXXXX"
                                                            value="<?php echo $product["PcsPerBox"]; ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-9">

                                                    </div>
                                                    <div class="col-sm-5">
                                                        <!-- checked="" -->
                                                        <div class="card-wrapper border rounded-3 checkbox-checked">
                                                            <h6 class="sub-title">Rules<span style="color:red;">*</span>
                                                            </h6>
                                                            <label class="d-block" for="chk-jual"></label>
                                                            <input class="checkbox_animated" id="chk-jual"
                                                                name="rulesJual" value="1" type="checkbox" <?php if ($product["Sales"] == 1) {
                                                                    echo "checked";
                                                                } ?>
                                                                disabled>Jual
                                                            <label class="d-block" for="chk-beli"></label>
                                                            <input class="checkbox_animated" id="chk-beli"
                                                                name="rulesBeli" value="1" type="checkbox" <?php if ($product["Purchase"] == 1) {
                                                                    echo "checked";
                                                                } ?>
                                                                disabled>Beli
                                                            <label class="d-block" for="chk-produksi"></label>
                                                            <input class="checkbox_animated" id="chk-produksi"
                                                                name="rulesProduksi" value="1" type="checkbox" <?php if ($product["Production"] == 1) {
                                                                    echo "checked";
                                                                } ?>
                                                                disabled>Produksi
                                                            <label class="d-block" for="chk-transaksi"></label>
                                                            <input class="checkbox_animated" id="chk-transaksi"
                                                                name="rulesTransaksi" value="1" type="checkbox" <?php if ($product["Transaction"] == 1) {
                                                                    echo "checked";
                                                                } ?>
                                                                disabled>Transaksi
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="card-wrapper border rounded-3 checkbox-checked">
                                                            <h6 class="sub-title">Status?<span
                                                                    style="color:red;">*</span></h6>
                                                            <div class="radio-form">
                                                                <div class="form-check">
                                                                    <input class="form-check-input"
                                                                        id="flexRadioDefault3" type="radio"
                                                                        name="produkStatus" value="1" <?php if ($product["Status"] == 1) {
                                                                            echo "checked";
                                                                        } ?>
                                                                        disabled="disabled">
                                                                    <label class="form-check-label"
                                                                        for="flexRadioDefault3">Active</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input"
                                                                        id="flexRadioDefault4" type="radio"
                                                                        name="produkStatus" value="0" <?php if ($product["Status"] == 0) {
                                                                            echo "checked";
                                                                        } ?>
                                                                        disabled="disabled">
                                                                    <label class="form-check-label"
                                                                        for="flexRadioDefault4">Inactive</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade show" id="profile" role="tabpanel">
                                                <table id="dinamis" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Produk</th>
                                                            <th scope="col">Jumlah</th>
                                                            <th scope="col">Satuan</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="dbody">
                                                        <tr id="row1">
                                                            <td>
                                                                <input type="text" class="form-control prodlist"
                                                                    name="products[]" list="namelist">
                                                                <datalist id="namelist" style="width:3rem;">
                                                                    <option value="PC4">Paper Cup 4 oz</option>
                                                                    <option value="PC7">Paper Cup 7 oz</option>
                                                                </datalist>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                    name="quantities[]" placeholder="0">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="discs[]"
                                                                    placeholder="0">
                                                            </td>
                                                            <td>

                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-12">
                                            <a class="btn btn-warning" href="product.php">Back</a>
                                            <!-- <button class="btn btn-info" type="button" onclick="editProduct(this)" value="<?php echo $_GET["prodcd"] ?>">Edit</button> -->
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