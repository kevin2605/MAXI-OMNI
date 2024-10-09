<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    include "../DBConnection.php";
    ?>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- use xlsx.mini.min.js from version 0.20.3 -->
    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.mini.min.js"></script>

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
                        <div class="row">
                            <div class="col-sm-6 ps-0">
                                <h3>REPORT INVOICE</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Report</li>
                                    <li class="breadcrumb-item">Penjualan</li>
                                    <li class="breadcrumb-item">Invoice</li>
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
                                <div class="card-header">
                                    <h3>FILTER</h3>
                                </div>
                                <div class="card-body">
                                    <!-- <form class="form theme-form" method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="mb-3 row">
                                                        <label class="col-sm-2">Supplier</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control" id="supplier" name="supplier"
                                                                list="supplierOptions"
                                                                placeholder="-- Pilih Supplier --">
                                                            <datalist id="supplierOptions">
                                                                <?php
                                                                $querys = "SELECT SupplierNum, SupplierName FROM supplier";
                                                                $results = mysqli_query($conn, $querys);
                                                                while ($rows = mysqli_fetch_array($results)) {
                                                                    echo '<option value="' . $rows["SupplierNum"] . ' - ' . $rows["SupplierName"] . '"></option>';
                                                                }
                                                                ?>
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label class="col-sm-2">Tanggal Awal</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control" id="startdate" name="startdate"
                                                                type="date">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label class="col-sm-2">Tanggal Akhir</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control" id="enddate" name="enddate"
                                                                type="date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="mb-3 row">
                                                        <label class="col-sm-2">Tanggal Awal Faktur</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control" id="startdatefaktur"
                                                                name="startdatefaktur" type="date">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <label class="col-sm-2">Tanggal Akhir Faktur</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control" id="enddatefaktur"
                                                                name="enddatefaktur" type="date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" name="btnSearch"><i class="fa fa-search"></i>
                                            Search</button>
                                    </form> -->
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
                                                    <th rowspan="2">NO NOTA</th>
                                                    <th rowspan="2">NO FAKTUR PAJAK</th>
                                                    <th rowspan="2">TGL FP/NOTA</th>
                                                    <th rowspan="2">NAMA SUPLIER</th>
                                                    <th rowspan="2">NAMA BARANG</th>
                                                    <th colspan="3" class="text-center">Lain-lain</th>
                                                    <th rowspan="2">PPN MASUKAN</th>
                                                    <th rowspan="2">UTANG DAGANG</th>
                                                </tr>
                                                <tr>
                                                    <th>DPP</th>
                                                    <th>PPh 23</th>
                                                    <th>KETERANGAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>




                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->

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