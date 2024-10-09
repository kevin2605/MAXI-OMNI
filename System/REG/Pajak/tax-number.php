<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT taxn FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['taxn'], 'C') !== false || // Create
    strpos($row['taxn'], 'R') !== false || // Read
    strpos($row['taxn'], 'U') !== false || // Update
    strpos($row['taxn'], 'D') !== false;  // Delete
  
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
          text: 'Anda tidak memiliki akses untuk mengubah NPWP .',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
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
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>NOMOR SERI PAJAK</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Pajak</li>
                    <li class="breadcrumb-item">Nomor Seri Pajak</li>
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
                    <div class="row">
                      <div class="col-sm-6 ps-0">
                        <h3>NOMOR SERI PAJAK</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Pajak</li>
                          <li class="breadcrumb-item">Nomor Seri Pajak</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="d-flex">
                            <?php
                            $canUpdate = false;
                            if (!empty($userID)) {
                              $query_access = "SELECT taxn FROM useraccesslevel WHERE UserID = '$userID'";
                              $result_access = mysqli_query($conn, $query_access);
                              if ($result_access) {
                                $row_access = mysqli_fetch_assoc($result_access);
                                $access_level = $row_access['taxn'];
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
                              <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title" id="myExtraLargeModal">Form Nomor Seri Pajak Baru</h4>
                                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                      aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body dark-modal">
                                    <div class="card-body custom-input">
                                      <form action="../Process/createTaxSerialNumber.php" method="POST">
                                        <div class="row g-3">
                                          <div class="col-3">
                                            <label class="form-label" for="id">ID<span
                                                style="color:red;">*</span></label>
                                            <input class="form-control" id="id" name="id" type="text"
                                              placeholder="auto-generate" readonly>
                                          </div>
                                          <div class="col-9">
                                            <label class="form-label" for="keterangan">Keterangan<span
                                                style="color:red;">*</span></label>
                                            <input class="form-control" id="keterangan" name="keterangan" type="text"
                                              required>
                                          </div>
                                          <div class="col-3">
                                            <label class="form-label" for="prefix">Nomor Prefix<span
                                                style="color:red;">*</span></label>
                                            <input class="form-control" id="prefix" name="prefix" type="text"
                                              placeholder="xxx.xxx-xx." required>
                                          </div>
                                          <hr>
                                          <p>Masa Berlaku</p>
                                          <div class="mb-3 row">
                                            <label class="col-sm-2">Tgl. Awal</label>
                                            <div class="col-sm-6">
                                              <input class="form-control" id="start" name="startdate" type="date"
                                                required>
                                            </div>
                                          </div>
                                          <div class="mb-3 row">
                                            <label class="col-sm-2">Tgl. Akhir</label>
                                            <div class="col-sm-6">
                                              <input class="form-control" id="end" name="enddate" type="date" required>
                                            </div>
                                          </div>
                                          <hr>
                                          <p>Nomor Pajak</p>
                                          <div class="mb-3 row">
                                            <label class="col-sm-2">Nomor Awal</label>
                                            <div class="col-sm-6">
                                              <input class="form-control digits" id="startnum" name="startnum"
                                                type="number" required>
                                            </div>
                                          </div>
                                          <div class="mb-3 row">
                                            <label class="col-sm-2">Nomor Akhir</label>
                                            <div class="col-sm-6">
                                              <input class="form-control digits" id="endnum" name="endnum" type="numer"
                                                required>
                                            </div>
                                          </div>
                                          <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                          </div>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--
                        <div class="col-md-2">
                          <select id="status-filter" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                          </select>
                        </div>
                        -->
                        </div>
                        <hr>
                        <h3>Daftar Nomor Pajak</h3>
                        <div class="table-responsive custom-scrollbar user-datatable">
                          <table class="display" id="basic-12">
                            <thead>
                              <tr>
                                <th rowspan="2" class="text-center">ID</th>
                                <th colspan="2" class="text-center">Masa Berlaku</th>
                                <th rowspan="2">Nomor Prefix</th>
                                <th rowspan="2">Keterangan</th>
                                <th colspan="2" class="text-center">Nomor Seri</th>
                                <th rowspan="2">Angka Terakhir</th>
                                <th rowspan="2">Terpakai</th>
                                <th rowspan="2">Total</th>
                              </tr>
                              <tr>
                                <th>Tgl. Awal</th>
                                <th>Tgl. Akhir</th>
                                <th>No. Awal</th>
                                <th>No. Akhir</th>
                              </tr>
                            </thead>
                            <tbody id="unit-table-body">
                              <?php
                              if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
                                $creator = $_COOKIE["UserID"];
                              } else {
                                die("Error: Cookie 'UserID' tidak ada atau kosong.");
                              }
                              $query_access = "SELECT TipePembayaran FROM useraccesslevel WHERE UserID = '$creator'";
                              $result_access = mysqli_query($conn, $query_access);
                              $can_updatee = false;
                              if ($result_access) {
                                $row_access = mysqli_fetch_assoc($result_access);
                                $access_level = $row_access['TipePembayaran'];
                                if (strpos($access_level, 'U') !== false) {
                                  $can_updatee = true;
                                }
                              } else {
                                die("Error: Gagal mengambil data akses pengguna.");
                              }
                              $query = "SELECT * FROM taxserialnumber";
                              $result = mysqli_query($conn, $query);
                              while ($row = mysqli_fetch_array($result)) {
                                echo '<tr>
                                        <td class="text-center">' . $row["SerialID"] . '</td>
                                        <td>' . $row["StartDate"] . '</td>
                                        <td>' . $row["EndDate"] . '</td>
                                        <td>' . $row["Prefix"] . '</td>
                                        <td>' . $row["Description"] . '</td>
                                        <td>' . $row["StartNumber"] . '</td>
                                        <td>' . $row["EndNumber"] . '</td>
                                        <td>' . $row["LastNumberFlag"] . '</td>
                                        <td>' . $row["UsedNumber"] . '</td>
                                        <td>' . $row["TotalNumber"] . '</td>
                                      </tr>';
                              }
                              ?>
                            </tbody>
                          </table>
                          <script>
                            document.getElementById('status-filter').addEventListener('change', function () {
                              var status = this.value;
                              var xhr = new XMLHttpRequest();
                              xhr.open('GET', '../Process/filter-active-payment.php?status=' + status, true);
                              xhr.onload = function () {
                                if (this.status == 200) {
                                  console.log(this.responseText); // Memastikan data yang dikembalikan benar
                                  document.getElementById('unit-table-body').innerHTML = this.responseText;
                                }
                              };
                              xhr.send();
                            });
                          </script>
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