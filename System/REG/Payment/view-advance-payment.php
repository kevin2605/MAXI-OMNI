<!DOCTYPE html>
<html lang="en">
  <head>
    <?php 
      include "../headcontent.php"; 
      include "../DBConnection.php";
    ?>
  </head>
    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function getInvAmount(){
            //get invoice
            var invoice = document.getElementById("invoice").value;
            var invoiceid = invoice.split(" ");
            console.log(invoiceid[0]);
            $.ajax({
                type: "POST",
                url: "../Process/getInvAmount.php",
                data: "invid=" + invoiceid[0],
                success: function (result) {
                    var res = JSON.parse(result);
                    $.each(res, function (index, value) {
                        document.getElementById("nominal").value = value.TotalInvoice;
                    });
                }
            });
        }
    </script>
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
                  <h3>PEMBAYARAN DI AWAL</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item">Pembayaran di Awal</li>
                    <li class="breadcrumb-item">Detail</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <!-- QUERY TABLE advancepayment -->
                        <?php
                            $query = "SELECT a.AdvPaymentID, a.CreatedOn, c.CustID, c.CustName, a.Amount, a.AccountCD, a.PaymentBy, a.Description, a.TotalUsage, a.Status
                                       FROM advancepayment a, customer c
                                       WHERE a.CustID = c.CustID
                                             AND a.Status=0
                                             AND a.AdvPaymentID='".$_GET["advid"]."'";
                            $result = mysqli_query($conn,$query);
                            $row = mysqli_fetch_assoc($result);
                        ?>
                        <div class="card-header">
                            <h4>Detil Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-sm-4">Nomor</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value="<?php echo $row["AdvPaymentID"]; ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4">Tanggal</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value="<?php echo $row["CreatedOn"]; ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4">Customer</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value="<?php echo $row["CustName"]; ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4">Nominal</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value="<?php echo number_format($row["Amount"], 0, '.', ','); ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4">Terbayar</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value="<?php echo number_format($row["TotalUsage"], 0, '.', ','); ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4">Sisa</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value="<?php echo number_format($row["Amount"]-$row["TotalUsage"], 0, '.', ','); ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4">Kode Akun</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value="<?php echo $row["AccountCD"]; ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4">Pembayaran</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value="<?php echo $row["PaymentBy"]; ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4">Description</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" readonly><?php echo $row["Description"]; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-warning" href="../Payment/advance-payment.php">Back</a>
                </div>
                
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Rincian Penggunaan Pembayaran</h4>
                        </div>
                        <div class="card-body">

                        <!-- check if status is 0 -->
                        <?php if($row["Status"] == 0){ ?>

                            <form class="row g-3" action="../Process/createAdvPaymentUsage.php" method="POST">
                                <input type="hidden" name="advpaymentid" value="<?php echo $row["AdvPaymentID"]; ?>">
                                <div class="col-3">
                                    <label class="form-label" for="invoice">Invoice</label>
                                    <input class="form-control" id="invoice" name="invoice" list="invOptions" onchange="getInvAmount(this)" placeholder="Pilih Invoice" required>
                                    <datalist id="invOptions">
                                        <?php
                                            $queryu = "SELECT *
                                                      FROM invoiceheader
                                                      WHERE InvoiceStatus = 0
                                                            AND TotalInvoice = NULL
                                                            AND PaidDate = NULL
                                                            AND CustID='".$row["CustID"]."'";
                                            $resultu = mysqli_query($conn, $queryu);
                                            while ($rowu = mysqli_fetch_array($resultu)) {
                                                echo '<option value="' . $rowu["InvoiceID"] . ' (' . $row["CustName"] . ')"></option>';
                                            }
                                        ?>
                                    </datalist>
                                </div>
                                <div class="col-3">
                                    <label class="form-label" for="nominal">Nominal</label>
                                    <input class="form-control digits" id="nominal" name="nominal" max="<?php echo $row["Amount"]-$row["TotalUsage"] ?>" type="number" placeholder="0" readonly>
                                </div>
                                <div class="col-4">
                                    <label class="form-label" for="keterangan">Deskripsi</label>
                                    <input class="form-control" id="keterangan" name="keterangan" type="text">
                                </div>
                                <div class="col-1">
                                    <label class="form-label" for="save">Save</label>
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </form>

                        <?php } ?>

                        </div>
                    </div>
                    <!-- QUERY TABLE advancepaymentusage -->
                    <?php
                        $query = "SELECT *
                                    FROM advancepaymentusage
                                    WHERE AdvPaymentID='".$_GET["advid"]."'";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_array($result)) {
                    ?>
                            <div class="card">
                                <div class="card-body">
                                    <h3 style="margin-bottom: 10px;"><i class="fa fa-file-text"></i>&nbsp;&nbsp;&nbsp;<?php echo $row["InvoiceID"] ?></h3>
                                    Tanggal : <?php echo $row["CreatedOn"] ?><br>
                                    Nominal : Rp <?php echo number_format($row["Amount"], 0, '.', ',') ?><br>
                                    Keterangan : <?php echo ($row["Description"] == null)? '-':$row["Description"]; ?>
                                </div>
                            </div>
                    <?php
                        }
                    ?>
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