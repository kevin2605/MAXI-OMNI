<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT stax FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['stax'], 'C') !== false || // Create
    strpos($row['stax'], 'R') !== false || // Read
    strpos($row['stax'], 'U') !== false || // Update
    strpos($row['stax'], 'D') !== false;  // Delete
  
  $accessDenied = !$hasCRUDAccess;
  ?>

  <!-- AJAX SCRIPT and DYNAMIC TABLE -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    var i = 0;

    var find = 0;
    var serial = 0;

    var prefix = 0;
    var endnumber = 0;
    var lastflgnumber = 0;

    function getTaxSN(id) {
      //get price
      var sid = id.value;
      console.log(sid);
      $.ajax({
        type: "POST",
        url: "../Process/getTaxSN.php",
        data: "sid=" + sid,
        success: function (result) {
          var res = JSON.parse(result);
          $.each(res, function (index, value) {
            document.getElementById("startdate").innerHTML = value.StartDate;
            document.getElementById("enddate").innerHTML = value.EndDate;
            document.getElementById("prefix").innerHTML = value.Prefix;
            prefix = value.Prefix;
            document.getElementById("startnum").innerHTML = value.StartNumber;
            document.getElementById("endnum").innerHTML = value.EndNumber;
            endnumber = value.EndNumber;
            document.getElementById("lastnumflag").innerHTML = value.LastNumberFlag;
            lastflgnumber = (value.LastNumberFlag == "0") ? parseInt(value.StartNumber) - parseInt(1) : value.LastNumberFlag;
            document.getElementById("usednumber").innerHTML = value.UsedNumber;
            document.getElementById("totalnum").innerHTML = value.TotalNumber;
          });
        }
      });
      serial = 1;
      $("#taxserialid").val(id.value);//input serial id
    }

    $("document").ready(function () {
      $(document).on('click', '.bremove', function () {
        i--;
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
      });
    });

    function findInvoice() {
      //get free invoice (tidak ada nomor faktur pajak)
      var sd = document.getElementById("startdateinv").value;
      var ed = document.getElementById("enddateinv").value;
      console.log(sd + ed);
      if (sd == "" || ed == "") {
        Swal.fire({
          position: "center",
          icon: "error",
          title: "Tanggal tidak boleh kosong!",
          showConfirmButton: false,
          timer: 3000
        });
      } else {
        $.ajax({
          type: "POST",
          url: "../Process/getFreeInvoiceList.php",
          data: "sd=" + sd + "&ed=" + ed,
          success: function (result) {
            $("#tInv #tInvBody tr").remove();
            var res = JSON.parse(result);
            $.each(res, function (index, value) {
              i++;
              var tr = '<tr id="row' + i + '">' +
                '<td>' + value.InvoiceID +
                '<input type="hidden" name="invoiceids[]" value="' + value.InvoiceID + '" readonly>' +
                '</td>' +
                '<td>' + value.CreatedOn + '</td>' +
                '<td>' + value.CustName + '</td>' +
                '<td>' + numeral(value.TotalInvoice).format("0,0") + '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="noFaktur[]" style="border-style:none; padding-left:0;" placeholder="-" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="dateFaktur[]" style="border-style:none; padding-left:0;" placeholder="-" readonly>' +
                '</td>' +
                '<td>' +
                '<button id="' + i + '" type="button" class="btn btn-danger bremove" style="padding:5px 10px 5px 10px;">' +
                '<i class="icofont icofont-close-line-circled"></i>' +
                '</button>' +
                '</td>' +
                '</tr>'
              $('#tInv #tInvBody').append(tr);
            });
          }
        });
        find = 1;
      }
    }

    //funtion untuk sync data no faktur ke dalam invoice
    function syncdata() {
      if (find != 1 || serial != 1) {
        Swal.fire({
          position: "center",
          icon: "error",
          title: "Nomor Seri kosong atau Data Invoice kosong!",
          showConfirmButton: false,
          timer: 3000
        });
      } else {
        //set end number value
        $("#endnumber").val(endnumber);//input serial id

        // Date object
        const date = new Date();
        let currentDay = String(date.getDate()).padStart(2, '0');
        let currentMonth = String(date.getMonth() + 1).padStart(2, "0");
        let currentYear = date.getFullYear();
        // we will display the date as DD-MM-YYYY 
        let currentDate = `${currentYear}-${currentMonth}-${currentDay}`;

        //filling in the full tax number
        var tempflag = lastflgnumber;
        var tempend = endnumber;
        var table = document.getElementById('tInv');
        var input = table.getElementsByTagName('input');
        for (var i = 0; i < input.length && tempflag <= tempend; i++) {
          i++;
          tempflag = parseInt(tempflag) + parseInt(1);
          input[i].value = prefix + tempflag;
          i++;
          input[i].value = currentDate;
        }

        //disable find button, serial selection, open save button
        document.getElementById("savetax").removeAttribute("disabled");
        document.getElementById("serialid").setAttribute("disabled", "");
        document.getElementById("buttonFind").setAttribute("disabled", "");

        //disable all x button
        document.querySelectorAll(".bremove").forEach(element => element.disabled = true);
      }
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
                    <p><b> Selamat! </b>Sinkronisasi nomor Faktur dengan Invoice berhasil dilakukan. Klik halaman<b> Export </b> untuk CSV.
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                } else if ($_GET["status"] == "error") {
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Error! </b>Terjadi kesalahan saat sinkronisasi data.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                }
              }
              ?>
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>ISI NOMOR PAJAK</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Pajak</li>
                    <li class="breadcrumb-item">Isi Nomor Pajak</li>
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
                    <p><b> Selamat! </b>Sinkronisasi nomor Faktur dengan Invoice berhasil dilakukan. Klik halaman<b> Export </b> untuk CSV.
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                      } else if ($_GET["status"] == "error") {
                        echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Error! </b>Terjadi kesalahan saat sinkronisasi data.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                      }
                    }
                    ?>
                    <div class="row">
                      <div class="col-sm-6 ps-0">
                        <h3>ISI NOMOR PAJAK</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Pajak</li>
                          <li class="breadcrumb-item">Isi Nomor Pajak</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="card">
                      <div class="card-body">
                        <p>Nomor Seri Pajak</p>
                        <div class="mb-3 row">
                          <div class="col-sm-12">
                            <input class="form-control" id="serialid" name="serialid" list="snOptions"
                              placeholder="-- Pilih Nomor Seri --" onchange="getTaxSN(this)" required>
                            <datalist id="snOptions">
                              <?php
                              //set timezone
                              date_default_timezone_set("Asia/Jakarta");
                              $date = date('Y-m-d');

                              $queryc = "SELECT * FROM taxserialnumber WHERE StartDate <= '" . $date . "' AND EndDate >= '" . $date . "'";
                              $resultc = mysqli_query($conn, $queryc);
                              while ($rowc = mysqli_fetch_array($resultc)) {
                                echo '<option value="' . $rowc["SerialID"] . '">' . $rowc["Description"] . '</option>';
                              }
                              ?>
                            </datalist>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-4">
                            <label>Dari</label>
                            <p class="f-w-700" id="startdate">-</p>
                          </div>
                          <div class="col-4">
                            <label>Sampai</label>
                            <p class="f-w-700" id="enddate">-</p>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-4">
                            <label>Prefix</label>
                            <p class="f-w-700" id="prefix">-</p>
                          </div>
                          <div class="col-4">
                            <label>Nomor Awal</label>
                            <p class="f-w-700" id="startnum">-</p>
                          </div>
                          <div class="col-4">
                            <label>Nomor Akhir</label>
                            <p class="f-w-700" id="endnum">-</p>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-4">
                            <label>Terakhir Digunakan</label>
                            <p class="f-w-700" id="lastnumflag">-</p>
                          </div>
                          <div class="col-4">
                            <label>Sudah Digunakan</label>
                            <p class="f-w-700" id="usednumber">-</p>
                          </div>
                          <div class="col-4">
                            <label>Total Nomor</label>
                            <p class="f-w-700" id="totalnum">-</p>
                          </div>
                        </div>
                        <hr>
                        <p>Filter Nota</p>
                        <br>
                        <form class="row g-3" action="../Process/.php" method="POST">
                          <div class="mb-3 row">
                            <label class="col-sm-3">Tgl. Awal</label>
                            <div class="col-sm-9">
                              <input class="form-control" id="startdateinv" name="startdateinv" type="date">
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label class="col-sm-3">Tgl. Akhir</label>
                            <div class="col-sm-9">
                              <input class="form-control" id="enddateinv" name="enddateinv" type="date">
                            </div>
                          </div>
                        </form>
                        <br>
                        <button id="buttonFind" class="btn btn-primary" onclick="findInvoice()"><i
                            class="fa fa-search"></i>
                          Find</button>
                        <button class="btn btn-primary" onclick="syncdata()"><i class="fa fa-refresh"></i> Sync</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive custom-scrollbar signal-table">
                          <form action="../Process/syncTaxSerialNum.php" method="POST">
                            <!-- serial id and last num flag --->
                            <input type="hidden" id="taxserialid" name="taxserialid">
                            <input type="hidden" id="endnumber" name="endnumber">
                            <table class="table table-hover" id="tInv" style="width: 100%;">
                              <thead>
                                <tr>
                                  <th style="width: 13%;">No. Nota</th>
                                  <th style="width: 15%;">Tanggal</th>
                                  <th style="width: 20%;">Pelanggan</th>
                                  <th style="width: 12%;">Nominal</th>
                                  <th style="width: 20%;">No. Faktur</th>
                                  <th style="width: 12%;">Tgl. Faktur</th>
                                  <th style="width: 5%;">Action</th>
                                </tr>
                              </thead>
                              <tbody id="tInvBody">
                                <!-- body table diisi oleh ajax -->
                              <tbody>
                            </table>
                            <br>
                            <button class="btn btn-primary" id="savetax" disabled>Save</button>
                          </form>
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
        <script src="../../assets/js/height-equal.js"></script>
        <script src="../../assets/js/notify/bootstrap-notify.min.js"></script>
        <script src="../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
        <script src="../../assets/js/datatable/datatables/datatable.custom.js"></script>
        <script src="../../assets/js/modalpage/validation-modal.js"></script>
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="../../assets/js/script.js"></script>
        <!-- Plugin used-->
</body>

</html>