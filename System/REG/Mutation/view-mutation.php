<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  include "../DBConnection.php";
  ?>

  <script>
    function editInv(str) {
      //document.location = "editSalesOrder.php?id=" + str.value;
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
                <h3>MUTASI</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Mutasi</li>
                  <li class="breadcrumb-item">Detail</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!-- Container-fluid starts-->
        <?php
        $mutationid = $_GET["id"];
        $query = "SELECT * FROM mutationheader WHERE MutationID='" . $mutationid . "'";
        $result = mysqli_query($conn, $query);
        $mut = mysqli_fetch_assoc($result);
        ?>
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  Informasi Mutasi
                </div>
                <div class="card-body">
                  <div class="mb-2 row">
                    <label class="col-sm-1">No. Mutasi</label>
                    <div class="col-sm-3">
                      <input class="form-control" type="text" value="<?php echo $mut["MutationID"]; ?>" readonly>
                    </div>
                  </div>
                  <div class="mb-2 row">
                    <label class="col-sm-1">Tgl. Mutasi</label>
                    <div class="col-sm-3">
                      <input class="form-control" type="text" value="<?php echo $mut["CreatedOn"]; ?>" readonly>
                    </div>
                  </div>
                  <div class="mb-2 row">
                    <label class="col-sm-1">Dibuat</label>
                    <div class="col-sm-3">
                      <input class="form-control" type="text" value="<?php echo $mut["MutationID"]; ?>" readonly>
                    </div>
                  </div>
                  <div class="mb-2 row">
                    <label class="col-sm-1">Keterangan</label>
                    <div class="col-sm-3">
                      <input class="form-control" type="text" value="<?php echo $mut["Description"]; ?>" readonly>
                    </div>
                  </div>
                  <div class="mb-2 row">
                    <label class="col-sm-1">Tipe Mutasi</label>
                    <div class="col-sm-3">
                      <?php
                      if ($mut["CategoryCD"] == "BB") {
                        echo "<input class='form-control' type='text' value='Bahan Baku' readonly>";
                      } else if ($mut["CategoryCD"] == "SA") {
                        echo "<input class='form-control' type='text' value='Saldo Awal' readonly>";
                      } else if ($mut["CategoryCD"] == "BTM") {
                        echo "<input class='form-control' type='text' value='Bottom' readonly>";
                      } else if ($mut["CategoryCD"] == "BPP") {
                        echo "<input class='form-control' type='text' value='Penunjang Produksi' readonly>";
                      } else if ($mut["CategoryCD"] == "SPR") {
                        echo "<input class='form-control' type='text' value='Spare Part' readonly>";
                      } else if ($mut["CategoryCD"] == "PR") {
                        echo "<input class='form-control' type='text' value='Produk' readonly>";
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <?php
                include "../DBConnection.php";

                if (isset($_GET["id"])) {
                  $mutationID = urldecode($_GET["id"]);
                } else {
                  die("Error: Parameter 'id' tidak ditemukan.");
                }
                $queryHeader = "SELECT * FROM mutationheader WHERE MutationID='" . mysqli_real_escape_string($conn, $mutationID) . "'";
                $resultHeader = mysqli_query($conn, $queryHeader);
                if (!$resultHeader) {
                  die("Error: " . mysqli_error($conn));
                }
                $header = mysqli_fetch_assoc($resultHeader);

                if (!$header) {
                  die("Error: Mutasi dengan ID tersebut tidak ditemukan.");
                }

                echo '<div class="card-header">Detail Mutasi</div>';
                echo '<div class="card-body">
                <div class="table-responsive custom-scrollbar signal-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Barang</th>
                                <th scope="col">Masuk</th>
                                <th scope="col">Keluar</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>';
                $categoryCD = $header["CategoryCD"];
                switch ($categoryCD) {
                  case 'SA':
                    $queryDetail = "SELECT CreatedOn, ProductCD AS ItemCD, FlowIn, FlowOut, UnitCD, Description 
                        FROM mutationdetail 
                        WHERE MutationID='" . mysqli_real_escape_string($conn, $mutationID) . "'";
                    break;
                  case 'BTM':
                    $queryDetail = "SELECT CreatedOn, MaterialCD AS ItemCD, FlowIn, FlowOut, UnitCD_1 AS UnitCD, Description 
                        FROM mutationdetailbtm 
                        WHERE MutationID='" . mysqli_real_escape_string($conn, $mutationID) . "'";
                    break;
                  case 'SPR':
                    $queryDetail = "SELECT CreatedOn, PartCD AS ItemCD, FlowIn, FlowOut, UnitCD, Description 
                        FROM mutationdetailsprt 
                        WHERE MutationID='" . mysqli_real_escape_string($conn, $mutationID) . "'";
                    break;
                  case 'BPP':
                    $queryDetail = "SELECT CreatedOn, GoodsCD AS ItemCD, FlowIn, FlowOut, UnitCD_1 AS UnitCD, Description 
                        FROM mutationdetailbpp 
                        WHERE MutationID='" . mysqli_real_escape_string($conn, $mutationID) . "'";
                    break;
                  default:
                    die("Error: Kategori mutasi tidak dikenali.");
                }

                $resultDetail = mysqli_query($conn, $queryDetail);

                if (!$resultDetail) {
                  die("Error: " . mysqli_error($conn));
                }

                while ($rowDetail = mysqli_fetch_assoc($resultDetail)) {
                  echo '<tr>
                          <td>' . substr($rowDetail["CreatedOn"], 0, 10) . '</td>
                          <td>' . $rowDetail["ItemCD"] . '</td>
                          <td>' . number_format($rowDetail["FlowIn"], 0, '.', ',') . '</td>
                          <td>' . number_format($rowDetail["FlowOut"], 0, '.', ',') . '</td>
                          <td>' . $rowDetail["UnitCD"] . '</td>
                          <td>' . $rowDetail["Description"] . '</td>
                      </tr>';
                }
                echo '  </tbody>
                    </table>
                  </div>
                </div>';

                mysqli_close($conn);
                ?>

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
  <script src="../../assets/js/form-wizard/form-wizard.js"></script>
  <script src="../../assets/js/form-wizard/image-upload.js"></script>
  <!-- Plugins JS Ends-->
  <!-- Theme js-->
  <script src="../../assets/js/script.js"></script>
  <!-- Plugin used-->
</body>

</html>