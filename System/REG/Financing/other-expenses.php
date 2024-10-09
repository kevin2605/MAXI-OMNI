<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT otherexp FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['otherexp'], 'C') !== false || // Create
    strpos($row['otherexp'], 'R') !== false || // Read
    strpos($row['otherexp'], 'U') !== false || // Update
    strpos($row['otherexp'], 'D') !== false;  // Delete
  
  $accessDenied = !$hasCRUDAccess;
  ?>

  <!-- AJAX SCRIPT and DYNAMIC TABLE -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    var i = 1;

    function appendTable(x) {
      i++;
      $('#data #datalist').append(`
                <tr id="row${i}">
                    <td style="width: 15%;">
                        <input type="text" class="form-control prodlist" name="akun[]" list="prodOptions" onChange="appendTable(this)" required>
                        <datalist id="prodOptions">
                            <?php
                            $queryp = "SELECT * FROM chartofaccount";
                            $resultp = mysqli_query($conn, $queryp);
                            while ($rowp = mysqli_fetch_array($resultp)) {
                              echo '<option value="' . $rowp["AccountCD"] . '">' . $rowp["AccountName"] . '</option>';
                            }
                            ?>
                        </datalist>
                    </td>
                    <td style="width: 45%;">
                        <input type="text" class="form-control" name="namaakun[]" placeholder="-" readonly>
                    </td>
                    <td style="width: 15%;">
                        <input type="number" class="form-control digits debit" name="debit[]" oninput="disableOtherInput(this)" placeholder="0">
                    </td>
                    <td style="width: 15%;">
                        <input type="number" class="form-control digits credit" name="credit[]" oninput="disableOtherInput(this)" placeholder="0">
                    </td>
                    <td>
                        <button id="${i}" type="button" class="btn btn-danger bremove" style="padding:5px 10px 5px 10px;">
                            <i class="icofont icofont-close-line-circled"></i>
                        </button>
                    </td>
                </tr>`);

      //get account name
      $.ajax({
        type: "POST",
        url: "../Process/getAccountName.php",
        data: "acctcd=" + x.value,
        success: function (result) {
          var res = JSON.parse(result);
          $.each(res, function (index, value) {
            x.parentElement.parentElement.cells[1].getElementsByTagName("input")[0].value = value.AccountName;
          });
        }
      });
    }

    $("document").ready(function () {
      $(document).on('click', '.bremove', function () {
        i--;
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
      });
    });

    function closeModal() {
      $('.modal-jurnal-umum').modal('toggle');
    }

    function disableOtherInput(currentInput) {
      let row = currentInput.closest('tr');
      let flowinInput = row.querySelector('.debit');
      let flowoutInput = row.querySelector('.credit');

      if (currentInput === flowinInput && flowinInput.value !== '') {
        flowoutInput.setAttribute('readonly', true);
      } else if (currentInput === flowoutInput && flowoutInput.value !== '') {
        flowinInput.setAttribute('readonly', true);
      }
      if (flowinInput.value === '') {
        flowoutInput.removeAttribute('readonly');
      }
      if (flowoutInput.value === '') {
        flowinInput.removeAttribute('readonly');
      }
    }

    function view(x) {
      document.location = "view-other-expense.php?id=" + x.value;
    }
  </script>
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
              <?php
              if (isset($_GET["status"])) {
                if ($_GET["status"] == "success") {
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Selamat! </b>Biaya lain-lain berhasil disimpan ke database.</p>
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
                  <h3>BIAYA LAIN LAIN</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Keuangan</li>
                    <li class="breadcrumb-item">Biaya Lain-lain</li>
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
                    <p><b> Selamat! </b>Biaya lain-lain berhasil disimpan ke database.</p>
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
                        <h3>BIAYA LAIN LAIN</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Keuangan</li>
                          <li class="breadcrumb-item">Biaya Lain-lain</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-12">
                            <?php
                            $canCreate = false;
                            $userID = isset($_COOKIE["UserID"]) ? $_COOKIE["UserID"] : '';
                            if (!empty($userID)) {
                              $query_access = "SELECT otherexp FROM useraccesslevel WHERE UserID = '$userID'";
                              $result_access = mysqli_query($conn, $query_access);
                              if ($result_access) {
                                $row_access = mysqli_fetch_assoc($result_access);
                                $access_level = $row_access['otherexp'];
                                if (strpos($access_level, 'C') !== false) {
                                  $canCreate = true;
                                }
                              } else {
                                die("Error: Gagal mengambil data akses pengguna.");
                              }
                            } else {
                              die("Error: Cookie 'UserID' tidak ada atau kosong.");
                            }
                            ?>
                            <button class="btn btn-outline-primary" type="button" <?php echo $canCreate ? 'data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg"' : 'disabled'; ?>><i
                                class="fa fa-plus-circle"></i> New</button>
                            <div class="modal fade modal-jurnal-umum" tabindex="-1" role="dialog"
                              aria-labelledby="myExtraLargeModal" aria-hidden="true">
                              <div class="modal-dialog modal-fullscreen">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title" id="myExtraLargeModal">Form Biaya Lain-lain</h4>
                                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                      aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body dark-modal">
                                    <div class="card-body custom-input">
                                      <form class="row g-3" action="../Process/createOtherExpense.php" method="POST">
                                        <div class="row">
                                          <div class="col-12">
                                            <div class="mb-3 row">
                                              <label class="col-sm-1">#ID<span style="color:red;">*</span></label>
                                              <div class="col-sm-2">
                                                <input class="form-control" type="text" value="auto-generated" readonly>
                                              </div>
                                            </div>
                                            <div class="mb-3 row">
                                              <label class="col-sm-1">Tanggal<span style="color:red;">*</span></label>
                                              <div class="col-sm-2">
                                                <input class="form-control" name="tanggal" type="date" value="<?php date_default_timezone_set("Asia/Jakarta");
                                                echo date('Y-m-d'); ?>" required>
                                              </div>
                                            </div>
                                            <div class="mb-3 row">
                                              <label class="col-sm-1">Memo<span style="color:red;">*</span></label>
                                              <div class="col-sm-2">
                                                <input class="form-control" name="memo" type="text" required>
                                              </div>
                                              <div class="col-sm-3">
                                                <input class="form-control" name="memodesc" type="text" required>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-6">
                                            <label class="form-label" for="desc">Keterangan</label>
                                            <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
                                          </div>
                                        </div>
                                        <hr>
                                        <div class="col-md-8">
                                          <div class="table-responsive custom-scrollbar signal-table">
                                            <table class="table" id="data">
                                              <thead>
                                                <tr>
                                                  <th scope="col">Kode Akun</th>
                                                  <th scope="col">Nama Akun</th>
                                                  <th scope="col">Debit</th>
                                                  <th scope="col">Credit</th>
                                                  <th scope="col">Action</th>
                                                </tr>
                                              </thead>
                                              <tbody id="datalist">
                                                <tr id="row1">
                                                  <td style="width: 15%;">
                                                    <input type="text" class="form-control prodlist" name="akun[]"
                                                      list="prodOptions" onChange="appendTable(this)" required>
                                                    <datalist id="prodOptions">
                                                      <?php
                                                      $queryp = "SELECT * FROM chartofaccount";
                                                      $resultp = mysqli_query($conn, $queryp);
                                                      while ($rowp = mysqli_fetch_array($resultp)) {
                                                        echo '<option value="' . $rowp["AccountCD"] . '">' . $rowp["AccountName"] . '</option>';
                                                      }
                                                      ?>
                                                    </datalist>
                                                  </td>
                                                  <td style="width: 45%;">
                                                    <input type="text" class="form-control" name="namaakun[]"
                                                      placeholder="-" readonly>
                                                  </td>
                                                  <td style="width: 15%;">
                                                    <input type="number" class="form-control digits debit"
                                                      name="debit[]" oninput="disableOtherInput(this)" placeholder="0">
                                                  </td>
                                                  <td style="width: 15%;">
                                                    <input type="number" class="form-control digits credit"
                                                      name="credit[]" oninput="disableOtherInput(this)" placeholder="0">
                                                  </td>
                                                  <td style="width: 10%;">

                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                          <div class="col-8">
                                            <button class="btn btn-warning" type="button"
                                              onclick="closeModal()">Back</button>
                                            <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                                          </div>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <hr>
                            <h3>Biaya Lain-lain</h3>
                            <div class="col-md-12">
                              <div class="table-responsive custom-scrollbar user-datatable">
                                <table class="display" id="basic-100">
                                  <thead>
                                    <tr>
                                      <th>#ID</th>
                                      <th>Tanggal</th>
                                      <th>Memo ID</th>
                                      <th>Nama Memo</th>
                                      <th>Keterangan</th>
                                      <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $query = "SELECT *
                                                          FROM othexpenseheader
                                                          ORDER BY 1 DESC";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_array($result)) {
                                      echo '
                                                            <tr>
                                                              <td>' . $row["OthExpenseID"] . '</td>
                                                              <td>' . $row["JournalDate"] . '</td>
                                                              <td>' . $row["MemoID"] . '</td>
                                                              <td>' . $row["MemoDesc"] . '</td>
                                                              <td>' . $row["Description"] . '</td>
                                                              <td>
                                                                <button onclick="view(this)" type="button" class="light-card border-primary border b-r-10" value="' . $row["OthExpenseID"] . '"><i class="fa fa-eye txt-primary"></i></button>
                                                              </td>
                                                            </tr>
                                                         ';
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