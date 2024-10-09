<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    session_start();
    include "../DBConnection.php";
    $userID = $_COOKIE['UserID'];

    $query = "SELECT rLaba FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $hasCRUDAccess = strpos($row['rLaba'], 'C') !== false || // Create
        strpos($row['rLaba'], 'R') !== false || // Read
        strpos($row['rLaba'], 'U') !== false || // Update
        strpos($row['rLaba'], 'D') !== false;  // Delete
    
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
        function printInv() {
            // Ambil nilai startdate dan enddate dari input form
            var startdate = document.getElementById('startdate').value;
            var enddate = document.getElementById('enddate').value;

            // Periksa jika tanggal sudah diisi
            if (startdate === '' || enddate === '') {
                alert('Silakan isi tanggal awal dan akhir.');
                return;
            }

            // Tambahkan timestamp untuk mencegah caching
            var timestamp = new Date().getTime();
            var url = "../Process/generate_laba-rugi_pdf.php?startdate=" + encodeURIComponent(startdate) + "&enddate=" + encodeURIComponent(enddate) + "&t=" + timestamp;

            // Tunda 1 detik sebelum membuka tab baru
            setTimeout(function () {
                window.open(url, '_blank');
            }, 100); // 1000 milidetik = 1 detik

            // Refresh halaman setelah 1 detik
            setTimeout(function () {
                location.reload();
            }, 2260000); // 1000 milidetik = 1 detik
        }
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
    <style>
        .box{
            margin-bottom: 20px;
            margin-left: 5px;
            padding-top: 10px;
            padding-bottom: 10px;
            width: 23%;
            height: 20vh;
        }
    </style>
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
                            <div class="row">
                                <div class="col-sm-6 ps-0">
                                    <h3>LAPORAN LABA RUGI</h3>
                                </div>
                                <div class="col-sm-6 pe-0">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">
                                                <svg class="stroke-icon">
                                                    <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                                </svg></a></li>
                                        <li class="breadcrumb-item">Report</li>
                                        <li class="breadcrumb-item">Bahan Baku</li>
                                        <li class="breadcrumb-item">Keluar</li>
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
                                        <div class="row">
                                            <div class="col-sm-6 ps-0">
                                                <h3>LAPORAN LABA RUGI</h3>
                                            </div>
                                            <div class="col-sm-6 pe-0">
                                                <ol class="breadcrumb">
                                                    <li class="breadcrumb-item"><a href="index.html">
                                                            <svg class="stroke-icon">
                                                                <use
                                                                    href="../../assets/svg/icon-sprite.svg#stroke-home">
                                                                </use>
                                                            </svg></a></li>
                                                    <li class="breadcrumb-item">Report</li>
                                                    <li class="breadcrumb-item">Keuangan</li>
                                                    <li class="breadcrumb-item">Jurnal Umum</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3>CETAK LAPORAN</h3><small>Laporan : Bulanan</small>
                                            </div>
                                            <div class="card-body">
                                                <?php
                                                /*
                                                $startdate = '';
                                                $enddate = '';
                                                if (isset($_POST['btnSearch'])) {
                                                    $startdate = $_POST['startdate'];
                                                    $enddate = $_POST['enddate'];
                                                }
                                                */
                                                ?>
                                                <!--
                                                <form class="form theme-form" method="POST">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="mb-3 row">
                                                                    <label class="col-sm-2">Tanggal Awal</label>
                                                                    <div class="col-sm-10">
                                                                        <input class="form-control" id="startdate"
                                                                            name="startdate" type="date"
                                                                            value="<?php echo htmlspecialchars($startdate); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3 row">
                                                                    <label class="col-sm-2">Tanggal Akhir</label>
                                                                    <div class="col-sm-10">
                                                                        <input class="form-control" id="enddate"
                                                                            name="enddate" type="date"
                                                                            value="<?php echo htmlspecialchars($enddate); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary" type="button" onclick="printInv()">
                                                        <i class="fa fa-file-pdf"></i> PDF
                                                    </button>
                                                </form>
                                                -->
                                                <ul class="square-timeline">
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Januar1 <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-01-01 sampai <?php echo date("Y");?>-01-31</div>
                                                            <?php
                                                                $currM = date("m");
                                                                $startdate = date("Y-01-01");
                                                                $enddate = date("Y-01-31");
                                                                if(1 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Februari <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-02-01 sampai <?php echo date("Y");?>-02-29</div>
                                                            <?php
                                                                $startdate = date("Y-02-01");
                                                                $enddate = date("Y-02-29");
                                                                if(2 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Maret <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-03-01 sampai <?php echo date("Y");?>-03-31</div>
                                                            <?php
                                                                $startdate = date("Y-03-01");
                                                                $enddate = date("Y-03-31");
                                                                if(3 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">April <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-04-01 sampai <?php echo date("Y");?>-04-30</div>
                                                            <?php
                                                                $startdate = date("Y-04-01");
                                                                $enddate = date("Y-04-30");
                                                                if(4 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Mei <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-05-01 sampai <?php echo date("Y");?>-05-31</div>
                                                            <?php
                                                                $startdate = date("Y-05-01");
                                                                $enddate = date("Y-05-31");
                                                                if(5 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Juni <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-06-01 sampai <?php echo date("Y");?>-06-30</div>
                                                            <?php
                                                                $startdate = date("Y-06-01");
                                                                $enddate = date("Y-06-30");
                                                                if(6 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Juli <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-07-01 sampai <?php echo date("Y");?>-07-31</div>
                                                            <?php
                                                                $startdate = date("Y-07-01");
                                                                $enddate = date("Y-07-31");
                                                                if(7 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Agustus <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-08-01 sampai <?php echo date("Y");?>-08-31</div>
                                                            <?php
                                                                $startdate = date("Y-08-01");
                                                                $enddate = date("Y-08-31");
                                                                if(8 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">September <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-09-01 sampai <?php echo date("Y");?>-09-30</div>
                                                            <?php
                                                                $startdate = date("Y-09-01");
                                                                $enddate = date("Y-09-30");
                                                                if(9 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Oktober <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-10-01 sampai <?php echo date("Y");?>-10-31</div>
                                                            <?php
                                                                $startdate = date("Y-10-01");
                                                                $enddate = date("Y-10-31");
                                                                if(10 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">November <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-11-01 sampai <?php echo date("Y");?>-11-30</div>
                                                            <?php
                                                                $startdate = date("Y-11-01");
                                                                $enddate = date("Y-11-30");
                                                                if(11 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <br>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Desember <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-12-01 sampai <?php echo date("Y");?>-12-31</div>
                                                            <?php
                                                                $startdate = date("Y-12-01");
                                                                $enddate = date("Y-12-31");
                                                                if(12 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <li class="timeline-event">
                                                        <label class="timeline-event-icon"></label>
                                                        <div class="timeline-event-wrapper">
                                                            <p class="timeline-thumbnail">Januari - Desember <?php echo date("Y");?></p>
                                                            <h5 class="f-w-500">Periode</h5>
                                                            <div class="text-muted"><?php echo date("Y");?>-01-01 sampai <?php echo date("Y");?>-12-31</div>
                                                            <?php
                                                                $startdate = date("Y-01-01");
                                                                $enddate = date("Y-12-31");
                                                                if(12 < $currM){
                                                                    echo '<a target="_blank" href="../Process/generate_laba-rugi_pdf.php?startdate='.$startdate.'&enddate='.$enddate.'">
                                                                            <i class="fa fa-cloud-download"></i> Download
                                                                        </a>';
                                                                }
                                                            ?>
                                                        </div>
                                                    </li>
                                                </ul>
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
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

                <!-- Plugins JS Ends-->
                <!-- Theme js-->
                <script src="../../assets/js/script.js"></script>
                <!-- Plugin used-->
</body>

</html>