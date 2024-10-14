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
    function editMaterial(str) {
      document.location = "edit-material.php?matcd=" + str.value;
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
                  <p><b> Selamat! </b>Bahan Baku baru berhasil disimpan ke database.</p>
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
                <h3>BAHAN BAKU</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Bahan Baku</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card height-equal">
                <div class="card-body custom-input">
                  <?php
                  $querym = "SELECT * FROM material WHERE MaterialCD='" . $_GET["matcd"] . "'";
                  $resultm = mysqli_query($conn, $querym);
                  $material = mysqli_fetch_assoc($resultm);
                  ?>
                  <form class="row g-3" action="../Process/editMaterial.php" method="POST">
                    <div class="col-2">
                      <label class="form-label" for="urutanreport">Urutan<span style="color:red;">*</span></label>
                      <input class="form-control" id="urutanreport" name="urutanreport" type="text" placeholder="1"
                        value="<?php echo $material["Sequence"]; ?>" readonly>
                    </div>
                    <div class="col-4">
                      <label class="form-label" for="kodebahan">Kode Bahan<span style="color:red;">*</span></label>
                      <input class="form-control" id="kodebahan" name="kodebahan" type="text" placeholder="ABXXXX"
                        value="<?php echo $material["MaterialCD"]; ?>" readonly>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="namabahan">Nama Bahan<span style="color:red;">*</span></label>
                      <input class="form-control" id="namabahan" name="namabahan" type="text"
                        placeholder="barang contoh" value="<?php echo $material["MaterialName"]; ?>" readonly>
                    </div>
                    <div class="col-6">
                      <label class="col-sm-12 col-form-label" for="satuanpertama">Satuan Pertama<span
                          style="color:red;">*</span></label>
                      <input class="form-control" id="satuanpertama" list="satuanOptions" name="satuanpertama"
                        placeholder="Satuan" value="<?php echo $material["UnitCD_1"]; ?>" readonly>
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
                      <label class="col-sm-12 col-form-label" for="satuankedua">Satuan Kedua<span
                          style="color:red;">*</span></label>
                      <input class="form-control" id="satuankedua" list="satuanOptions" name="satuankedua"
                        placeholder="Satuan" value="<?php echo $material["UnitCD_2"]; ?>" readonly>
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
                      <label class="col-sm-12 col-form-label" for="kategori">Kategori<span
                          style="color:red;">*</span></label>
                      <input class="form-control" id="kategori" name="kategori" list="kategoriOptions"
                        placeholder="Kategori" value="<?php echo $material["CategoryCD"]; ?>" readonly>
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
                      <label class="col-sm-12 col-form-label" for="groupbarang">Group Bahan<span
                          style="color:red;">*</span></label>
                      <input class="form-control" id="groupbarang" name="group" list="groupOptions" placeholder="Group"
                        value="<?php echo $material["GroupCD"]; ?>" readonly>
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
                      <label class="col-sm-12 col-form-label" for="gudang">Gudang<span
                          style="color:red;">*</span></label>
                      <input class="form-control" id="gudang" name="gudang" list="gudangOptions" placeholder="Gudang"
                        value="<?php echo $material["WarehCD"]; ?>" readonly>
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
                    <div class="col-3">
                      <label class="col-sm-12 col-form-label" for="supplier">Supplier</label>
                      <?php
                      $query = "SELECT SupplierNum, SupplierName FROM supplier WHERE SupplierNum='97546'";
                      $result = mysqli_query($conn, $query);
                      while ($row = mysqli_fetch_array($result)) {
                        echo '<input class="form-control" id="supplier" name="supplier" list="supplierOptions" placeholder="Supplier" value="' . $row["SupplierNum"] . ' - ' . $row["SupplierName"] . '">';
                      }
                      ?>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="produk">Produk</label>
                      <input class="form-control" id="produk" name="produk" list="produkOptions"
                        value="<?php echo $material["ProductCD"]; ?>" placeholder="Pilih Produk Jadi" readonly>
                      <datalist id="produkOptions">
                        <?php
                        $query = "SELECT ProductCD, ProductName FROM product WHERE Status='1'";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_array($result)) {
                          echo '<option value="' . $row["ProductCD"] . ' - ' . $row["ProductName"] . '"></option>';
                        }
                        ?>
                      </datalist>
                    </div>
                    <div class="col-3">
                      <label class="form-label" for="buyprice">Harga Beli<span style="color:red;">*</span>
                        <i>exclude</i></label>
                      <input class="form-control" id="buyprice" name="buyprice" type="text" placeholder="0"
                        value="Rp <?php echo number_format($material["BuyPrice"], 2, '.', ','); ?>" readonly>
                    </div>
                    <div class="col-3">
                      <label class="form-label" for="avgprice">Harga Avg</label>
                      <input class="form-control" id="avgprice" name="avgprice" type="text" placeholder="0"
                        value="Rp <?php echo number_format($material["AvgPrice"], 2, '.', ','); ?>" readonly>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="keterangan2">Keterangan 2</label>
                      <input class="form-control" id="keterangan1" name="keterangan1" type="text" placeholder="..."
                        value="<?php echo $material["Desc_1"]; ?>" readonly>
                    </div>
                    <div class="col-6"></div>
                    <div class="col-6">
                      <label class="form-label" for="keterangan2">Keterangan 2</label>
                      <input class="form-control" id="keterangan2" name="keterangan2" type="text" placeholder="..."
                        value="<?php echo $material["Desc_2"]; ?>" readonly>
                    </div>
                    <div class="col-6"></div>
                    <div class="col-6">
                      <label class="form-label" for="keterangan3">Keterangan 3</label>
                      <input class="form-control" id="keterangan3" name="keterangan3" type="text" placeholder="..."
                        value="<?php echo $material["Desc_3"]; ?>" readonly>
                    </div>
                    <div class="col-6"></div>
                    <hr>
                    <div class="col-sm-4">
                      <!-- checked="" -->
                      <div class="card-wrapper border rounded-3 checkbox-checked">
                        <h6 class="sub-title">Rules<span style="color:red;">*</span></h6>
                        <label class="d-block" for="chk-jual"></label>
                        <input class="checkbox_animated" id="chk-jual" name="rulesJual" value="1" type="checkbox" <?php if ($material["Sales"] == 1) {
                          echo "checked";
                        } ?> disabled>Jual
                        <label class="d-block" for="chk-beli"></label>
                        <input class="checkbox_animated" id="chk-beli" name="rulesBeli" value="1" type="checkbox" <?php if ($material["Purchase"] == 1) {
                          echo "checked";
                        } ?> disabled>Beli
                        <label class="d-block" for="chk-produksi"></label>
                        <input class="checkbox_animated" id="chk-produksi" name="rulesProduksi" value="1"
                          type="checkbox" <?php if ($material["Production"] == 1) {
                            echo "checked";
                          } ?> disabled>Produksi
                        <label class="d-block" for="chk-transaksi"></label>
                        <input class="checkbox_animated" id="chk-transaksi" name="rulesTransaksi" value="1"
                          type="checkbox" <?php if ($material["Transaction"] == 1) {
                            echo "checked";
                          } ?> disabled>Transaksi
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="card-wrapper border rounded-3 checkbox-checked">
                        <h6 class="sub-title">Status?<span style="color:red;">*</span></h6>
                        <div class="radio-form">
                          <div class="form-check">
                            <input class="form-check-input" id="flexRadioDefault3" type="radio" name="produkStatus"
                              value="1" <?php if ($material["Status"] == 1) {
                                echo "checked";
                              } ?> disabled>
                            <label class="form-check-label" for="flexRadioDefault3">Active</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" id="flexRadioDefault4" type="radio" name="produkStatus"
                              value="0" <?php if ($material["Status"] == 0) {
                                echo "checked";
                              } ?> disabled>
                            <label class="form-check-label" for="flexRadioDefault4">Inactive</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="col-12">
                      <a class="btn btn-warning" href="material.php">Back</a>
                      <button class="btn btn-info" type="button" onclick="editMaterial(this)"
                        value="<?php echo $_GET["matcd"] ?>">Edit</button>
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
  <script src="../../../assets/js/sidebar-pin.js"></script>
  <script src="../../../assets/js/slick/slick.min.js"></script>
  <script src="../../../assets/js/slick/slick.js"></script>
  <script src="../../../assets/js/sidebar-menu.js"></script>
  <script src="../../../assets/js/header-slick.js"></script>
  <script src="../../../assets/js/form-validation-custom.js"></script>
  <script src="../../../assets/js/notify/bootstrap-notify.min.js"></script>
  <script src="../../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../assets/js/datatable/datatables/datatable.custom.js"></script>
  <script src="../../../assets/js/tooltip-init.js"></script>
  <script src="../../../assets/js/modalpage/validation-modal.js"></script>
  <script src="../../../assets/js/height-equal.js"></script>
  <!-- Plugins JS Ends-->

  <!-- Theme js-->
  <script src="../../../assets/js/script.js"></script>
  <!-- Plugin used-->
</body>

</html>