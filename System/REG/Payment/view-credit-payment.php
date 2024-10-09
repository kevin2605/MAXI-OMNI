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
                <h3>PELUNASAN PIUTANG</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Transaksi</li>
                  <li class="breadcrumb-item">Pelunasan Piutang</li>
                  <li class="breadcrumb-item">Detail</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!-- Container-fluid starts-->
        <?php
        $id = $_GET["id"];
        $query = "SELECT cp.CreditPaymentID, cp.CreatedOn, cp.CustID, c.CustName, cp.PaymentMethod, cp.Description
                  FROM creditpaymentheader cp, customer c
                  WHERE cp.CustID=c.CustID
                        AND CreditPaymentID ='" . $id . "'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        ?>
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  Informasi Pelunasan Piutang
                </div>
                <div class="card-body">
                  <div class="mb-2 row">
                    <label class="col-sm-2">ID Pelunasan</label>
                    <div class="col-sm-4">
                      <input class="form-control" type="text" value="<?php echo $row["CreditPaymentID"]; ?>" readonly>
                    </div>
                  </div>
                  <div class="mb-2 row">
                    <label class="col-sm-2">Tgl. Pelunasan</label>
                    <div class="col-sm-4">
                      <input class="form-control" type="text" value="<?php echo $row["CreatedOn"]; ?>" readonly>
                    </div>
                  </div>
                  <div class="mb-2 row">
                    <label class="col-sm-2">Pelanggan</label>
                    <div class="col-sm-4">
                      <input class="form-control" type="text" value="<?php echo $row["CustName"]; ?>" readonly>
                    </div>
                  </div>
                  <div class="mb-2 row">
                    <label class="col-sm-2">Keterangan</label>
                    <div class="col-sm-4">
                      <input class="form-control" type="text" value="<?php echo $row["Description"]; ?>" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <?php

                if (isset($_GET["id"])) {
                  $id = urldecode($_GET["id"]);
                } else {
                  die("Error: Parameter 'id' tidak ditemukan.");
                }
                $queryHeader = "SELECT * FROM creditpaymentheader WHERE CreditPaymentID='" . mysqli_real_escape_string($conn, $id) . "'";
                $resultHeader = mysqli_query($conn, $queryHeader);
                if (!$resultHeader) {
                  die("Error: " . mysqli_error($conn));
                }
                $header = mysqli_fetch_assoc($resultHeader);

                if (!$header) {
                  die("Error: Pelunasan Piutang dengan ID tersebut tidak ditemukan.");
                }

                echo '<div class="card-header">Detail Pelunasan Piutang</div>';
                echo '<div class="card-body">
                <div class="table-responsive custom-scrollbar signal-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">InvoiceID</th>
                                <th scope="col">Jumlah Bayar</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>';

                $queryDetail = "SELECT *
                                FROM creditpaymentdetail 
                                WHERE CreditPaymentID='" . $id . "'";
                $resultDetail = mysqli_query($conn, $queryDetail);

                if (!$resultDetail) {
                  die("Error: " . mysqli_error($conn));
                }

                while ($rowDetail = mysqli_fetch_assoc($resultDetail)) {
                  echo '<tr>
                          <td>' . substr($rowDetail["CreatedOn"], 0, 10) . '</td>
                          <td>' . $rowDetail["InvoiceID"] . '</td>
                          <td>' . number_format($rowDetail["TotalPayment"], 0, '.', ',') . '</td>
                          <td><span class="badge badge-light-success">Lunas</span></td>
                      </tr>';
                }
                echo '  </tbody>
                    </table>
                  </div>
                </div>';

                mysqli_close($conn);
                ?>

              </div>
              <a class="btn btn-warning" href="../Payment/payment-of-credit.php">Back</a>
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