<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  include "../DBConnection.php";
  ?>

  <!-- AJAX SCRIPT and DYNAMIC TABLE -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>

  <script>
    function toInvoicing(str) {
      document.location = "prosesInvoicing.php?id=" + str.value;
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
            <?php
            if (isset($_GET["status"])) {
              if ($_GET["status"] == "approved") {
                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Sales Order ' . $_GET["id"] . ' telah disetujui.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else if ($_GET["status"] == "reject") {
                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Reject! </b>Purchase Order ' . $_GET["id"] . ' tidak disetujui.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
              }
            }
            ?>
            <div class="row">
              <div class="col-sm-6 ps-0">
                <h3>INVOICING PENERIMAAN</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Pembelian (Import)</li>
                  <li class="breadcrumb-item">Invoicing</li>
                  <li class="breadcrumb-item">Detil</li>
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
                  <div class="col-xl-4 shipping-border">
                    <div class="nav nav-pills horizontal-options shipping-options" id="cart-options-tab" role="tablist"
                      aria-orientation="vertical">
                      <a class="nav-link b-r-0 active" id="bill-wizard-tab" data-bs-toggle="pill" href="#bill-wizard"
                        role="tab" aria-controls="bill-wizard" aria-selected="true">
                        <div class="cart-options">
                          <div class="stroke-icon-wizard"><i class="fa fa-file-text"></i></div>
                          <div class="cart-options-content">
                            <h3>Main</h3>
                          </div>
                        </div>
                      </a>
                      <a class="nav-link b-r-0" id="ship-wizard-tab" data-bs-toggle="pill" href="#ship-wizard"
                        role="tab" aria-controls="ship-wizard" aria-selected="false">
                        <div class="cart-options">
                          <div class="stroke-icon-wizard"><i class="icofont icofont-shopping-cart"></i></div>
                          <div class="cart-options-content">
                            <h3>Purchase Order</h3>
                          </div>
                        </div>
                      </a>
                      <a class="nav-link b-r-0" id="ship-wizard-tab" data-bs-toggle="pill" href="#document" role="tab"
                        aria-controls="ship-wizard" aria-selected="false">
                        <div class="cart-options">
                          <div class="stroke-icon-wizard"><i class="fa fa-files-o"></i></div>
                          <div class="cart-options-content">
                            <h3>Doc</h3>
                          </div>
                        </div>
                      </a>
                    </div>
                    <div class="tab-content dark-field shipping-content" id="cart-options-tabContent">
                      <div class="tab-pane fade show active" id="bill-wizard" role="tabpanel"
                        aria-labelledby="bill-wizard-tab">
                        <h3>Informasi Penerimaan</h3>
                        <p class="f-light"></p>
                        <?php
                        $queryPO = "SELECT rh.ReceptionID, rh.CreatedOn, rh.CreatedBy, rh.CategoryCD, rh.WarehCD, rh.Termin, rh.Description,
                        ph.PurchaseOrderID, ph.CreatedOn AS tglPO, ph.ShippingAddress, ph.SupplierNum, s.SupplierName, ph.CategoryCD, c.CategoryName, rh.Status
                        FROM receptionheader rh, purchaseorderheader ph, supplier s, category c
                        WHERE rh.PurchaseOrderID=ph.PurchaseOrderID
                            AND ph.SupplierNum=s.SupplierNum
                            AND ph.CategoryCD=c.CategoryCD
                            AND rh.ReceptionID='" . $_GET["id"] . "'";
                        $resultPO = mysqli_query($conn, $queryPO);
                        $row = mysqli_fetch_assoc($resultPO);
                        ?>
                        <div class="row g-3">
                          <div class="col-sm-6">
                            <label class="form-label" for="noInvoicing">Nomor Invoicing</label>
                            <input class="form-control" id="noInvoicing" name="noInvoicing" type="text" value="-"
                              readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="invdate">Tanggal Invoicing</label>
                            <input class="form-control" id="invdate" name="invdate" type="text"
                              value="<?php echo '-'; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="rcvId">Penerimaan
                              Barang</span></label>
                            <input class="form-control" id="rcvId" name="rcvId" type="text"
                              value="<?php echo $row["ReceptionID"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="customLastname">Tanggal
                              Penerimaan</label>
                            <input class="form-control" id="customLastname" type="text"
                              value="<?php echo $row["CreatedOn"]; ?>" readonly>
                          </div>
                          <div class="col-sm-12">
                            <label class="form-label" for="gudang">Gudang</label>
                            <input class="form-control" id="gudang" name="gudang" type="text"
                              value="<?php echo $row["WarehCD"]; ?>" readonly>
                          </div>
                          <div class="col-sm-12">
                            <label class="form-label" for="termin">Termin</label>
                            <input class="form-control" id="termin" name="termin" type="text"
                              value="<?php echo $row["Termin"]; ?>" readonly>
                          </div>
                          <div class="col-sm-12">
                            <label class="form-label" for="desc">Keterangan</label>
                            <textarea class="form-control" id="desc" name="desc" rows="3"
                              readonly><?php echo $row["Description"]; ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade shipping-wizard" id="ship-wizard" role="tabpanel"
                        aria-labelledby="ship-wizard-tab">
                        <h3>Informasi Purhcase Order</h3>
                        <p class="f-light"></p>
                        <div class="row g-3">
                          <div class="col-sm-12">
                            <label class="form-label" for="custid">Purhace Order</span></label>
                            <input class="form-control" id="custid" type="text"
                              value="<?php echo $row["PurchaseOrderID"]; ?>" readonly>
                          </div>
                          <div class="col-sm-12">
                            <label class="form-label" for="custname">Tanggal</label>
                            <input class="form-control" id="custname" type="text" value="<?php echo $row["tglPO"]; ?>"
                              readonly>
                          </div>
                          <div class="col-sm-12">
                            <label class="form-label" for="custname">Supplier</label>
                            <input class="form-control" id="custname" type="text"
                              value="<?php echo $row["SupplierNum"] . " - " . $row["SupplierName"]; ?>" readonly>
                          </div>
                          <div class="col-sm-12">
                            <label class="form-label" for="custname">Kategori Pembelian</label>
                            <input class="form-control" id="custname" type="text"
                              value="<?php echo $row["CategoryCD"] . " - " . $row["CategoryName"]; ?>" readonly>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade shipping-wizard" id="document" role="tabpanel"
                        aria-labelledby="ship-wizard-tab">
                        <h3>Dokumen</h3>
                        <p class="f-light"></p>
                        <div class="row">
                          <?php
                          $receptionID = $_GET['id'];
                          $uploadDir = '../Local-purchasing/documentimage/';
                          $queryDocuments = "SELECT documentimage_1, documentimage_2 FROM receptiondetail WHERE ReceptionID = '$receptionID'";
                          $resultDocuments = mysqli_query($conn, $queryDocuments);
                          echo '<div class="col-6">';
                          echo '<div class="document-title">Gambar Surat:</div>';
                          echo '<div class="document-grid">';
                          while ($rowDocuments = mysqli_fetch_assoc($resultDocuments)) {
                            if (!empty($rowDocuments['documentimage_1'])) {
                              $imagesSurat = explode(',', $rowDocuments['documentimage_1']);
                              foreach ($imagesSurat as $imageSurat) {
                                $trimmedImage = trim($imageSurat);
                                echo '<div class="image-container">';
                                echo '<a href="' . $uploadDir . $trimmedImage . '" target="_blank">';
                                echo '<img src="' . $uploadDir . $trimmedImage . '" alt="Gambar Surat" class="img-thumbnail">';
                                echo '</a>';
                                echo '<div class="image-name">' . basename($trimmedImage) . '</div>';
                                echo '</div>';
                              }
                            }
                          }
                          echo '</div>';
                          echo '</div>';
                          mysqli_data_seek($resultDocuments, 0);
                          echo '<div class="col-6">';
                          echo '<div class="document-title">Gambar Barang:</div>';
                          echo '<div class="document-grid">';
                          $allImagesBarang = [];
                          while ($rowDocuments = mysqli_fetch_assoc($resultDocuments)) {
                            if (!empty($rowDocuments['documentimage_2'])) {
                              $imagesBarang = explode(',', $rowDocuments['documentimage_2']);
                              foreach ($imagesBarang as $imageBarang) {
                                $trimmedImage = trim($imageBarang);
                                if (!empty($trimmedImage)) {
                                  $allImagesBarang[] = $trimmedImage;
                                }
                              }
                            }
                          }
                          foreach ($allImagesBarang as $imageBarang) {
                            echo '<div class="image-container">';
                            echo '<a href="' . $uploadDir . $imageBarang . '" target="_blank">';
                            echo '<img src="' . $uploadDir . $imageBarang . '" alt="Gambar Barang" class="img-thumbnail">';
                            echo '</a>';
                            echo '<div class="image-name">' . basename($imageBarang) . '</div>';
                            echo '</div>';
                          }
                          echo '</div>';
                          echo '</div>';
                          ?>
                        </div>
                      </div>
                    </div>>
                  </div>
                  <div class="col-xl-8">
                    <form action="../Process/createRecInvoice.php" method="POST">
                      <input type="hidden" name="rcvId" value="<?php echo $_GET["id"]; ?>">
                      <input type="hidden" name="category" value="<?php echo $row["CategoryCD"]; ?>">
                      <div class="row g-3">
                        <div class="col-sm-6">
                          <label class="form-label" for="noFP">No. Faktur Pajak<span style="color:red;">*</span></label>
                          <input class="form-control" id="noFP" name="noFP" type="text"
                            placeholder="xxx.xxx-xx.xxxxxxxx" pattern="[1-9]{3}+.[1-9]{3}+-[1-9]{2}+.[1-9]{8,9}">
                        </div>
                        <div class="col-sm-6">
                          <label class="form-label" for="tglFP">Tgl Faktur Pajak<span
                              style="color:red;">*</span></label>
                          <input class="form-control" id="tglFP" name="tglFP" type="date">
                        </div>
                      </div>
                      <hr>
                      <div class="shipping-info">
                        <h5><i class="fa fa-table"></i> Detail Invoicing</h5>
                      </div>
                      <div class="overflow-auto">
                        <table class="table table-striped" style="width:100%">
                          <thead>
                            <tr>
                              <th style="width:30%">Barang</th>
                              <th style="width:10%">Jumlah</th>
                              <th style="width:5%">Satuan</th>
                              <th style="width:10%">Harga</th>
                              <th style="width:15%">DPP</th>
                              <th style="width:15%">PPN</th>
                              <th style="width:15%" class="text-end">Subtotal</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if ($row["CategoryCD"] == "BB") {
                              $total = 0;
                              $queryd = "SELECT r.ItemCD, m.MaterialName, r.Quantity_1, r.UnitCD_1, pd.Price
                            FROM receptiondetail r, material m, purchaseorderheader ph, purchaseorderdetail pd
                            WHERE ReceptionID='" . $row["ReceptionID"] . "'
                                    AND ph.PurchaseOrderID ='" . $row["PurchaseOrderID"] . "'
                                    AND ph.PurchaseOrderID=pd.PurchaseOrderID
                                    AND r.ItemCD=m.MaterialCD
                                    AND pd.ItemCD=r.ItemCD";
                              $resultd = mysqli_query($conn, $queryd);
                              while ($rowd = mysqli_fetch_array($resultd)) {
                                $dpp = $rowd["Quantity_1"] * $rowd["Price"];
                                $ppn = $dpp * 0.11;
                                $subtotal = $dpp + $ppn;
                                $total += $subtotal;
                                echo '<tr>
                                <td>' . $rowd["MaterialName"] . '<input type="hidden" class="form-control tb-label f-14" name="items[]" value="' . $rowd["ItemCD"] . '"></td>
                                <td><input type="number" class="form-control f-14" style="border-width:0" name="quantity[]" value="' . $rowd["Quantity_1"] . '" readonly></td>
                                <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="unit[]" value="' . $rowd["UnitCD_1"] . '"readonly></td>
                                <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="price[]" value="' . number_format($rowd["Price"], 0, '.', ',') . '"readonly></td>
                                <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="dpp[]" value="' . number_format($dpp, 0, '.', ',') . '"readonly></td>
                                <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="ppn[]" value="' . number_format($ppn, 0, '.', ',') . '"readonly></td>
                                <td><input type="text" class="form-control tb-label f-14 text-end" style="border-width:0" name="subtotal[]" value="' . number_format($subtotal, 0, '.', ',') . '"readonly></td>
                            </tr>';
                              }
                            } else if ($row["CategoryCD"] == "BPP") {
                              $total = 0;
                              $queryd = "SELECT r.ItemCD, s.GoodsName, s.Tax, r.Quantity_1, r.UnitCD_1, pd.Price
                                FROM receptiondetail r, supportinggoods s, purchaseorderheader ph, purchaseorderdetail pd
                                WHERE ReceptionID='" . $row["ReceptionID"] . "'
                                        AND ph.PurchaseOrderID ='" . $row["PurchaseOrderID"] . "'
                                        AND ph.PurchaseOrderID=pd.PurchaseOrderID
                                        AND r.ItemCD=s.GoodsCD
                                        AND pd.ItemCD=r.ItemCD";
                              $resultd = mysqli_query($conn, $queryd);
                              while ($rowd = mysqli_fetch_array($resultd)) {
                                $dpp = $rowd["Quantity_1"] * $rowd["Price"];
                                if ($rowd["Tax"] == 1) {
                                  $ppn = $dpp * 0.11;
                                } else if ($rowd["Tax"] == 0) {
                                  $ppn = 0;
                                }
                                $subtotal = $dpp + $ppn;
                                $total += $subtotal;
                                echo '<tr>
                                <td>' . $rowd["GoodsName"] . '<input type="hidden" class="form-control tb-label f-14" name="items[]" value="' . $rowd["ItemCD"] . '"></td>
                                <td><input type="number" class="form-control tb-label f-14" style="border-width:0" name="quantity[]" value="' . $rowd["Quantity_1"] . '"readonly></td>
                                <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="unit[]" value="' . $rowd["UnitCD_1"] . '"readonly></td>
                                <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="price[]" value="' . number_format($rowd["Price"], 0, '.', ',') . '"readonly></td>
                                <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="dpp[]" value="' . number_format($dpp, 0, '.', ',') . '"readonly></td>
                                <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="ppn[]" value="' . number_format($ppn, 0, '.', ',') . '"readonly></td>
                                <td><input type="text" class="form-control tb-label f-14 text-end" style="border-width:0" name="subtotal[]" value="' . number_format($subtotal, 0, '.', ',') . '"readonly></td>
                                </tr>';
                              }
                            } else if ($row["CategoryCD"] == "SPR") {
                              $total = 0;
                              $queryd = "SELECT r.ItemCD, s.PartName, s.Tax, r.Quantity_1, r.UnitCD_1, pd.Price
                                FROM receptiondetail r, sparepart s, purchaseorderheader ph, purchaseorderdetail pd
                                WHERE ReceptionID='" . $row["ReceptionID"] . "'
                                        AND ph.PurchaseOrderID ='" . $row["PurchaseOrderID"] . "'
                                        AND ph.PurchaseOrderID=pd.PurchaseOrderID
                                        AND r.ItemCD=s.PartCD
                                        AND pd.ItemCD=r.ItemCD";
                              $resultd = mysqli_query($conn, $queryd);
                              while ($rowd = mysqli_fetch_array($resultd)) {
                                $dpp = $rowd["Quantity_1"] * $rowd["Price"];
                                if ($rowd["Tax"] == 1) {
                                  $ppn = $dpp * 0.11;
                                } else if ($rowd["Tax"] == 0) {
                                  $ppn = 0;
                                }
                                $subtotal = $dpp + $ppn;
                                $total += $subtotal;
                                echo '<tr>
                                  <td>' . $rowd["PartName"] . '<input type="hidden" class="form-control tb-label f-14" name="items[]" value="' . $rowd["ItemCD"] . '"></td>
                                  <td><input type="number" class="form-control tb-label f-14" style="border-width:0" name="quantity[]" value="' . $rowd["Quantity_1"] . '"readonly></td>
                                  <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="unit[]" value="' . $rowd["UnitCD_1"] . '"readonly></td>
                                  <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="price[]" value="' . number_format($rowd["Price"], 0, '.', ',') . '"readonly></td>
                                  <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="dpp[]" value="' . number_format($dpp, 0, '.', ',') . '"readonly></td>
                                  <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="ppn[]" value="' . number_format($ppn, 0, '.', ',') . '"readonly></td>
                                  <td><input type="text" class="form-control tb-label f-14 text-end" style="border-width:0" name="subtotal[]" value="' . number_format($subtotal, 0, '.', ',') . '"readonly></td>
                                  </tr>';
                              }
                            }
                            ?>
                          </tbody>
                          <tfoot>
                            <tr>
                              <td colspan="6"></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td colspan="6">Total :</td>
                              <td class="text-end">
                                <?php echo number_format($total, 0, '.', ','); ?>
                              </td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      <br>
                      <div>
                        <input type="hidden" id="category" value="<?php echo $row["CategoryCD"]; ?>">

                        <input type="hidden" id="ppnValue" value="<?php echo $ppn; ?>">
                        <input type="submit" class="btn btn-primary" value="Submit">
                      </div>
                    </form>
                    <script>
                      window.onload = function () {
                        var ppn = parseFloat(document.getElementById('ppnValue').value);
                        var category = document.getElementById('category').value;

                        // Hide form if PPN is 0 and category is not 'BB'
                        if (ppn === 0 && category !== 'BB') {
                          document.getElementById('noFP').parentElement.style.display = 'none';
                          document.getElementById('tglFP').parentElement.style.display = 'none';
                        }
                      }
                    </script>

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
  <!-- Plugins JS Ends-->
  <!-- Theme js-->
  <script src="../../assets/js/script.js"></script>
  <!-- Plugin used-->
</body>
<style>
  .document-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
  }

  .document-grid img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  }

  .document-title {
    margin-bottom: 15px;
    font-weight: bold;
  }
</style>
<style>
  .preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
  }

  .preview-container img {
    max-width: 100px;
    max-height: 100px;
    object-fit: cover;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  }
</style>