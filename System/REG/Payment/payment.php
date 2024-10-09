<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT TipePembayaran FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['TipePembayaran'], 'C') !== false || // Create
    strpos($row['TipePembayaran'], 'R') !== false || // Read
    strpos($row['TipePembayaran'], 'U') !== false || // Update
    strpos($row['TipePembayaran'], 'D') !== false;  // Delete
  
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
          text: 'Anda tidak memiliki akses untuk mengubah tipe pembayaran.',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        });
      }
    });
  </script>

  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function editPayment(x) {
      Swal.fire({
        title: "Apakah anda yakin?",
        text: "Pembayaran dengan kode " + x.value + " akan diedit!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ya, setuju!",
        cancelButtonColor: "#d33",
        cancelButtonText: "Tidak"
      }).then((result) => {
        if (result.isConfirmed) {
          document.location = "edit-payment.php?paymentcd=" + x.value;
        }
      });
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
                  <p><b> Selamat! </b>Tipe Pembayaran baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "error") {
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "success-edit") {
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Tipe Pembayaran berhasil di edit dan disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "error-edit") {
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Error! </b>Terjadi kesalahan saat edit tipe pembayaran ke database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                }
              }
              ?>
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>TIPE PEMBAYARAN</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Tipe Pembayaran</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <<div class="container-fluid <?php echo $accessDenied ? 'hidden' : ''; ?>">
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
                  <p><b> Selamat! </b>Tipe Pembayaran baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                      } else if ($_GET["status"] == "error") {
                        echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                      } else if ($_GET["status"] == "success-edit") {
                        echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Tipe Pembayaran berhasil di edit dan disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                      } else if ($_GET["status"] == "error-edit") {
                        echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Error! </b>Terjadi kesalahan saat edit tipe pembayaran ke database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                      }
                    }
                    ?>
                    <div class="row">
                      <div class="col-sm-6 ps-0">
                        <h3>TIPE PEMBAYARAN</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Tipe Pembayaran</li>
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
                              $query_access = "SELECT TipePembayaran FROM useraccesslevel WHERE UserID = '$userID'";
                              $result_access = mysqli_query($conn, $query_access);
                              if ($result_access) {
                                $row_access = mysqli_fetch_assoc($result_access);
                                $access_level = $row_access['TipePembayaran'];
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
                            <button class="btn btn-primary dropdown-toggle ms-2" type="button" data-bs-toggle="dropdown"
                              aria-expanded="false">Menu</button>
                            <ul class="dropdown-menu dropdown-block" id="myTab" role="tablist">
                              <li class="nav-item"><a class="dropdown-item active txt-primary f-w-500 f-18"
                                  id="home-tab" data-bs-toggle="tab" href="#daftarTipe" role="tab" aria-controls="home"
                                  aria-selected="true">Daftar Tipe</a></li>
                            </ul>
                          </div>
                          <div class="col-md-2">
                            <select id="status-filter" class="form-control">
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                            </select>
                          </div>
                        </div>
                        <hr>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="daftarTipe" role="tabpanel">
                            <h3>Daftar Tipe Pembayaran</h3>
                            <div class="table-responsive custom-scrollbar user-datatable">
                              <table class="display" id="basic-12">
                                <thead>
                                  <tr>
                                    <th>Kode Pembayaran</th>
                                    <th>Pembayaran</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Last Edit</th>
                                    <th>Action</th>
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
                                  $query = "SELECT * FROM payment";
                                  $result = mysqli_query($conn, $query);
                                  while ($row = mysqli_fetch_array($result)) {
                                    echo '
                                        <tr>
                                        <td>' . $row["PaymentCD"] . '</td>
                                        <td>' . $row["PaymentName"] . '</td>
                                        <td>' . $row["Description"] . '</td>';
                                    if ($row["Status"] == 1) {
                                      echo '<td><span class="badge badge-light-success">Active</span></td>';
                                    } else {
                                      echo '<td><span class="badge badge-light-danger">Inactive</span></td>';
                                    }
                                    echo '  
                                        <td>' . $row["LastEdit"] . '</td>
                                        <td> 
                                        <ul class="action">';
                                    if ($can_updatee) {
                                      echo '<button onclick="editPayment(this)" type="button" class="light-card border-warning border b-r-10" value="' . $row["PaymentCD"] . '"><i class="icon-pencil-alt txt-warning"></i></button>';
                                    }
                                    echo '                 
                                         </ul>
                                         </td>
                                     </tr>
                                 ';
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