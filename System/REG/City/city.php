<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";

  session_start();

  // Koneksi ke database
  include "../DBConnection.php"; // Sesuaikan dengan file koneksi database Anda
  
  // Ambil ID pengguna dari sesi atau cookie
  $userID = $_COOKIE['UserID']; // Sesuaikan dengan cara Anda menyimpan ID pengguna
  
  // Ambil akses level dari database
  $query = "SELECT Kota FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  // Cek akses CRUD dan tentukan apakah akses diizinkan
  $hasCRUDAccess = strpos($row['Kota'], 'C') !== false || // Create
    strpos($row['Kota'], 'R') !== false || // Read
    strpos($row['Kota'], 'U') !== false || // Update
    strpos($row['Kota'], 'D') !== false;  // Delete
  
  // Jika tidak memiliki akses CRUD, tampilkan pesan dan redirect
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
                  <p><b> Selamat! </b>Kota baru berhasil disimpan ke database.</p>
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
                <h3>KOTA</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Kontak</li>
                  <li class="breadcrumb-item">Kota</li>
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
                  <p><b> Selamat! </b>Kota baru berhasil disimpan ke database.</p>
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
                <h3>KOTA</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Kontak</li>
                  <li class="breadcrumb-item">Kota</li>
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
                    $query_access = "SELECT Kota FROM useraccesslevel WHERE UserID = '$userID'";
                    $result_access = mysqli_query($conn, $query_access);
                    if ($result_access) {
                      $row_access = mysqli_fetch_assoc($result_access);
                      $access_level = $row_access['Kota'];
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
                  <button class="btn btn-outline-primary" type="button" <?php echo $canUpdate ? '' : 'disabled'; ?>
                    data-bs-toggle="modal" data-bs-target="#cityModal">
                    <i class="fa fa-plus-circle"></i> New
                  </button>
                  <div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="cityModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title" id="cityModalLabel">Form Kota Baru</h4>
                          <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        </div>
                        <div class="modal-body dark-modal">
                          <div class="card-body custom-input">
                            <form class="row g-3" action="../Process/createCity.php" method="POST">
                              <div class="col-12">
                                <label class="form-label" for="namakota">Nama Kota</label>
                                <input class="form-control" id="namakota" name="namakota" type="text"
                                  placeholder="SURABAYA" required>
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
                        aria-selected="true">Daftar Kota</a></li>
                  </ul>
                  <hr>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="daftarBarang" role="tabpanel">
                      <h3>Daftar Kota</h3>
                      <div class="col-12">
                        <div class="table-responsive custom-scrollbar user-datatable">
                          <table class="display" id="basic-12">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Nama Kota</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $query = "SELECT * FROM city";
                              $result = mysqli_query($conn, $query);
                              while ($row = mysqli_fetch_array($result)) {
                                echo '
                                                            <tr>
                                                                <td>' . $row["CityID"] . '</td>
                                                                <td>' . $row["CityName"] . '</td>
                                                            </tr>';
                                ;
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