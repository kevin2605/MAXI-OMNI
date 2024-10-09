<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    session_start();
    include "../DBConnection.php";
    $userID = $_COOKIE['UserID'];

    $query = "SELECT rGeneral FROM useraccesslevel WHERE UserID = '$userID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $hasCRUDAccess = strpos($row['rGeneral'], 'C') !== false || // Create
        strpos($row['rGeneral'], 'R') !== false || // Read
        strpos($row['rGeneral'], 'U') !== false || // Update
        strpos($row['rGeneral'], 'D') !== false;  // Delete
    
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
            var url = "../Process/generate_jurnal_u_pdf.php?startdate=" + encodeURIComponent(startdate) + "&enddate=" + encodeURIComponent(enddate) + "&t=" + timestamp;

            // Tunda 1 detik sebelum membuka tab baru
            setTimeout(function () {
                window.open(url, '_blank');
            }, 1000); // 1000 milidetik = 1 detik

            // Refresh halaman setelah 1 detik
            setTimeout(function () {
                location.reload();
            }, 2000); // 1000 milidetik = 1 detik
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
                                    <h3>LAPORAN BAHAN BAKU KELUAR</h3>
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
                                                <h3>LAPORAN JURNAL UMUM</h3>
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
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3>FILTER</h3>
                                            </div>
                                            <div class="card-body">
                                                <?php
                                                $startdate = '';
                                                $enddate = '';
                                                if (isset($_POST['btnSearch'])) {
                                                    $startdate = $_POST['startdate'];
                                                    $enddate = $_POST['enddate'];
                                                }
                                                ?>
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
                                                    <button class="btn btn-primary" type="submit" name="btnSearch">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
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
                                                    <button class="btn btn-primary" type="button" onclick="printInv()">
                                                        <i class="fa fa-file-pdf"></i> PDF
                                                    </button>
                                                    <table class="display" id="export-button">
                                                        <thead>
                                                            <tr class="table-header">
                                                                <th>#ID</th>
                                                                <th>Tanggal</th>
                                                                <th>Memo ID</th>
                                                                <th>Nama Memo</th>
                                                                <th>Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if (isset($_POST['btnSearch'])) {
                                                                $startdate = $_POST['startdate'];
                                                                $enddate = $_POST['enddate'];
                                                                // Simpan tanggal ke dalam sesi
                                                                $_SESSION['startdate'] = $startdate;
                                                                $_SESSION['enddate'] = $enddate;
                                                                $query = "SELECT * FROM genjournalheader";
                                                                if (!empty($startdate) && !empty($enddate)) {
                                                                    $query .= " WHERE JournalDate BETWEEN '$startdate' AND '$enddate'";
                                                                }
                                                                $query .= " ORDER BY 1 DESC";
                                                            } else {
                                                                $query = "SELECT * FROM genjournalheader ORDER BY 1 DESC";
                                                            }
                                                            $result = mysqli_query($conn, $query);
                                                            if ($result === false) {
                                                                echo "Error: " . mysqli_error($conn);
                                                                exit;
                                                            }
                                                            while ($row = mysqli_fetch_array($result)) {
                                                                $genJourID = $row["GenJourID"];
                                                                echo '
                                                                <tr class="header-row table-row" data-genjourid="' . $genJourID . '">
                                                                    <td>' . $row["GenJourID"] . '</td>
                                                                    <td>' . $row["JournalDate"] . '</td>
                                                                    <td>' . $row["MemoID"] . '</td>
                                                                    <td>' . $row["MemoDesc"] . '</td>
                                                                    <td>' . $row["Description"] . '</td>
                                                                </tr>';
                                                                $queryDetail = "SELECT * FROM genjournaldetail WHERE GenJourID = '$genJourID'";
                                                                $resultDetail = mysqli_query($conn, $queryDetail);
                                                                if ($resultDetail === false) {
                                                                    echo "Error: " . mysqli_error($conn);
                                                                    continue;
                                                                }
                                                                if (mysqli_num_rows($resultDetail) > 0) {
                                                                    echo '
                                                                    <tr class="detail-header table-row" style="display:none;">
                                                                        <td colspan="5">
                                                                            <table class="inner-table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Kode Akun</th>
                                                                                        <th>Nama Akun</th>
                                                                                        <th>Debit</th>
                                                                                        <th>Credit</th>                            
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>';
                                                                    while ($detail = mysqli_fetch_array($resultDetail)) {
                                                                        echo '
                                                                        <tr class="detail-row table-row">
                                                                            <td>' . $detail["AccountCD"] . '</td>
                                                                            <td>' . $detail["AccountName"] . '</td>
                                                                            <td>' . number_format($detail["Debit"], 2) . '</td>
                                                                            <td>' . number_format($detail["Credit"], 2) . '</td>
                                                                        </tr>';
                                                                    }
                                                                    echo '
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>';
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <!-- jQuery untuk toggle -->
                                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                    <script>
                                                        $(document).ready(function () {
                                                            $(".header-row").click(function () {
                                                                $(this).nextUntil(".header-row").toggle();
                                                            });
                                                        });
                                                    </script>
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
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

                <!-- Plugins JS Ends-->
                <!-- Theme js-->
                <script src="../../assets/js/script.js"></script>
                <!-- Plugin used-->
</body>

</html>