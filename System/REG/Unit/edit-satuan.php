<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  include "../DBConnection.php";
  ?>

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
                  <p><b> Selamat! </b>Satuan baru berhasil disimpan ke database.</p>
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
                <h3>EDIT SATUAN</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Edit Satuan</li>
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
                  $queryu = "SELECT * FROM unit WHERE UnitCD='" . $_GET["unitcd"] . "'";
                  $resultu = mysqli_query($conn, $queryu);
                  $unit = mysqli_fetch_assoc($resultu);
                  ?>
                  <form class="row g-3" action="../Process/editUnit.php" method="POST">
                    <div class="col-6">
                      <label class="form-label" for="kodesatuan">Kode Satuan<span style="color:red;">*</span></label>
                      <input class="form-control" id="kodesatuan" name="kodesatuan" type="text"
                        value="<?php echo $unit["UnitCD"]; ?>" readonly>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="satuan">Satuan<span style="color:red;">*</span></label>
                      <input class="form-control" id="satuan" name="satuan" type="text" placeholder="satuan"
                        value="<?php echo $unit["UnitName"]; ?>" required>
                    </div>
                    <div class="col-12">
                      <label class="col-sm-12 col-form-label" for="keterangan">Keterangan</label>
                      <input class="form-control" id="keterangan" name="keterangan" type="text"
                        value="<?php echo $unit["Description"]; ?>">
                    </div>
                    <div class="col-3">
                      <div class="card-wrapper border rounded-3 checkbox-checked">
                        <h6 class="sub-title">Status?<span style="color:red;">*</span></h6>
                        <div class="radio-form">
                          <div class="form-check">
                            <input class="form-check-input" id="flexRadioDefault3" type="radio" name="satuanStatus"
                              value="1" <?php if ($unit["Status"] == 1) {
                                echo "checked";
                              } ?> required="">
                            <label class="form-check-label" for="flexRadioDefault3">Active</label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" id="flexRadioDefault4" type="radio" name="satuanStatus"
                              value="0" <?php if ($unit["Status"] == 0) {
                                echo "checked";
                              } ?> required="">
                            <label class="form-check-label" for="flexRadioDefault4">Inactive</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-check form-switch">
                        <input class="form-check-input" id="flexSwitchCheckDefault" type="checkbox" role="switch"
                          required>
                        <label class="form-check-label" for="flexSwitchCheckDefault">Apakah informasi diatas sudah
                          benar?</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <a class="btn btn-warning" href="satuan.php">Back</a>
                      <button class="btn btn-primary" type="submit">Submit</button>
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
  <script src="../../assets/js/sweet-alert/sweetalert.min.js"></script>
  <script src="../../assets/js/sweet-alert/app.js"></script>
  <!-- Plugins JS Ends-->
  <!-- Theme js-->
  <script src="../../assets/js/script.js"></script>
  <!-- Plugin used-->
</body>

</html>