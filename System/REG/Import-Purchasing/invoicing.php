<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT tInvoicePembelian FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['tInvoicePembelian'], 'C') !== false || // Create
    strpos($row['tInvoicePembelian'], 'R') !== false || // Read
    strpos($row['tInvoicePembelian'], 'U') !== false || // Update
    strpos($row['tInvoicePembelian'], 'D') !== false;  // Delete
  
  $accessDenied = !$hasCRUDAccess;
  ?>

  <!-- AJAX SCRIPT and DYNAMIC TABLE -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function viewInvoicing(x) {
      document.location = "viewInvoicing.php?rcvinvid=" + x.value;
    }
  </script>
  <style>
    .disabled {
      pointer-events: none;
    }

    .tb-label {
      padding: 0;
      pointer-events: none;
      border-style: none;
    }

    .hidden {
      display: none;
    }
  </style>
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
                if ($_GET["status"] == "success") {
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Pembuatan Invoice dari penerimaan berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "error") {
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan invoicing ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }
              }
              ?>
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>INVOICE PEMBELIAN BARANG</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Pembelian (Import)</li>
                    <li class="breadcrumb-item">Invoice Pembelian</li>
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
                      if ($_GET["status"] == "success") {
                        echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Pembuatan Invoice dari penerimaan berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                      } else if ($_GET["status"] == "error") {
                        echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan invoicing ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                      }
                    }
                    ?>
                    <div class="row">
                      <div class="col-sm-6 ps-0">
                        <h3>INVOICE PEMBELIAN BARANG</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Pembelian (Import)</li>
                          <li class="breadcrumb-item">Invoice Pembelian</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                          aria-expanded="false">Menu</button>
                        <ul class="dropdown-menu dropdown-block" id="myTab" role="tablist">
                          <li class="nav-item"><a class="dropdown-item active txt-primary f-w-500 f-18" id="home-tab"
                              data-bs-toggle="tab" href="#unpaid" role="tab" aria-controls="home"
                              aria-selected="true">Belum
                              Lunas</a></li>
                          <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18" id="profile-tabs"
                              data-bs-toggle="tab" href="#paid" role="tab" aria-controls="profile"
                              aria-selected="false">Lunas</a></li>
                        </ul>
                        <hr>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="unpaid" role="tabpanel">
                            <h3>Invoicing Pembelian Import</h3><small>Status : Belum Lunas</small>
                            <br><br>
                            <div class="table-responsive custom-scrollbar user-datatable">
                              <table class="display" id="basic-12">
                                <thead>
                                  <tr>
                                    <th>No. Invoicing</th>
                                    <th>Tanggal</th>
                                    <th>Purchase Order</th>
                                    <th>DPP</th>
                                    <th>BM</th>
                                    <th>PPN</th>
                                    <th>PPH</th>
                                    <th>Total</th>
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
                                  $query_access = "SELECT tInvoicePembelian FROM useraccesslevel WHERE UserID = '$creator'";
                                  $result_access = mysqli_query($conn, $query_access);
                                  $can_update = false;
                                  if ($result_access) {
                                    $row_access = mysqli_fetch_assoc($result_access);
                                    $access_level = $row_access['tInvoicePembelian'];
                                    if (strpos($access_level, 'R') !== false) {
                                      $can_update = true;
                                    }
                                  } else {
                                    die("Error: Gagal mengambil data akses pengguna.");
                                  }
                                  $queryR = "SELECT ri.RCV_InvoiceID, ri.CreatedOn, r.PurchaseOrderID, ri.DPP, ri.BM, ri.PPN, ri.PPH, ri.TotalAmount
                                      FROM importreceptioninvoiceheader ri, importreceptionheader r
                                      WHERE ri.ReceptionID=r.ReceptionID
                                            AND ri.Status=0
                                            ORDER BY ri.RCV_InvoiceID DESC";
                                  $resultR = mysqli_query($conn, $queryR);
                                  while ($rowR = mysqli_fetch_array($resultR)) {
                                    echo '<tr>
                                                        <td>' . $rowR["RCV_InvoiceID"] . '</td>
                                                        <td>' . $rowR["CreatedOn"] . '</td>
                                                        <td><a href="viewIPOMaterial.php?id=' . $rowR["PurchaseOrderID"] . '">' . $rowR["PurchaseOrderID"] . '</a></td>
                                                        <td>' . number_format($rowR["DPP"], 0, '.', ',') . '</td>
                                                        <td>' . number_format($rowR["BM"], 0, '.', ',') . '</td>
                                                        <td>' . number_format($rowR["PPN"], 0, '.', ',') . '</td>
                                                        <td>' . number_format($rowR["PPH"], 0, '.', ',') . '</td>
                                                        <td>' . number_format($rowR["TotalAmount"], 0, '.', ',') . '</td>
                                                        <td> 
                                                          <ul> ';
                                    if ($can_update) {
                                      echo '<button onclick="viewInvoicing(this)" value="' . $rowR["RCV_InvoiceID"] . '" type="button" class="light-card border-primary border b-r-10"><i class="fa fa-eye txt-primary"></i></button>';
                                    }
                                    echo '            
                                            </ul>
                                          </td>
                                      </tr>';
                                  }
                                  ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="paid" role="tabpanel">
                            <h3>Invoicing Pembelian Import</h3><small>Status : Lunas</small>
                            <br><br>
                            <div class="table-responsive custom-scrollbar user-datatable">
                              <table class="display" id="basic-1">
                                <thead>
                                  <tr>
                                    <th>No. Invoicing</th>
                                    <th>Tanggal</th>
                                    <th>Purchase Order</th>
                                    <th>DPP</th>
                                    <th>PPN</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $queryR = "SELECT ri.RCV_InvoiceID, ri.CreatedOn, r.PurchaseOrderID, ri.DPP, ri.BM, ri.PPN, ri.PPH, ri.TotalAmount
                                  FROM importreceptioninvoiceheader ri, importreceptionheader r
                                  WHERE ri.ReceptionID=r.ReceptionID
                                        AND ri.Status=1
                                        ORDER BY ri.RCV_InvoiceID DESC";
                                  $resultR = mysqli_query($conn, $queryR);
                                  while ($rowR = mysqli_fetch_array($resultR)) {
                                    echo '<tr>
                                                        <td>' . $rowR["RCV_InvoiceID"] . '</td>
                                                        <td>' . $rowR["CreatedOn"] . '</td>
                                                        <td><a href="viewIPOMaterial.php?id=' . $rowR["PurchaseOrderID"] . '">' . $rowR["PurchaseOrderID"] . '</a></td>
                                                        <td>' . number_format($rowR["DPP"], 0, '.', ',') . '</td>
                                                        <td>' . number_format($rowR["BM"], 0, '.', ',') . '</td>
                                                        <td>' . number_format($rowR["PPN"], 0, '.', ',') . '</td>
                                                        <td>' . number_format($rowR["PPH"], 0, '.', ',') . '</td>
                                                        <td>' . number_format($rowR["TotalAmount"], 0, '.', ',') . '</td>
                                                        <td> 
                                                          <ul> 
                                                            <button onclick="viewInvoicing(this)" value="' . $rowR["RCV_InvoiceID"] . '" type="button" class="light-card border-primary border b-r-10"><i class="fa fa-eye txt-primary"></i></button>
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
        <script src="../../assets/js/dropzone/dropzone.js"></script>
        <script src="../../assets/js/dropzone/dropzone-script.js"></script>
        <script src="../../assets/js/form-validation-custom.js"></script>
        <script src="../../assets/js/height-equal.js"></script>
        <script src="../../assets/js/notify/bootstrap-notify.min.js"></script>
        <script src="../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
        <script src="../../assets/js/datatable/datatables/datatable.custom.js"></script>
        <script src="../../assets/js/tooltip-init.js"></script>
        <script src="../../assets/js/modalpage/validation-modal.js"></script>
        <script src="../../assets/js/dropzone/dropzone.js"></script>
        <script src="../../assets/js/dropzone/dropzone-script.js"></script>
        <script src="../../assets/js/filepond/filepond-plugin-image-preview.js"></script>
        <script src="../../assets/js/filepond/filepond-plugin-file-rename.js"></script>
        <script src="../../assets/js/filepond/filepond-plugin-image-transform.js"></script>
        <script src="../../assets/js/filepond/filepond.js"></script>
        <script src="../../assets/js/filepond/custom-filepond.js"></script>
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="../../assets/js/script.js"></script>
        <!-- Plugin used-->
</body>

</html>