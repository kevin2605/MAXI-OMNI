<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT pMesin FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['pMesin'], 'C') !== false || // Create
    strpos($row['pMesin'], 'R') !== false || // Read
    strpos($row['pMesin'], 'U') !== false || // Update
    strpos($row['pMesin'], 'D') !== false;  // Delete
  
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
          text: 'Anda tidak memiliki akses untuk edit data mesin.',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        });
      }
    });
  </script>

  <!-- AJAX SCRIPT and DYNAMIC TABLE -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function calTarget() {
      var speed = document.getElementById("speed").value;
      var cavity = document.getElementById("cavity").value;
      var target = speed * cavity * 60;
      document.getElementById("mintarget").value = target * 0.85;
      document.getElementById("maxtarget").value = target;
    }

    function editMachine(x) {
      Swal.fire({
        title: "Apakah anda yakin?",
        text: "Mesin dengan kode " + x.value + " akan di edit!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ya, setuju!",
        cancelButtonColor: "#d33",
        cancelButtonText: "Tidak"
      }).then((result) => {
        if (result.isConfirmed) {
          document.location = "edit-machine.php?maccd=" + x.value;
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
                  <p><b> Selamat! </b>Mesin Baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "error") {
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                } else if ($_GET["status"] == "success-edit") {
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Mesin berhasil diedit ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }
              }
              ?>
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>MESIN</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Mesin</li>
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
                        <p><b> Selamat! </b>Mesin Baru berhasil disimpan ke database.</p>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                      } else if ($_GET["status"] == "error") {
                              echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                        <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                      } else if ($_GET["status"] == "success-edit") {
                              echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                        <p><b> Error! </b>Mesin berhasil diedit ke database.</p>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                      }
                    }
                    ?>
                    <div class="row">
                      <div class="col-sm-6 ps-0">
                        <h3>MESIN</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Mesin</li>
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
                        $hasAccess = false;
                        $userID = isset($_COOKIE["UserID"]) ? $_COOKIE["UserID"] : '';

                        if (!empty($userID)) {
                          $query_access = "SELECT pMesin FROM useraccesslevel WHERE UserID = '$userID'";
                          $result_access = mysqli_query($conn, $query_access);

                          if ($result_access) {
                            $row_access = mysqli_fetch_assoc($result_access);
                            $access_level = $row_access['pMesin'];
                            // Periksa apakah 'C' ada dalam string akses
                            if (strpos($access_level, 'C') !== false) {
                              $hasAccess = true;
                            }
                          } else {
                            die("Error: Gagal mengambil data akses pengguna.");
                          }
                        } else {
                          die("Error: Cookie 'UserID' tidak ada atau kosong.");
                        }
                        ?>
                        <button class="btn btn-outline-primary" type="button" <?php echo $hasAccess ? 'data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg1"' : 'disabled'; ?>>
                          <i class="fa fa-plus-circle"></i> New
                        </button>

                        <div class="modal fade bd-example-modal-lg1" tabindex="-1" role="dialog"
                          aria-labelledby="myExtraLargeModal" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="myExtraLargeModal">Form Mesin Baru</h4>
                                <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body dark-modal">
                                <div class="card-body custom-input">
                                  <form class="row g-3" action="../Process/createMachine.php" method="POST">
                                    <div class="col-3">
                                      <label class="form-label" for="machinecd">Kode Mesin</label>
                                      <input class="form-control" id="machinecd" name="machinecd" type="text"
                                        placeholder="1" required>
                                    </div>
                                    <div class="col-9">
                                      <label class="form-label" for="machinename">Nama Mesin</label>
                                      <input class="form-control" id="machinename" name="machinename" type="text"
                                        placeholder="Mesin A" required>
                                    </div>
                                    <div class="col-2">
                                      <label class="col-sm-12 col-form-label" for="seq">Urutan</label>
                                      <input class="form-control" id="seq" name="seq" type="number" placeholder="1"
                                        required>
                                    </div>
                                    <div class="col-2">
                                      <label class="col-sm-12 col-form-label" for="speed">Speed</label>
                                      <input class="form-control digits" id="speed" name="speed" type="number"
                                        placeholder="0" onkeyup="calTarget()" required>
                                    </div>
                                    <div class="col-2">
                                      <label class="col-sm-12 col-form-label" for="cavity">Cavity</label>
                                      <input class="form-control digits" id="cavity" name="cavity" type="number"
                                        placeholder="0" onkeyup="calTarget()" required>
                                    </div>
                                    <div class="col-3">
                                      <label class="col-sm-12 col-form-label" for="mintarget">Target 85%</label>
                                      <input class="form-control" id="mintarget" name="mintarget" type="number"
                                        placeholder="0" readonly>
                                    </div>
                                    <div class="col-3">
                                      <label class="col-sm-12 col-form-label" for="maxtarget">Target 100%</label>
                                      <input class="form-control" id="maxtarget" name="maxtarget" type="number"
                                        placeholder="0" readonly>
                                    </div>
                                    <div class="col-12">
                                      <div class="card-wrapper border rounded-3 checkbox-checked">
                                        <h6 class="sub-title">Status?</h6>
                                        <div class="radio-form">
                                          <div class="form-check">
                                            <input class="form-check-input" id="flexRadioDefault3" type="radio"
                                              value="1" name="machineStatus" required="">
                                            <label class="form-check-label" for="flexRadioDefault3">Active</label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" id="flexRadioDefault4" type="radio"
                                              value="0" name="machineStatus" required="">
                                            <label class="form-check-label" for="flexRadioDefault4">Inactive</label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-check form-switch">
                                        <input class="form-check-input" id="flexSwitchCheckDefault" type="checkbox"
                                          role="switch" required>
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Are you sure
                                          above
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
                        <h3>Daftar Mesin</h3>
                        <br>
                        <div class="table-responsive custom-scrollbar user-datatable">
                          <table class="display" id="basic-12">
                            <thead>
                              <tr>
                                <th scope="col">Urutan</th>
                                <th scope="col">Kode Mesin</th>
                                <th scope="col">Nama Mesin</th>
                                <th scope="col">Kecepatan</th>
                                <th scope="col">Cavity</th>
                                <th scope="col">Target/Jam</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
                                $creator = $_COOKIE["UserID"];
                              } else {
                                die("Error: Cookie 'UserID' tidak ada atau kosong.");
                              }
                              $query_access = "SELECT pMesin FROM useraccesslevel WHERE UserID = '$creator'";
                              $result_access = mysqli_query($conn, $query_access);
                              $can_update = false;
                              if ($result_access) {
                                $row_access = mysqli_fetch_assoc($result_access);
                                $access_level = $row_access['pMesin'];
                                if (strpos($access_level, 'R') !== false) {
                                  $can_update = true;
                                }
                              } else {
                                die("Error: Gagal mengambil data akses pengguna.");
                              }

                              $query = "SELECT * FROM machine";
                              $result = mysqli_query($conn, $query);

                              while ($row = mysqli_fetch_array($result)) {
                                $minTarget = $row["MinTargetPerHour"];
                                $maxTarget = $row["MaxTargetPerHour"];
                                $targetPerHour = $minTarget . ' - ' . $maxTarget;

                                echo '
                                      <tr>
                                        <td>' . $row["Sequence"] . '</td>
                                        <td>' . $row["MachineCD"] . '</td>
                                        <td>' . $row["MachineName"] . '</td>
                                        <td>' . $row["Speed"] . '</td>
                                        <td>' . $row["Cavity"] . '</td>
                                        <td>' . $targetPerHour . '</td>';
                                                if ($row["Status"] == 1) {
                                                  echo '<td><span class="badge badge-light-success">Active</span></td>';
                                                } else {
                                                  echo '<td><span class="badge badge-light-danger">Inactive</span></td>';
                                                }
                                                echo '
                                      <td>';
                                                if ($can_update) {
                                                  echo '<button onclick="editMachine(this)" type="button" class="light-card border-warning border b-r-10" value="' . $row["MachineCD"] . '"><i class="fa fa-pencil-square-o txt-warning"></i></button>';
                                                }
                                                echo ' 
                                        
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