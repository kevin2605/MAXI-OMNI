<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT rMMasuk FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['rMMasuk'], 'C') !== false || // Create
    strpos($row['rMMasuk'], 'R') !== false || // Read
    strpos($row['rMMasuk'], 'U') !== false || // Update
    strpos($row['rMMasuk'], 'D') !== false;  // Delete
  
  $accessDenied = !$hasCRUDAccess;
  ?>

  <!-- AJAX SCRIPT and DYNAMIC TABLE -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- use xlsx.mini.min.js from version 0.20.3 -->
  <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.mini.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    function filterFunction() {
      var input, filter, ul, li, a, i;
      input = document.getElementById("materialname");
      filter = input.value.toLowerCase();
      ul = document.getElementById("materialDropdown");
      li = ul.getElementsByTagName("li");
      ul.style.display = "block";
      for (i = 0; i < li.length; i++) {
        a = li[i].innerText;
        if (a.toLowerCase().indexOf(filter) > -1) {
          li[i].style.display = "";
        } else {
          li[i].style.display = "none";
        }
      }
    }

    $(document).ready(function () {
      $('#materialDropdown').on('click', 'li', function () {
        $('#materialname').val($(this).text());
        $('#materialDropdown').hide();
      });

      $(document).click(function (event) {
        if (!$(event.target).closest('#materialname, #materialDropdown').length) {
          $('#materialDropdown').hide();
        }
      });
    });
  </script>

  <script>
    /*
      function submitFilter(){
          var customer = document.getElementById("customer").value;
          var startdate = document.getElementById("startdate").value;
          var enddate = document.getElementById("enddate").value;
          var startdatefaktur = document.getElementById("startdatefaktur").value;
          var enddatefaktur = document.getElementById("enddatefaktur").value;

          $.ajax({
              type: "POST",
              url: "../Process/reportInvoice.php", 
              data: "customer="+customer+"&startdate="+startdate+"&enddate="+enddate+"&startdatefaktur="+startdatefaktur+"&enddatefaktur="+enddatefaktur,
              success: function(result){
                  $("#export-button tbody tr").remove(); 
                  var res = JSON.parse(result);
                  console.log(res.length);
                  $.each(res, function(index, value) {
                      let dpp = value.TotalInvoice/1.11;
                      let ppn = value.TotalInvoice - dpp;
                      $('#export-button tbody').append("<tr><td>"+ value.InvoiceID +"</td><td>"+ value.CreatedOn.substring(0,10) +"</td><td>"+ value.CustName +"</td><td>"+ value.NPWPNum +"</td><td>"+ value.TaxInvoiceNumber +"</td><td>"+ value.TaxInvoiceDate +"</td><td>"+ numeral(value.TotalInvoice).format("0,0.00") +"</td><td> 0 </td><td>"+ numeral(value.TotalInvoice).format("0,0.00") +"</td><td>"+ numeral(dpp).format("0,0.00") +"</td><td>"+ numeral(ppn).format("0,0.00") +"</td><td>"+ numeral(value.TotalInvoice).format("0,0.00") +"</td></tr>");
                  });
                  if(res.length < 1){
                      Swal.fire({
                          position: "center",
                          icon: "error",
                          title: "Pencarian tidak ditemukan!",
                          showConfirmButton: false,
                          timer:2000
                      });
                  }
              }
          });
      }*/
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
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->

        <?php include "../sidemenu.php"; ?>

        <!-- Page Sidebar Ends-->
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>LAPORAN BAHAN BAKU MASUK</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Report</li>
                    <li class="breadcrumb-item">Bahan Baku</li>
                    <li class="breadcrumb-item">Masuk</li>
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
            <div class="page-body-wrapper">
              <!-- Page Sidebar Start-->

              <?php include "../sidemenu.php"; ?>

              <!-- Page Sidebar Ends-->
              <div class="page-body">
                <div class="container-fluid">
                  <div class="page-title">
                    <div class="row">
                      <div class="col-sm-6 ps-0">
                        <h3>LAPORAN BAHAN BAKU MASUK</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Report</li>
                          <li class="breadcrumb-item">Bahan Baku</li>
                          <li class="breadcrumb-item">Masuk</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>FILTER</h3>
                      </div>
                      <div class="card-body">
                        <form class="form theme-form" method="POST">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="row">
                                <div class="mb-3 row">
                                  <label class="col-sm-2">Nama Bahan Baku</label>
                                  <div class="col-sm-10">
                                    <input class="form-control" id="materialname" name="materialname" type="text"
                                      autocomplete="off" oninput="filterFunction()">
                                    <ul id="materialDropdown" class="list-unstyled">
                                      <?php
                                      include "../DBConnection.php";
                                      $query = "SELECT MaterialName FROM material WHERE Status='1'";
                                      $result = mysqli_query($conn, $query);
                                      while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<li>' . htmlspecialchars($row['MaterialName']) . '</li>';
                                      }
                                      ?>
                                    </ul>
                                  </div>
                                </div>
                                <div class="mb-3 row">
                                  <label class="col-sm-2">Tanggal Awal</label>
                                  <div class="col-sm-10">
                                    <input class="form-control" id="startdate" name="startdate" type="date">
                                  </div>
                                </div>
                                <div class="mb-3 row">
                                  <label class="col-sm-2">Tanggal Akhir</label>
                                  <div class="col-sm-10">
                                    <input class="form-control" id="enddate" name="enddate" type="date">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--<button class="btn btn-primary" type="button" onclick="submitFilter()"><i class="fa fa-search"></i> Search</button>-->
                          <button class="btn btn-primary" name="btnSearch"><i class="fa fa-search"></i> Search</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>REPORT</h3>
                      </div>
                      <div class="card-body">
                        <div class="dt-ext table-responsive custom-scrollbar">
                          <table class="display" id="export-button">
                            <thead>
                              <tr>
                                <th>KODE</th>
                                <th>NAMA</th>
                                <th>TANGGAL</th>
                                <th>MASUK</th>
                                <th>NOMOR</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $startDate = isset($_POST['startdate']) ? $_POST['startdate'] : '';
                              $endDate = isset($_POST['enddate']) ? $_POST['enddate'] : '';
                              $materialName = isset($_POST['materialname']) ? $_POST['materialname'] : '';

                              $queryReception = "SELECT r.ReceptionID, r.CreatedOn, r.ItemCD, m.MaterialName, r.Quantity_1, r.Quantity_2 
                              FROM receptiondetail r
                              JOIN material m ON r.ItemCD = m.MaterialCD
                              WHERE 1=1";

                              if ($startDate != '') {
                                $queryReception .= " AND substr(r.CreatedOn,1,10) >= '" . mysqli_real_escape_string($conn, $startDate) . "'";
                              }

                              if ($endDate != '') {
                                $queryReception .= " AND substr(r.CreatedOn,1,10) <= '" . mysqli_real_escape_string($conn, $endDate) . "'";
                              }

                              if ($materialName != '') {
                                $materialName = mysqli_real_escape_string($conn, $materialName);
                                $queryReception .= " AND m.MaterialName LIKE '%" . $materialName . "%'";
                              }

                              $resultReception = mysqli_query($conn, $queryReception);

                              // Periksa apakah query berhasil dijalankan
                              if (!$resultReception) {
                                die("Query Error: " . mysqli_error($conn) . " - Query: " . $queryReception);
                              }

                              while ($rowReception = mysqli_fetch_array($resultReception)) {
                                echo '<tr>
                                  <td>' . $rowReception["ItemCD"] . '</td>
                                  <td>' . $rowReception["MaterialName"] . '</td>
                                  <td>' . substr($rowReception["CreatedOn"], 0, 10) . '</td>
                                  <td>' . $rowReception["Quantity_1"] . ' Kg | ' . $rowReception["Quantity_2"] . ' Pallet</td>
                                  <td>' . $rowReception["ReceptionID"] . '</td>
                                </tr>';
                              }
                              ?>

                              <!-- APPEND BY AJAX -->
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Container-fluid Ends-->
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

        <style>
          #materialDropdown {
            position: absolute;
            z-index: 1000;
            background-color: #fff;
            border: 1px solid #ddd;
            max-height: 200px;
            overflow-y: auto;
            display: none;

          }

          #materialDropdown li {
            padding: 10px;
            cursor: pointer;
          }

          #materialDropdown li:hover {
            background-color: #f1f1f1;
          }
        </style>
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
        <script src="../../assets/js/notify/bootstrap-notify.min.js"></script>
        <script src="../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
        <script src="../../assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
        <script src="../../assets/js/datatable/datatable-extension/jszip.min.js"></script>
        <script src="../../assets/js/datatable/datatable-extension/buttons.colVis.min.js"></script>
        <script src="../../assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
        <script src="../../assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
        <script src="../../assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
        <script src="../../assets/js/datatable/datatable-extension/custom.js"></script>
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="../../assets/js/script.js"></script>
        <!-- Plugin used-->
</body>

</html>