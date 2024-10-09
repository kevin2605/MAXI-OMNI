<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT KodeAkun FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['KodeAkun'], 'C') !== false || // Create
    strpos($row['KodeAkun'], 'R') !== false || // Read
    strpos($row['KodeAkun'], 'U') !== false || // Update
    strpos($row['KodeAkun'], 'D') !== false;  // Delete
  
  $accessDenied = !$hasCRUDAccess;
  ?>

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
              <?php
              if (isset($_GET["status"])) {
                if ($_GET["status"] == "success") {
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Kode Akun baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "error") {
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "success-edit") {
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Kode Akun berhasil di edit dan disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "error-edit") {
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Error! </b>Terjadi kesalahan saat edit kode akun ke database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                }
              }
              ?>
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>KODE AKUN</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Kode Akun</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid <?php echo $accessDenied ? 'hidden' : ''; ?>">
          <?php endif; ?>
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
                          <p><b> Selamat! </b>Kode Akun baru berhasil disimpan ke database.</p>
                          <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                      } else if ($_GET["status"] == "error") {
                        echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                          <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                          <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                      } else if ($_GET["status"] == "success-edit") {
                        echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                        <p><b> Selamat! </b>Kode Akun berhasil di edit dan disimpan ke database.</p>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                      } else if ($_GET["status"] == "error-edit") {
                        echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                        <p><b> Error! </b>Terjadi kesalahan saat edit kode akun ke database.</p>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                      }
                    }
                    ?>
                    <div class="row">
                      <div class="col-sm-6 ps-0">
                        <h3>KODE AKUN</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Kode Akun</li>
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
                          $query_access = "SELECT KodeAkun FROM useraccesslevel WHERE UserID = '$userID'";
                          $result_access = mysqli_query($conn, $query_access);
                          if ($result_access) {
                            $row_access = mysqli_fetch_assoc($result_access);
                            $access_level = $row_access['KodeAkun'];
                            if (strpos($access_level, 'R') !== false) {
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
                                <h4 class="modal-title" id="myExtraLargeModal">Form Kode Akun Baru</h4>
                                <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body dark-modal">
                                <div class="card-body custom-input">

                                  <form class="row g-3" action="../Process/createChartOfAcc.php" method="POST">
                                    <div class="col-12">
                                      <label class="form-label" for="kode">Kode Akun</label>
                                      <input class="form-control" id="kode" name="kode" type="text" placeholder="x-xxxx"
                                        required>
                                    </div>
                                    <div class="col-12">
                                      <label class="form-label" for="nama">Nama Akun</label>
                                      <input class="form-control" id="nama" name="nama" type="text" placeholder="-"
                                        required>
                                    </div>
                                    <div class="col-4">
                                      <label class="col-sm-12 col-form-label" for="tipe">Tipe</label>
                                      <input class="form-control" id="tipe" name="tipe" list="tipeOptions"
                                        placeholder="Pilih Tipe..." required>
                                      <datalist id="tipeOptions">
                                        <option value="Asset"></option>
                                        <option value="Liability"></option>
                                        <option value="Equity"></option>
                                        <option value="Income"></option>
                                        <option value="Cost of Sales"></option>
                                        <option value="Expense"></option>
                                        <option value="Other Income"></option>
                                        <option value="Other Expense"></option>
                                      </datalist>
                                    </div>
                                    <div class="col-4">
                                      <label class="col-sm-12 col-form-label" for="dbcr">Debit/Credit</label>
                                      <input class="form-control" id="dbcr" name="dbcr" list="dbcrOptions"
                                        placeholder="Debit/Credit" required>
                                      <datalist id="dbcrOptions">
                                        <option value="Debit"></option>
                                        <option value="Credit"></option>
                                      </datalist>
                                    </div>
                                    <div class="col-4">
                                      <label class="col-sm-12 col-form-label" for="hddt">Header/Detail</label>
                                      <input class="form-control" id="hddt" name="hddt" list="hddtOptions"
                                        placeholder="Header/Detail" required>
                                      <datalist id="hddtOptions">
                                        <option value="Header"></option>
                                        <option value="Detail"></option>
                                      </datalist>
                                    </div>

                                    <div class="col-12">
                                      <div class="form-check form-switch">
                                        <input class="form-check-input" id="flexSwitchCheckDefault" type="checkbox"
                                          role="switch" required>
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Are you sure above
                                          information are true</label>
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <button class="btn btn-primary" type="submit">Submit</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                          aria-expanded="false">Menu</button>
                        <ul class="dropdown-menu dropdown-block" id="myTab" role="tablist">
                          <li class="nav-item"><a class="dropdown-item active txt-primary f-w-500 f-18" id="home-tab"
                              data-bs-toggle="tab" href="#daftarBarang" role="tab" aria-controls="home"
                              aria-selected="true">Daftar Akun</a></li>
                        </ul>
                        <hr>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="daftarBarang" role="tabpanel">
                            <h3>Daftar Kode Akun</h3>
                            <div class="table-responsive custom-scrollbar user-datatable">
                              <table class="display" id="basic-12">
                                <thead>
                                  <tr>
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Tipe</th>
                                    <th>Debit/Credit</th>
                                    <th>Header/Detail</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $query = "SELECT * FROM chartofaccount ORDER BY AccountCD";
                                  $result = mysqli_query($conn, $query);
                                  while ($row = mysqli_fetch_array($result)) {
                                    echo '
                                                    <tr>
                                                        <td>' . $row["AccountCD"] . '</td>
                                                        <td>' . $row["AccountName"] . '</td>
                                                        <td>' . $row["AcctType"] . '</td>
                                                        <td>' . $row["DebitVsCredit"] . '</td>
                                                        <td>' . $row["HeaderVsDetail"] . '</td>
                                                    </tr>
                                                ';
                                  }
                                  ?>
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