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
  $query = "SELECT Supplier FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  // Cek akses CRUD dan tentukan apakah akses diizinkan
  $hasCRUDAccess = strpos($row['Supplier'], 'C') !== false || // Create
      strpos($row['Supplier'], 'R') !== false || // Read
      strpos($row['Supplier'], 'U') !== false || // Update
      strpos($row['Supplier'], 'D') !== false;  // Delete
  
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
          text: 'Anda tidak memiliki akses untuk mengubah data supplier.',
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
    function getBanks() {
      var url, bankOption;
      url = '../../indonesia-bank.json';

      $.getJSON(url, function (data) {
        //populate the banks datalist
        $(data.banks).each(function () {
          bankOption = "<option value=\"" + this.code + "\">" + this.name + "</option>";
          $('#banksOptions').append(bankOption);
        });
      });
    }

    function viewSupplier(str) {
      document.location = "view-supplier.php?id=" + str.value;
    }

    function editSupplier(str) {
      document.location = "edit-supplier.php?id=" + str.value;
    }

    function deleteSupplier(str) {
      Swal.fire({
        title: "Apakah anda yakin?",
        text: "Supplier dengan ID " + str.value + " akan dihapus dari database!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ya, setuju!",
        cancelButtonColor: "#d33",
        cancelButtonText: "Tidak"
      }).then((result) => {
        if (result.isConfirmed) {
          document.location = "../Process/deleteSupplier.php?id=" + str.value;
        }
      });
    }
  </script>
  <script>window.onload = getBanks;</script>
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
                  <p><b> Selamat! </b>Supplier baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else if ($_GET["status"] == "error") {
                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else if ($_GET["status"] == "success-edit") {
                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Selamat! </b>Supplier berhasil di edit dan disimpan ke database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
              } else if ($_GET["status"] == "error-edit") {
                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                      <p><b> Error! </b>Terjadi kesalahan saat edit supplier ke database.</p>
                      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
              } else if ($_GET["status"] == "success-delete") {
                echo '<div class="alert txt-warning border-warning outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Selamat! </b>Supplier berhasil di hapus dari database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
              }
            }
            ?>
            <div class="row">
              <div class="col-sm-6 ps-0">
                <h3>SUPPLIER</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Kontak</li>
                  <li class="breadcrumb-item">Supplier</li>
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
                  <p><b> Selamat! </b>Supplier baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else if ($_GET["status"] == "error") {
                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else if ($_GET["status"] == "success-edit") {
                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Selamat! </b>Supplier berhasil di edit dan disimpan ke database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
              } else if ($_GET["status"] == "error-edit") {
                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                      <p><b> Error! </b>Terjadi kesalahan saat edit supplier ke database.</p>
                      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
              } else if ($_GET["status"] == "success-delete") {
                echo '<div class="alert txt-warning border-warning outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Selamat! </b>Supplier berhasil di hapus dari database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
              }
            }
            ?>
            <div class="row">
              <div class="col-sm-6 ps-0">
                <h3>SUPPLIER</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Kontak</li>
                  <li class="breadcrumb-item">Supplier</li>
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
                    $query_access = "SELECT Supplier FROM useraccesslevel WHERE UserID = '$userID'";
                    $result_access = mysqli_query($conn, $query_access);
                    if ($result_access) {
                      $row_access = mysqli_fetch_assoc($result_access);
                      $access_level = $row_access['Supplier'];
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
                    data-bs-toggle="modal" data-bs-target=".bd-example-modal-xl">
                    <i class="fa fa-plus-circle"></i> New
                  </button>
                  <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog"
                    aria-labelledby="myExtraLargeModal" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title" id="myExtraLargeModal">Form Supplier Baru</h4>
                          <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        </div>
                        <div class="modal-body dark-modal">
                          <div class="card-body custom-input">
                            <form class="row g-3" action="../Process/createSupplier.php" method="POST">
                              <div class="col-12">
                                <label class="form-label" for="namasupplier">Nama Supplier<span
                                    style="color:red;">*</span></label>
                                <input class="form-control" id="namasupplier" name="namasupplier" type="text"
                                  placeholder="-" aria-label="First name" required>
                              </div>
                              <hr>
                              <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item"><a class="nav-link active txt-primary" id="home-tab"
                                    data-bs-toggle="tab" href="#informasi" role="tab" aria-controls="home"
                                    aria-selected="true">Informasi</a></li>
                                <li class="nav-item"><a class="nav-link txt-primary" id="profile-tabs"
                                    data-bs-toggle="tab" href="#pajak" role="tab" aria-controls="profile"
                                    aria-selected="false">Pajak</a></li>
                                <li class="nav-item"><a class="nav-link txt-primary" id="bank-tabs" data-bs-toggle="tab"
                                    href="#bank" role="tab" aria-controls="bank" aria-selected="false">Bank</a></li>
                              </ul>
                              <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="informasi" role="tabpanel"
                                  aria-labelledby="home-tab">
                                  <div class="row g-3">
                                    <!-- informasi starts here -->
                                    <div class="col-12">
                                      <label class="form-label" for="alamat">Alamat<span
                                          style="color:red;">*</span></label>
                                      <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                        required></textarea>
                                    </div>
                                    <div class="col-6">
                                      <label class="form-label" for="email">Email</label>
                                      <input class="form-control" id="email" name="email" type="email"
                                        placeholder="example@saeoil.com">
                                    </div>
                                    <div class="col-3">
                                      <label class="form-label" for="telepon">No. Telepon</label>
                                      <input class="form-control" id="telepon" name="telepon" type="text" minlength="10"
                                        maxlength="12" placeholder="031xxxxxxx">
                                    </div>
                                    <div class="col-3">
                                      <label class="form-label" for="hpsupplier">No. HP Supplier</label>
                                      <input class="form-control" id="hpsupplier" name="hpsupplier" type="text"
                                        minlength="10" maxlength="12" placeholder="081xxxxxxx">
                                    </div>
                                    <div class="col-6">
                                      <label class="form-label" for="namakontak">Nama Kontak<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="namakontak" name="namakontak" type="text"
                                        placeholder="-">
                                    </div>
                                    <div class="col-6">
                                      <label class="form-label" for="handphone">No. HP Kontak<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="handphone" name="handphone" type="text"
                                        minlength="10" maxlength="12" placeholder="081xxxxxxx" required>
                                    </div>
                                    <div class="col-12">
                                      <label class="form-label" for="description">Description</label>
                                      <textarea class="form-control" id="description" name="description"
                                        rows="2"></textarea>
                                    </div>
                                    <div class="col-6">
                                      <div class="card-wrapper border rounded-3 checkbox-checked">
                                        <h6 class="sub-title">Approval?<span style="color:red;">*</span></h6>
                                        <div class="radio-form">
                                          <div class="form-check">
                                            <input class="form-check-input" id="flexRadioDefault1" type="radio"
                                              value="1" name="approval" required="">
                                            <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" id="flexRadioDefault2" type="radio"
                                              value="0" name="approval" required="">
                                            <label class="form-check-label" for="flexRadioDefault2">No</label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="card-wrapper border rounded-3 checkbox-checked">
                                        <h6 class="sub-title">Status?<span style="color:red;">*</span></h6>
                                        <div class="radio-form">
                                          <div class="form-check">
                                            <input class="form-check-input" id="flexRadioDefault3" type="radio"
                                              value="1" name="suppStatus" required="">
                                            <label class="form-check-label" for="flexRadioDefault3">Active</label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" id="flexRadioDefault4" type="radio"
                                              value="0" name="suppStatus" required="">
                                            <label class="form-check-label" for="flexRadioDefault4">Inactive</label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="tab-pane fade show" id="pajak" role="tabpanel">
                                  <div class="row g-3">
                                    <div class="col-12">
                                      <label class="form-label" for="namaNPWP">Nama NPWP<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="namaNPWP" name="namaNPWP" type="text"
                                        placeholder="-" required>
                                    </div>
                                    <div class="col-12">
                                      <label class="form-label" for="nomorNPWP">Nomor NPWP<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="nomorNPWP" name="nomorNPWP" type="text"
                                        placeholder="-" required>
                                    </div>
                                    <div class="col-12">
                                      <label class="form-label" for="alamatNPWP">Alamat NPWP<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="alamatNPWP" name="alamatNPWP" type="text"
                                        placeholder="-" required>
                                    </div>
                                  </div>
                                </div>
                                <div class="tab-pane fade show" id="bank" role="tabpanel">
                                  <div class="row g-3">
                                    <div class="col-6">
                                      <label class="col-sm-12 form-label" for="beneficiaryBank">Bank Penerima<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="beneficiaryBank" name="beneficiaryBank"
                                        list="banksOptions" placeholder="Pilih Bank" required>
                                      <datalist id="banksOptions">
                                        <!-- option javascript -->
                                      </datalist>
                                    </div>
                                    <div class="col-6">
                                      <label class="form-label" for="norek">Nomor Rekening<span
                                          style="color:red;">*</span></label>
                                      <input class="form-control" id="norek" name="norek" type="text" minlength="10"
                                        maxlength="12" placeholder="xxxxxxxxxx" required>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <hr>
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
                        aria-selected="true">Daftar Supplier</a></li>
                    <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18" id="contact-tab"
                        data-bs-toggle="tab" href="#historiBarang" role="tab" aria-controls="contact"
                        aria-selected="false">Histori</a></li>
                  </ul>
                  <hr>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="daftarBarang" role="tabpanel">
                      <h3>Daftar Supplier</h3>
                      <div class="table-responsive custom-scrollbar user-datatable">
                        <table class="display" id="basic-12">
                          <thead>
                            <tr>
                              <th scope="col">ID</th>
                              <th scope="col">Nama Supplier</th>
                              <th scope="col">Alamat</th>
                              <th scope="col">Contact Person</th>
                              <th scope="col">Approval</th>
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
                            $query_access = "SELECT Supplier FROM useraccesslevel WHERE UserID = '$creator'";
                            $result_access = mysqli_query($conn, $query_access);
                            $can_updatee = false;
                            if ($result_access) {
                              $row_access = mysqli_fetch_assoc($result_access);
                              $access_level = $row_access['Supplier'];
                              if (strpos($access_level, 'U') !== false) {
                                $can_updatee = true;
                              }
                            } else {
                              die("Error: Gagal mengambil data akses pengguna.");
                            }
                            $query = "SELECT * FROM supplier";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_array($result)) {
                              echo '
                                                        <tr>
                                                            <td>' . $row["SupplierNum"] . '</td>
                                                            <td>' . $row["SupplierName"] . '</td>
                                                            <td>' . $row["SupplierAdd"] . '</td>
                                                            <td>' . $row["ContactName"] . '</td>';
                              if ($row["Approval"] == 1) {
                                echo '<td><span class="badge badge-light-danger">Yes</span></td>';
                              } else {
                                echo '<td><span class="badge badge-light-success">No</span></td>';
                              }
                              if ($row["Status"] == 1) {
                                echo '<td><span class="badge badge-light-success">Active</span></td>';
                              } else {
                                echo '<td><span class="badge badge-light-danger">Inactive</span></td>';
                              }
                              echo '  <td>' . $row["LastEdit"] . '</td>
                                                            <td> 
                                                            <ul class="action"> 
                                                                <button onclick="viewSupplier(this)" type="button" class="light-card border-primary border b-r-10" value="' . $row["SupplierNum"] . '" style="margin-left:3px;"><i class="fa fa-eye txt-primary"></i></button>';
                              if ($can_updatee) {
                                echo '<button onclick="editSupplier(this)" type="button" class="light-card border-warning border b-r-10" value="' . $row["SupplierNum"] . '" style="margin-left:3px;"><i class="fa fa-pencil-square-o txt-warning"></i></button>';
                              }
                              echo '
                                                                ';
                              //<button onclick="deleteSupplier(this)" type="button" class="light-card border-danger border b-r-10" value="'.$row["SupplierNum"].'" style="margin-left:3px;"><i class="icon-trash txt-danger"></i></button>
                              echo '  </ul>
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
                      <h3>Histori Supplier</h3>
                      <br>
                      <div class="row">
                        <div class="col-md-3">
                          <form action="../testForm.php" method="post">
                            <div class="select-box">
                              <div class="options-container">
                                <div class="selection-option">
                                  <input class="radio" id="webdesigner" type="radio" name="barang" value="SUPP-0001">
                                  <label class="mb-0" for="webdesigner">Tjiwi</label>
                                </div>
                                <div class="selection-option">
                                  <input class="radio" id="film" type="radio" name="barang" value="SUPP-0002">
                                  <label class="mb-0" for="film">Tjakrawala Mega</label>
                                </div>
                                <div class="selection-option">
                                  <input class="radio" id="film" type="radio" name="barang" value="SUPP-0003">
                                  <label class="mb-0" for="film">PT Sentosa Jaya Selalu</label>
                                </div>
                              </div>
                              <div class="selected-box">Pilih Nama Pelanggan</div>
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
                                <td>Nomor Pelanggan :</td>
                                <td colspan="1">SUPP-0001 </td>
                              </tr>
                              <tr>
                                <td>Nama Pelanggan :</td>
                                <td colspan="1">Tjiwi</td>
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
  <!-- Plugins JS Ends-->
  <!-- Theme js-->
  <script src="../../assets/js/script.js"></script>
  <!-- Plugin used-->
</body>

</html>