<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  include "../DBConnection.php";
  ?>

  <script>
    function editInv(str) {
      //document.location = "editSalesOrder.php?id=" + str.value;
    }
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
                <h3>INVOICE</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Invoice</li>
                  <li class="breadcrumb-item">Detail</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="card">
          <div class="card-header pb-0">
          </div>
          <div class="card-body">
            <div class="row shopping-wizard">
              <div class="col-12">
                <div class="row shipping-form g-5">
                  <div class="col-xl-6 shipping-border">
                    <div class="nav nav-pills horizontal-options shipping-options" id="cart-options-tab" role="tablist"
                      aria-orientation="vertical"><a class="nav-link b-r-0 active" id="bill-wizard-tab"
                        data-bs-toggle="pill" href="#bill-wizard" role="tab" aria-controls="bill-wizard"
                        aria-selected="true">
                        <div class="cart-options">
                          <div class="stroke-icon-wizard"><i class="fa fa-file-text"></i></div>
                          <div class="cart-options-content">
                            <h3>Main</h3>
                          </div>
                        </div>
                      </a><a class="nav-link b-r-0" id="ship-wizard-tab" data-bs-toggle="pill" href="#ship-wizard"
                        role="tab" aria-controls="ship-wizard" aria-selected="false">
                        <div class="cart-options">
                          <div class="stroke-icon-wizard"><i class="fa fa-user"></i></div>
                          <div class="cart-options-content">
                            <h3>Pelanggan</h3>
                          </div>
                        </div>
                      </a>
                    </div>
                    <div class="tab-content dark-field shipping-content" id="cart-options-tabContent">
                      <div class="tab-pane fade show active" id="bill-wizard" role="tabpanel"
                        aria-labelledby="bill-wizard-tab">
                        <h3>Informasi Invoice</h3>
                        <p class="f-light"></p>
                        <?php
                        $invoiceID = isset($_GET["id"]) ? $_GET["id"] : '';
                        if (empty($invoiceID)) {
                          die("Error: ID tidak ditemukan.");
                        }
                        $querySO = "
                                  SELECT 
                                      inv.InvoiceID, inv.SalesOrderID, inv.CreatedOn, inv.TaxInvoiceNumber, inv.TaxInvoiceDate,
                                      c.CustID, c.CustName, c.ShipmentAddress, c.PhoneNumOne, c.NIK, c.NPWPNum, 
                                      c.Email, ca.AccountCD, p.PaymentName, w.WarehName, 
                                      inv.Description, inv.PaidDate, inv.InvoiceStatus
                                  FROM 
                                      invoiceheader inv
                                  JOIN 
                                      customer c ON CONVERT(inv.CustID USING utf8mb4) = CONVERT(c.CustID USING utf8mb4)
                                  JOIN 
                                      chartofaccount ca ON CONVERT(inv.AccountCD USING utf8mb4) = CONVERT(ca.AccountCD USING utf8mb4)
                                  JOIN 
                                      payment p ON CONVERT(inv.PaymentCD USING utf8mb4) = CONVERT(p.PaymentCD USING utf8mb4)
                                  JOIN 
                                      warehouse w ON CONVERT(inv.WarehCD USING utf8mb4) = CONVERT(w.WarehCD USING utf8mb4)
                                  WHERE 
                                      CONVERT(inv.InvoiceID USING utf8mb4) = CONVERT(? USING utf8mb4)
                              ";

                        if ($stmt = $conn->prepare($querySO)) {
                          $stmt->bind_param("s", $invoiceID);
                          $stmt->execute();
                          $resultSO = $stmt->get_result();

                          if ($resultSO) {
                            $row = mysqli_fetch_assoc($resultSO);

                            if ($row) {
                              // echo "<h3>Informasi Invoice</h3>";
                              // echo "<p class='f-light'>Invoice ID: " . htmlspecialchars($row['InvoiceID']) . "</p>";
                              // echo "<p class='f-light'>Sales Order ID: " . htmlspecialchars($row['SalesOrderID']) . "</p>";
                              // echo "<p class='f-light'>Created On: " . htmlspecialchars($row['CreatedOn']) . "</p>";
                              // echo "<p class='f-light'>Customer Name: " . htmlspecialchars($row['CustName']) . "</p>";
                        
                            } else {
                              echo "Data tidak ditemukan.";
                            }
                          } else {
                            echo "Error: " . $stmt->error;
                          }

                          $stmt->close();
                        } else {
                          echo "Error: " . $conn->error;
                        }
                        ?>

                        <form class="row g-3">
                          <div class="col-sm-4">
                            <label class="form-label" for="salesoid">Sales Order</label>
                            <input class="form-control" id="salesoid" type="text"
                              value="<?php echo $row["SalesOrderID"]; ?>" readonly>
                          </div>
                          <div class="col-sm-4">
                            <label class="form-label" for="invoiceid">Invoice</span></label>
                            <input class="form-control" id="invoiceid" type="text"
                              value="<?php echo $row["InvoiceID"]; ?>" readonly>
                          </div>
                          <div class="col-sm-4">
                            <label class="form-label" for="tanggal">Tanggal Invoice</label>
                            <input class="form-control" id="tanggal" type="text"
                              value="<?php echo $row["CreatedOn"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="nomorfp">Faktur Pajak</label>
                            <input class="form-control" id="nomorfp" name="nomorfp" type="text"
                              value="<?php echo ($row["TaxInvoiceNumber"] != NULL) ? $row["TaxInvoiceNumber"] : "-"; ?>"
                              readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="tanggalfp">Tanggal Faktur Pajak</label>
                            <input class="form-control" id="tanggalfp" name="tanggalfp" type="text"
                              value="<?php echo ($row["TaxInvoiceDate"] != NULL) ? $row["TaxInvoiceDate"] : "-"; ?>"
                              readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="jurnal">Kode Akun</label>
                            <input class="form-control" id="jurnal" type="text"
                              value="<?php echo $row["AccountCD"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="pembayaran">Pembayaran</label>
                            <input class="form-control" id="pembayaran" type="text"
                              value="<?php echo $row["PaymentName"]; ?>" readonly>
                          </div>
                          <div class="col-sm-12">
                            <label class="form-label" for="customContact">Keterangan</label>
                            <textarea class="form-control" id="customContact" rows="3"
                              readonly><?php echo $row["Description"]; ?></textarea>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="pembayaran">Status Pembayaran</label>
                            <input class="form-control" id="pembayaran" type="text" value="<?php if ($row["PaidDate"] == null) {
                              echo "Belum Lunas";
                            } else {
                              echo $row["PaidDate"];
                            } ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="pembayaran">Status Invoice</label>
                            <input class="form-control" id="pembayaran" type="text" value="<?php if ($row["InvoiceStatus"] == 0) {
                              echo "Pending";
                            } else {
                              echo "Complete";
                            } ?>" readonly>
                          </div>
                        </form>
                      </div>
                      <div class="tab-pane fade shipping-wizard" id="ship-wizard" role="tabpanel"
                        aria-labelledby="ship-wizard-tab">
                        <h3>Informasi Pelanggan</h3>
                        <p class="f-light"></p>
                        <form class="row g-3">
                          <div class="col-sm-4">
                            <label class="form-label" for="custid">ID Pelanggan</span></label>
                            <input class="form-control" id="custid" type="text" value="<?php echo $row["CustID"]; ?>"
                              readonly>
                          </div>
                          <div class="col-sm-8">
                            <label class="form-label" for="custname">Nama</label>
                            <input class="form-control" id="custname" type="text"
                              value="<?php echo $row["CustName"]; ?>" readonly>
                          </div>
                          <div class="col-sm-12">
                            <label class="form-label" for="shipment">Alamat Pengiriman</label>
                            <textarea class="form-control" id="shipment" rows="3"
                              readonly><?php echo $row["ShipmentAddress"]; ?></textarea>
                          </div>
                          <div class="col-sm-4">
                            <label class="form-label" for="npwp">No. NPWP/NIK</label>
                            <input class="form-control" id="npwp" type="text" value="<?php if ($row["NPWPNum"] != null || $row["NPWPNum"] != "-") {
                              echo $row["NPWPNum"];
                            } else if ($row["NIK"] != null || $row["NIK"] != "-") {
                              echo $row["NIK"];
                            } ?>" readonly>
                          </div>
                          <div class="col-sm-4">
                            <label class="form-label" for="nohp">No. HP</label>
                            <input class="form-control" id="nohp" type="text" value="<?php echo $row["PhoneNumOne"]; ?>"
                              readonly>
                          </div>
                          <div class="col-sm-4">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" id="email" type="text" value="<?php echo $row["Email"]; ?>"
                              readonly>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="shipping-info">
                      <h5><i class="fa fa-table"></i> Detail Order</h5>
                    </div>
                    <div class="overflow-auto">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th scope="col">Produk</th>
                            <th scope="col">Harga PL</th>
                            <th scope="col">Discount</th>
                            <th scope="col">Harga Nett</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col" class="text-end">Subtotal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $subtotal = 0;
                          $diskon = 0;
                          $totalexec = 0;
                          $queryd = "SELECT p.ProductName, ivd.Quantity, ivd.Price, ivd.Discount, ivd.Subtotal 
                          FROM invoicedetail ivd 
                          JOIN product p ON ivd.ProductCD=p.ProductCD 
                          WHERE ivd.InvoiceID='" . $row["InvoiceID"] . "'";
                          $resultd = mysqli_query($conn, $queryd);
                          while ($rowd = mysqli_fetch_array($resultd)) {
                            $subtotal += $rowd["Subtotal"];
                            $totalexec += $rowd["Price"] * $rowd["Quantity"];
                            echo '<tr>';
                            echo '<td>' . $rowd["ProductName"] . '</td>';
                            echo '<td>' . $rowd["Price"] . '</td>';
                            echo '<td>' . $rowd["Discount"] . '</td>';
                            echo '<td>' . ($rowd["Price"] - $rowd["Discount"]) . '</td>';
                            echo '<td>' . number_format($rowd["Quantity"], 0, '.', ',') . '</td>';
                            echo '<td class="text-end">' . number_format($rowd["Subtotal"], 0, '.', ',') . '</td>';
                            echo '</tr>';
                          }
                          $diskon = $totalexec - $subtotal;
                          $beforetax = $subtotal / 1.11;
                          $tax = $beforetax * 0.11;
                          $queryHeader = "SELECT DPAmount FROM invoiceheader WHERE InvoiceID='" . $row["InvoiceID"] . "'";
                          $resultHeader = mysqli_query($conn, $queryHeader);
                          $rowHeader = mysqli_fetch_array($resultHeader);
                          $dpAmount = $rowHeader['DPAmount'];
                          ?>
                        <tfoot>
                          <tr>
                            <td colspan="5">Total :</td>
                            <td class="text-end"><?php echo number_format($subtotal, 0, '.', ',') ?></td>
                          </tr>
                          <tr>
                            <td colspan="5"></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan="5">Diskon :</td>
                            <td class="text-end"><?php echo number_format($diskon, 0, '.', ',') ?></td>
                          </tr>
                          <tr>
                            <td colspan="5">DPP :</td>
                            <td class="text-end"><?php echo number_format($beforetax, 2, '.', ',') ?></td>
                          </tr>
                          <tr>
                            <td colspan="5">PPN :</td>
                            <td class="text-end"><?php echo number_format($tax, 2, '.', ',') ?></td>
                          </tr>
                          <tr>
                            <td colspan="5">DP :</td>
                            <td class="text-end"><?php echo number_format($dpAmount, 2, '.', ',') ?></td>
                          </tr>
                          <tr>
                            <td colspan="5">Total (NET) :</td>
                            <td class="text-end">
                              Rp.<?php echo number_format($subtotal - $dpAmount, 2, '.', ',') ?></td>
                          </tr>
                        </tfoot>
                        </tbody>

                      </table>
                    </div>
                    <br>
                    <br>
                    <div>
                      <a class="btn btn-warning" href="invoice.php">Back</a>
                      <a class="btn btn-info"
                        href="../Process/generate_invoice_pdf.php?InvoiceID=<?php echo $row['InvoiceID']; ?>"
                        target="_blank" onclick="printAndRefresh(event, this.href);">Print</a>
                      <script>
                        function printAndRefresh(event, url) {
                          event.preventDefault();
                          window.open(url, '_blank');
                          setTimeout(function () {
                            window.location.reload();
                          }, 1000);
                        }
                      </script>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Container-fluid Ends-->
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
  <script src="../../assets/js/form-wizard/form-wizard.js"></script>
  <script src="../../assets/js/form-wizard/image-upload.js"></script>
  <!-- Plugins JS Ends-->
  <!-- Theme js-->
  <script src="../../assets/js/script.js"></script>
  <!-- Plugin used-->
</body>

</html>