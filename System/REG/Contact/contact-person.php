<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  include "../DBConnection.php";
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
          text: 'Anda tidak memiliki akses untuk mengubah kontak.',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        });
      }
    });
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
            <?php
            if (isset($_GET["status"])) {
              if ($_GET["status"] == "success") {
                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Kontak baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else if ($_GET["status"] == "error") {
                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else if ($_GET["status"] == "success-edit") {
                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Kontak berhasil di edit dan disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else if ($_GET["status"] == "error-edit") {
                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Error! </b>Terjadi kesalahan saat edit kontak ke database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
              }
            }
            ?>
            <div class="row">
              <div class="col-sm-6 ps-0">
                <h3>KONTAK</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Kontak</li>
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
                  $canUpdate = false;
                  if (!empty($userID)) {
                    $query_access = "SELECT ContactPerson FROM useraccesslevel WHERE UserID = '$userID'";
                    $result_access = mysqli_query($conn, $query_access);
                    if ($result_access) {
                      $row_access = mysqli_fetch_assoc($result_access);
                      $access_level = $row_access['ContactPerson'];
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
                    data-toggle="modal" data-target="#contactModal">
                    <i class="fa fa-plus-circle"></i> New
                  </button>
                  <div class="modal fade" id="contactModal" tabindex="-1" role="dialog"
                    aria-labelledby="contactModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title" id="contactModalLabel">Form Kontak Baru</h4>
                          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="card-body">
                            <form class="row g-3" action="../Process/createContact.php" method="POST">
                              <div class="col-12">
                                <label class="form-label" for="namakontak">Nama Kontak<span
                                    style="color:red;">*</span></label>
                                <input class="form-control" id="namakontak" name="namakontak" type="text"
                                  placeholder="First name" required>
                              </div>
                              <div class="col-12">
                                <label class="form-label" for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
                              </div>
                              <div class="col-6">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email"
                                  placeholder="example@gmail.com">
                              </div>
                              <div class="col-3">
                                <label class="form-label" for="telepon">Telepon</label>
                                <input class="form-control" id="telepon" name="telepon" type="text" minlength="10"
                                  maxlength="12" placeholder="031xxxxxxx">
                              </div>
                              <div class="col-3">
                                <label class="form-label" for="handphone">No. HP<span
                                    style="color:red;">*</span></label>
                                <input class="form-control" id="handphone" name="handphone" type="text" minlength="10"
                                  maxlength="12" placeholder="081xxxxxxx" required>
                              </div>
                              <div class="col-12">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                              </div>
                              <div class="col-12">
                                <div class="card-wrapper border rounded-3 checkbox-checked">
                                  <h6 class="sub-title">Status?</h6>
                                  <div class="radio-form">
                                    <div class="form-check">
                                      <input class="form-check-input" id="flexRadioDefault3" type="radio"
                                        name="kontakStatus" value="1" required="">
                                      <label class="form-check-label" for="flexRadioDefault3">Active</label>
                                    </div>
                                    <div class="form-check">
                                      <input class="form-check-input" id="flexRadioDefault4" type="radio"
                                        name="kontakStatus" value="0" required="">
                                      <label class="form-check-label" for="flexRadioDefault4">Inactive</label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12">
                                <div class="form-check form-switch">
                                  <input class="form-check-input" id="flexSwitchCheckDefault" type="checkbox"
                                    role="switch" required>
                                  <label class="form-check-label" for="flexSwitchCheckDefault">Apakah informasi diatas
                                    sudah benar?</label>
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
                        aria-selected="true">Daftar Pelanggan</a></li>
                    <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18" id="contact-tab"
                        data-bs-toggle="tab" href="#historiBarang" role="tab" aria-controls="contact"
                        aria-selected="false">Histori</a></li>
                  </ul>
                  <hr>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="daftarBarang" role="tabpanel">
                      <h3>Daftar Kontak</h3>
                      <div class="table-responsive custom-scrollbar user-datatable">
                        <table class="display" id="basic-12">
                          <thead>
                            <tr>
                              <th scope="col">Kontak ID</th>
                              <th scope="col">Nama Kontak</th>
                              <th scope="col">No. HP</th>
                              <th scope="col">Email</th>
                              <th scope="col">Status</th>
                              <th scope="col">Last edit</th>
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
                            $query_access = "SELECT ContactPerson FROM useraccesslevel WHERE UserID = '$creator'";
                            $result_access = mysqli_query($conn, $query_access);
                            $can_updatee = false;
                            if ($result_access) {
                              $row_access = mysqli_fetch_assoc($result_access);
                              $access_level = $row_access['ContactPerson'];
                              if (strpos($access_level, 'U') !== false) {
                                $can_updatee = true;
                              }
                            } else {
                              die("Error: Gagal mengambil data akses pengguna.");
                            }
                            $query = "SELECT * FROM contactperson";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_array($result)) {
                              echo '
                                <tr>
                                    <td>' . $row["ContactNum"] . '</td>
                                    <td>' . $row["ContactName"] . '</td>
                                    <td>' . $row["ContactPhone"] . '</td>
                                    <td>' . $row["ContactEmail"] . '</td>';
                              if ($row["Status"] == 1) {
                                echo '<td><span class="badge badge-light-success">Active</span></td>';
                              } else {
                                echo '<td><span class="badge badge-light-danger">Inactive</span></td>';
                              }
                              echo '  <td>' . $row["LastEdit"] . '</td>
                                                        <td> 
                                                        <ul class="action">';
                              if ($can_updatee) {
                                echo '<li class="edit"> <a href="edit-contact.php?contactnum=' . $row["ContactNum"] . '"><i class="icon-pencil-alt"></i></a></li>';
                              }
                              echo '
                                                            
                                        <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                    </td>
                                </tr>
                            ';
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="historiBarang" role="tabpanel">
                      <h3>Histori Pelanggan</h3>
                      <br>
                      <div class="row">
                        <div class="col-md-3">
                          <form action="../testForm.php" method="post">
                            <div class="select-box">
                              <div class="options-container">
                                <div class="selection-option">
                                  <input class="radio" id="webdesigner" type="radio" name="barang" value="CUST-00001">
                                  <label class="mb-0" for="webdesigner">Kevin Christian Mulia</label>
                                </div>
                                <div class="selection-option">
                                  <input class="radio" id="film" type="radio" name="barang" value="CUST-00002">
                                  <label class="mb-0" for="film">Bryan Christian Mulia</label>
                                </div>
                              </div>
                              <div class="selected-box">Pilih Nama Supplier</div>
                              <div class="search-box">
                                <input type="text" placeholder="Cari disini...">
                              </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-md"><i
                                class="icofont icofont-ui-search"></i></button>
                          </form>
                        </div>
                        <div class="col-md-8 offset-1">
                          <table class="table">
                            <tfoot>
                              <tr>
                                <td>Kode Supplier :</td>
                                <td colspan="1">CUST-00001 </td>
                              </tr>
                              <tr>
                                <td>Nama Supplier :</td>
                                <td colspan="1">Kevin Christian Mulia</td>
                              </tr>
                              <tr>
                                <td>Dibuat oleh :</td>
                                <td colspan="2">System</td>
                              </tr>
                              <tr>
                                <td>Tanggal dibuat :</td>
                                <td colspan="1">2024-04-01</td>
                              </tr>
                              <tr>
                            </tfoot>
                          </table>
                          <br>
                          <h3>Histori Pembelian</h3>
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
  <script src="../../assets/js/select2/custom-inputsearch.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- Plugins JS Ends-->
  <!-- Theme js-->
  <script src="../../assets/js/script.js"></script>
  <!-- Plugin used-->
</body>

</html>