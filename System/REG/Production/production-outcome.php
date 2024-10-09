<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT pHasilProduksi FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['pHasilProduksi'], 'C') !== false || // Create
    strpos($row['pHasilProduksi'], 'R') !== false || // Read
    strpos($row['pHasilProduksi'], 'U') !== false || // Update
    strpos($row['pHasilProduksi'], 'D') !== false;  // Delete
  
  $accessDenied = !$hasCRUDAccess;
  ?>

  <!-- AJAX SCRIPT and DYNAMIC TABLE -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    let estoutcome = 0;
    let exoutcome = 0;
    let loss = 0;
    $("document").ready(function () {
      $("#buttonGen").click(function () {
        //get customer
        var x = document.getElementById("spk").value;
        var spk = x.split(" | ");
        console.log(spk[0]);
        $.ajax({
          type: "POST",
          url: "../Process/getSPKDetail.php",
          data: "spk=" + spk[0],
          success: function (result) {
            var res = JSON.parse(result);
            $.each(res, function (index, value) {
              document.getElementById("bahan").value = value.MaterialCD;
              document.getElementById("nbahan").value = value.MaterialName;
              document.getElementById("produk").value = value.ProductCD;
              document.getElementById("nproduk").value = value.ProductName;
              document.getElementById("mesin").value = value.MachineCD;
              document.getElementById("nmesin").value = value.MachineName;
              estoutcome = value.EstimateOutcome;
              exoutcome = value.ExactOutcome;
              loss = value.ProdLoss;
              console.log(value.EstimateOutcome);
              console.log(value.ExactOutcome);
              console.log(value.ProdLoss);
              //eror
            });
          }
        });
      });
    });

    function validateForm() {
      let newoutcome = document.getElementById("hasil").value;
      let newloss = document.getElementById("rusak").value;
      let newtotaloutcome = parseInt(newoutcome) + parseInt(newloss) + parseInt(exoutcome) + parseInt(loss);

      if (newtotaloutcome > estoutcome) {
        Swal.fire({
          position: "center",
          icon: "error",
          title: "Hasil  melebihi Estimasi Produksi!",
          showConfirmButton: false,
          timer: 3000
        });
        return false;
      }
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
                  <p><b> Selamat! </b>Hasil Produksi berhasil disimpan ke database.</p>
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
                  <h3>HASIL PRODUKSI</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Hasil Produksi</li>
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
                  <p><b> Selamat! </b>Hasil Produksi berhasil disimpan ke database.</p>
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
                        <h3>HASIL PRODUKSI</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Hasil Produksi</li>
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
                          $query_access = "SELECT pHasilProduksi FROM useraccesslevel WHERE UserID = '$userID'";
                          $result_access = mysqli_query($conn, $query_access);

                          if ($result_access) {
                            $row_access = mysqli_fetch_assoc($result_access);
                            $access_level = $row_access['pHasilProduksi'];
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

                        // Tampilkan tombol dengan kondisi disable jika tidak ada akses
                        echo '<button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg" ' . (!$hasAccess ? 'disabled' : '') . '><i class="fa fa-plus-circle"></i> New</button>';


                        ?>

                        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                          aria-labelledby="myExtraLargeModal" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="myExtraLargeModal">Input Hasil Produksi</h4>
                                <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body dark-modal">
                                <div class="card-body custom-input">
                                  <form class="row g-3" action="../Process/createProdOutcome.php" method="POST"
                                    onsubmit="return validateForm()">
                                    <div class="col-9">
                                      <label class="col-sm-12 col-form-label" for="spk">Pilih SPK</label>
                                      <input class="form-control" id="spk" name="spk" list="spkOptions"
                                        placeholder="-- Pilih SPK --" required>
                                      <datalist id="spkOptions">
                                        <?php
                                        $queryc = "SELECT ProductionOrderID, Description FROM productionorder WHERE Status=0";
                                        $resultc = mysqli_query($conn, $queryc);
                                        while ($rowc = mysqli_fetch_array($resultc)) {
                                          echo '<option value="' . $rowc["ProductionOrderID"] . ' | ' . $rowc["Description"] . '"></option>';
                                        }
                                        ?>
                                      </datalist>
                                    </div>
                                    <div class="col-3">
                                      <label class="col-sm-12 col-form-label" for="buttonGen">Generate</label>
                                      <button class="form-control btn btn-primary" type="button"
                                        id="buttonGen">Generate</button>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="bahan">Kode Bahan</label>
                                      <input class="form-control" id="bahan" name="bahan" type="text" placeholder="-"
                                        readonly>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="nbahan">Bahan Baku</label>
                                      <input class="form-control" id="nbahan" type="text" placeholder="-" readonly>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="produk">Kode Produk</label>
                                      <input class="form-control" id="produk" name="produk" type="text" placeholder="-"
                                        readonly>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="nproduk">Produk</label>
                                      <input class="form-control" id="nproduk" type="text" placeholder="-" readonly>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="mesin">Kode Mesin</label>
                                      <input class="form-control" id="mesin" name="mesin" type="text" placeholder="-"
                                        readonly>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="nmesin">Mesin</label>
                                      <input class="form-control" id="nmesin" type="text" placeholder="-" readonly>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="workhour">Jam Kerja</label>
                                      <select class="form-select" id="workhour" name="workhour" required="">
                                        <option>5</option>
                                        <option selected>8</option>
                                        <option>12</option>
                                      </select>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="shift">Shift</label>
                                      <input class="form-control" id="shift" name="shift" list="listshift" required>
                                      <datalist id="listshift">
                                        <option value="1"></option>
                                        <option value="2"></option>
                                        <option value="3"></option>
                                      </datalist>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="hasil">Hasil Produksi</label>
                                      <input class="form-control digits" id="hasil" name="hasil" type="number"
                                        placeholder="0" required>
                                    </div>
                                    <div class="col-6">
                                      <label class="col-sm-12 col-form-label" for="rusak">Kerusakan</label>
                                      <input class="form-control digits" id="rusak" name="rusak" type="number"
                                        placeholder="0" required>
                                    </div>
                                    <div class="col-6">
                                      <input class="checkbox_animated" id="chk-Add" name="closeorder" value="1" type="checkbox"><label class="text-danger">Tutup SPK Produksi (Akhir produksi)</label>
                                    </div>
                                    <br>
                                    <hr>
                                    <div class="col-12">
                                      <input class="btn btn-primary" type="submit" value="Save">
                                      <!--<button class="btn btn-primary" type="button" onclick="validateForm()">Save</button>-->
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
                          <li class="nav-item"><a class="dropdown-item active txt-primary f-w-500 f-18" id="contact-tab"
                              data-bs-toggle="tab" href="#inputProduksi" role="tab" aria-controls="contact"
                              aria-selected="false">Hasil Produksi</a></li>
                        </ul>
                        <hr>
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="inputProduksi" role="tabpanel">
                            <h3>Hasil Produksi</h3>
                            <br>
                            <div class="table-responsive custom-scrollbar user-datatable">
                              <table class="display" id="basic-12">
                                <thead>
                                  <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">No. SPK</th>
                                    <th scope="col">Mesin</th>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Shift</th>
                                    <th scope="col">Jam Kerja</th>
                                    <th scope="col">Hasil Produksi</th>
                                    <th scope="col">Target</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $query = "SELECT pr.CreatedOn, pr.ProductionOrderID, m.MachineName, m.Speed, p.ProductName, pr.Shift, pr.WorkingHour, pr.ProdOutcome
                                                        FROM productionresulthistory pr, machine m, product p
                                                        WHERE pr.MachineCD=m.MachineCD
                                                              AND pr.ProductCD=p.ProductCD
                                                        ORDER BY 1 DESC";
                                  $result = mysqli_query($conn, $query);
                                  while ($row = mysqli_fetch_array($result)) {
                                    echo '
                                                    <tr>
                                                        <td>' . $row["CreatedOn"] . '</td>
                                                        <td>' . $row["ProductionOrderID"] . '</td>
                                                        <td>' . $row["MachineName"] . '</td>
                                                        <td>' . $row["ProductName"] . '</td>
                                                        <td>' . $row["Shift"] . '</td>
                                                        <td>' . $row["WorkingHour"] . '</td>
                                                        <td>' . number_format($row["ProdOutcome"], 0, '.', ',') . '</td>';

                                    //calculate target
                                    $target = $row["Speed"] * 60 * $row["WorkingHour"];
                                    $currTarget = ($row["ProdOutcome"] / $target) * 100;
                                    if ($currTarget >= 85) {
                                      echo '<td><span class="badge badge-light-success">Yes</span></td>';
                                    } else {
                                      echo '<td><span class="badge badge-light-danger">No</span></td>';
                                    }
                                    echo '       
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