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
  $query = "SELECT Grup FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  // Cek akses CRUD dan tentukan apakah akses diizinkan
  $hasCRUDAccess = strpos($row['Grup'], 'C') !== false || // Create
    strpos($row['Grup'], 'R') !== false || // Read
    strpos($row['Grup'], 'U') !== false || // Update
    strpos($row['Grup'], 'D') !== false;  // Delete
  
  // Jika tidak memiliki akses CRUD, tampilkan pesan dan redirect
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
          text: 'Anda tidak memiliki akses untuk mengubah data group.',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        });
      }
    });
  </script>

  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function editGroup(x) {
      Swal.fire({
        title: "Apakah anda yakin?",
        text: "Group dengan kode " + x.value + " akan diedit!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ya, setuju!",
        cancelButtonColor: "#d33",
        cancelButtonText: "Tidak"
      }).then((result) => {
        if (result.isConfirmed) {
          document.location = "edit-group.php?groupcd=" + x.value;
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
                  <p><b> Selamat! </b>Group baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "error") {
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "success-edit") {
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Group berhasil di edit dan disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "error-edit") {
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Error! </b>Terjadi kesalahan saat edit group ke database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                }
              }
              ?>
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>GROUP</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Group</li>
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
                          <p><b> Selamat! </b>Group baru berhasil disimpan ke database.</p>
                          <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                      } else if ($_GET["status"] == "error") {
                        echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                          <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                          <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                      } else if ($_GET["status"] == "success-edit") {
                        echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                          <p><b> Selamat! </b>Group berhasil di edit dan disimpan ke database.</p>
                          <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                      } else if ($_GET["status"] == "error-edit") {
                        echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                          <p><b> Error! </b>Terjadi kesalahan saat edit group ke database.</p>
                          <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                      }
                    }
                    ?>
                    <div class="row">
                      <div class="col-sm-6 ps-0">
                        <h3>GROUP</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Group</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex justify-content-between">
                          <?php
                          $canUpdate = false;
                          if (!empty($userID)) {
                            $query_access = "SELECT Grup FROM useraccesslevel WHERE UserID = '$userID'";
                            $result_access = mysqli_query($conn, $query_access);
                            if ($result_access) {
                              $row_access = mysqli_fetch_assoc($result_access);
                              $access_level = $row_access['Grup'];
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
                          <div class="col-md-2">
                            <select id="status-filter" class="form-control">
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                          aria-labelledby="myExtraLargeModal" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="myExtraLargeModal">Form Group Baru</h4>
                                <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body dark-modal">
                                <div class="card-body custom-input">
                                  <form class="row g-3" action="../Process/createGroup.php" method="POST">
                                    <div class="col-2">
                                      <label class="form-label" for="urutan">Urutan<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="urutan" name="urutan" type="text" placeholder="1"
                                        required>
                                    </div>
                                    <div class="col-10">
                                      <label class="form-label" for="kodegroup">Kode Group<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="kodegroup" name="kodegroup" type="text"
                                        placeholder="kode" required>
                                    </div>
                                    <div class="col-12">
                                      <label class="form-label" for="namagroup">Nama Group<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="namagroup" name="namagroup" type="text" required>
                                    </div>
                                    <div class="col-12">
                                      <div class="card-wrapper border rounded-3 checkbox-checked">
                                        <h6 class="sub-title">Status?<span style="color:red;">*</span></h6>
                                        <div class="radio-form">
                                          <div class="form-check">
                                            <input class="form-check-input" id="flexRadioDefault3" type="radio"
                                              name="groupStatus" value="1" required="">
                                            <label class="form-check-label" for="flexRadioDefault3">Active</label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" id="flexRadioDefault4" type="radio"
                                              name="groupStatus" value="0" required="">
                                            <label class="form-check-label" for="flexRadioDefault4">Inactive</label>
                                          </div>
                                        </div>
                                      </div>
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
                        <hr>
                        <h3>Daftar Group</h3>
                        <div class="table-responsive custom-scrollbar user-datatable">
                          <table class="display" id="basic-12">
                            <thead>
                              <tr>
                                <th>Urutan</th>
                                <th>Kode Group</th>
                                <th>Nama Group</th>
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

                              $query_access = "SELECT Grup FROM useraccesslevel WHERE UserID = '$creator'";
                              $result_access = mysqli_query($conn, $query_access);
                              $can_updatee = false;
                              if ($result_access) {
                                $row_access = mysqli_fetch_assoc($result_access);
                                $access_level = $row_access['Grup'];
                                if (strpos($access_level, 'U') !== false) {
                                  $can_updatee = true;
                                }
                              } else {
                                die("Error: Gagal mengambil data akses pengguna.");
                              }
                              $status_filter = isset($_GET['status']) ? $_GET['status'] : '1';
                              $query = "SELECT * FROM `groups`";
                              if ($status_filter == '1') {
                                $query .= " WHERE Status = 1";
                              } elseif ($status_filter == '0') {
                                $query .= " WHERE Status = 0";
                              }
                              $result = mysqli_query($conn, $query);

                              if ($result) {
                                while ($row = mysqli_fetch_array($result)) {
                                  echo '
                                  <tr>
                                      <td>' . $row["Sequence"] . '</td>
                                      <td>' . $row["GroupCD"] . '</td>
                                      <td>' . $row["GroupName"] . '</td>
                                    <td>' . $row["LastEdit"] . '</td>
                                    <td> 
                                        <ul class="action">';
                                  if ($can_updatee) {
                                    echo '<button onclick="editGroup(this)" type="button" class="light-card border-warning border b-r-10" value="' . $row["GroupCD"] . '"><i class="icon-pencil-alt txt-warning"></i></button>';
                                  }
                                  echo ' 
                                            
                                        </ul>
                                    </td>
                                </tr>
                            ';
                                }
                              } else {
                                echo "Error: " . mysqli_error($conn);
                              }
                              ?>

                            </tbody>
                          </table>
                          <script>
                            document.getElementById('status-filter').addEventListener('change', function () {
                              var status = this.value;
                              var xhr = new XMLHttpRequest();
                              xhr.open('GET', '../Process/filter-active-group.php?status=' + status, true);
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