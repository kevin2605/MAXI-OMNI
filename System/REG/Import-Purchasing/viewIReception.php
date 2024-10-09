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
                  <p><b> Selamat! </b>Dokumen ' . $_GET["id"] . ' telah diperbarui.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              } else if ($_GET["status"] == "reject") {
                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Reject! </b>Dokumen ' . $_GET["id"] . ' tidak diperbarui.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
              }
            }
            ?>
            <div class="row">
              <div class="col-sm-6 ps-0">
                <h3>PENERIMAAN BARANG</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Pembelian (Import)</li>
                  <li class="breadcrumb-item">Penerimaan Barang</li>
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
                  <div class="col-xl-6 shipping-border">
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
                          <div class="stroke-icon-wizard"><i class="fa fa-user"></i></div>
                          <div class="cart-options-content">
                            <h3>Purchase Order</h3>
                          </div>
                        </div>
                      </a>
                      <a class="nav-link b-r-0" id="doc-wizard-tab" data-bs-toggle="pill" href="#doc-wizard" role="tab"
                        aria-controls="doc-wizard" aria-selected="false">
                        <div class="cart-options">
                          <div class="stroke-icon-wizard"><i class="fa fa-file"></i></div>
                          <div class="cart-options-content">
                            <h3>Dokumen</h3>
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
                        $queryPO = "SELECT rh.ReceptionID, rh.CreatedOn, rh.CreatedBy, rh.CategoryCD, rh.WarehCD, rh.Termin, rh.Description, rh.Invoice, rh.BL, rh.Insurance, rh.PackingList,
                        ph.PurchaseOrderID, ph.CreatedOn AS tglPO, ph.ShippingAddress, ph.SupplierNum, s.SupplierName, ph.CategoryCD, c.CategoryName, rh.Status
                        FROM importreceptionheader rh
                        JOIN importpurchaseorderheader ph ON rh.PurchaseOrderID=ph.PurchaseOrderID
                        JOIN supplier s ON ph.SupplierNum=s.SupplierNum
                        JOIN category c ON ph.CategoryCD=c.CategoryCD
                        WHERE rh.ReceptionID='" . $_GET["id"] . "'";
                        $resultPO = mysqli_query($conn, $queryPO);
                        $row = mysqli_fetch_assoc($resultPO);
                        ?>
                        <form class="row g-3">
                          <div class="col-sm-6">
                            <label class="form-label" for="receptionID">Nomor Penerimaan</label>
                            <input class="form-control" id="receptionID" type="text"
                              value="<?php echo $row["ReceptionID"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="createdOn">Tanggal</label>
                            <input class="form-control" id="createdOn" type="text"
                              value="<?php echo $row["CreatedOn"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="createdBy">Dibuat Oleh</label>
                            <?php
                            $queryn = "SELECT * FROM systemuser WHERE UserID='" . $row["CreatedBy"] . "'";
                            $resultn = mysqli_query($conn, $queryn);
                            $rown = mysqli_fetch_assoc($resultn);
                            echo '<input class="form-control" id="createdBy" type="text" value="' . $rown["UserID"] . ' - ' . $rown["Name"] . '" readonly>';
                            ?>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="warehCD">Gudang</label>
                            <input class="form-control" id="warehCD" type="text" value="<?php echo $row["WarehCD"]; ?>"
                              readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="invoice">No. Invoice</label>
                            <input class="form-control" id="invoice" type="text" value="<?php echo $row["Invoice"]; ?>"
                              readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="packingList">No. Packing List</label>
                            <input class="form-control" id="packingList" type="text"
                              value="<?php echo $row["PackingList"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="bl">No. B/L</label>
                            <input class="form-control" id="bl" type="text" value="<?php echo $row["BL"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="insurance">No. Insurance</label>
                            <input class="form-control" id="insurance" type="text"
                              value="<?php echo $row["Insurance"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="termin">Termin</label>
                            <input class="form-control" id="termin" type="text"
                              value="<?php echo $row["Termin"]; ?> Hari" readonly>
                          </div>
                          <div class="col-sm-12">
                            <label class="form-label" for="description">Keterangan</label>
                            <textarea class="form-control" id="description" rows="3"
                              readonly><?php echo $row["Description"]; ?></textarea>
                          </div>
                        </form>
                      </div>
                      <div class="tab-pane fade" id="ship-wizard" role="tabpanel" aria-labelledby="ship-wizard-tab">
                        <h3>Informasi Purchase Order</h3>
                        <p class="f-light"></p>
                        <form class="row g-3">
                          <div class="col-sm-6">
                            <label class="form-label" for="custid">Purchase Order</label>
                            <input class="form-control" id="custid" type="text"
                              value="<?php echo $row["PurchaseOrderID"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="custname">Tanggal</label>
                            <input class="form-control" id="custname" type="text" value="<?php echo $row["tglPO"]; ?>"
                              readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="custname">Supplier</label>
                            <input class="form-control" id="custname" type="text"
                              value="<?php echo $row["SupplierNum"] . " - " . $row["SupplierName"]; ?>" readonly>
                          </div>
                          <div class="col-sm-6">
                            <label class="form-label" for="custname">Kategori Pembelian</label>
                            <input class="form-control" id="custname" type="text"
                              value="<?php echo $row["CategoryCD"] . " - " . $row["CategoryName"]; ?>" readonly>
                          </div>
                        </form>
                      </div>
                      <div class="tab-pane fade" id="doc-wizard" role="tabpanel" aria-labelledby="img-wizard-tab">
                        <h3>Dokumen</h3>
                        <div class="row">
                          <?php
                          $receptionID = $_GET['id'];
                          $uploadDir = '../Import-Purchasing/documentimageI/';
                          $queryDocuments = "SELECT documentimageI_1, documentimageI_2 FROM importreceptiondetail WHERE ReceptionID = '$receptionID' LIMIT 1";
                          $resultDocuments = mysqli_query($conn, $queryDocuments);

                          // Menggunakan array asosiatif untuk memastikan gambar unik
                          $uniqueImagesSurat = [];
                          $uniqueImagesBarang = [];

                          while ($rowDocuments = mysqli_fetch_assoc($resultDocuments)) {
                            if (!empty($rowDocuments['documentimageI_1'])) {
                              $imagesSurat = explode(',', $rowDocuments['documentimageI_1']);
                              foreach ($imagesSurat as $imageSurat) {
                                $trimmedImage = trim($imageSurat);
                                if (!empty($trimmedImage)) {
                                  $uniqueImagesSurat[$trimmedImage] = $trimmedImage;
                                }
                              }
                            }

                            if (!empty($rowDocuments['documentimageI_2'])) {
                              $imagesBarang = explode(',', $rowDocuments['documentimageI_2']);
                              foreach ($imagesBarang as $imageBarang) {
                                $trimmedImage = trim($imageBarang);
                                if (!empty($trimmedImage)) {
                                  $uniqueImagesBarang[$trimmedImage] = $trimmedImage;
                                }
                              }
                            }
                          }

                          // Menampilkan gambar surat
                          echo '<div class="col-6">';
                          echo '<div class="document-title">Gambar Surat:</div>';
                          echo '<div class="document-grid">';
                          foreach ($uniqueImagesSurat as $imageSurat) {
                            echo '<div class="image-container">';
                            echo '<a href="' . $uploadDir . $imageSurat . '" target="_blank">';
                            echo '<img src="' . $uploadDir . $imageSurat . '" alt="Gambar Surat" class="img-thumbnail">';
                            echo '</a>';
                            echo '<div class="image-name">' . basename($imageSurat) . '</div>';
                            echo '</div>';
                          }
                          echo '</div>';
                          echo '</div>';

                          // Menampilkan gambar barang
                          echo '<div class="col-6">';
                          echo '<div class="document-title">Gambar Barang:</div>';
                          echo '<div class="document-grid">';
                          foreach ($uniqueImagesBarang as $imageBarang) {
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
                            <?php
                            if ($row["CategoryCD"] == "BB") {
                              echo '<th scope="col">Barang</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Satuan</th>';
                            } else {
                              echo '<th scope="col">Barang</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Satuan</th>';
                            }
                            ?>

                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($row["CategoryCD"] == "BB") {
                            $queryd = "SELECT m.MaterialName, rd.Quantity_1, rd.UnitCD_1, rd.Quantity_2, rd.UnitCD_2 
                           FROM importreceptiondetail rd, material m
                           WHERE rd.ItemCD=m.MaterialCD
                                 AND rd.ReceptionID='" . $row["ReceptionID"] . "'";
                            $resultd = mysqli_query($conn, $queryd);
                            while ($rowd = mysqli_fetch_array($resultd)) {
                              echo '<tr>
                                      <td>' . $rowd["MaterialName"] . '</td>
                                      <td>' . number_format($rowd["Quantity_1"], 0, '.', ',') . '</td>
                                      <td>' . $rowd["UnitCD_1"] . '</td>
                                      <td>' . number_format($rowd["Quantity_2"], 0, '.', ',') . '</td>
                                      <td>' . $rowd["UnitCD_2"] . '</td>
                                    </tr>';
                            }
                          } else if ($row["CategoryCD"] == "BPP") {
                            $queryd = "SELECT s.GoodsName, rd.Quantity_1, rd.UnitCD_1
                            FROM receptiondetail rd, supportinggoods s
                            WHERE rd.ItemCD=s.GoodsCD
                                  AND rd.ReceptionID='" . $row["ReceptionID"] . "'";
                            $resultd = mysqli_query($conn, $queryd);
                            while ($rowd = mysqli_fetch_array($resultd)) {
                              echo '<tr>
                                    <td>' . $rowd["GoodsName"] . '</td>
                                    <td>' . number_format($rowd["Quantity_1"], 0, '.', ',') . '</td>
                                    <td>' . $rowd["UnitCD_1"] . '</td>
                                    </tr>';
                            }
                          } else if ($row["CategoryCD"] == "SPR") {
                            $queryd = "SELECT s.PartName, rd.Quantity_1, rd.UnitCD_1
                              FROM receptiondetail rd, sparepart s
                              WHERE rd.ItemCD=s.PartCD
                                    AND rd.ReceptionID='" . $row["ReceptionID"] . "'";
                            $resultd = mysqli_query($conn, $queryd);
                            while ($rowd = mysqli_fetch_array($resultd)) {
                              echo '<tr>
                                     <td>' . $rowd["PartName"] . '</td>
                                     <td>' . number_format($rowd["Quantity_1"], 0, '.', ',') . '</td>
                                     <td>' . $rowd["UnitCD_1"] . '</td>
                                     </tr>';
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                    <br>
                    <div>
                      <a class="btn btn-warning" href="reception.php">Back</a>
                      <?php
                      if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
                        $creator = $_COOKIE["UserID"];
                      } else {
                        die("Error: Cookie 'UserID' tidak ada atau kosong.");
                      }
                      $query_access = "SELECT tPenerimaanBarangI FROM useraccesslevel WHERE UserID = '$creator'";
                      $result_access = mysqli_query($conn, $query_access);
                      $can_update = false;
                      if ($result_access) {
                        $row_access = mysqli_fetch_assoc($result_access);
                        $access_level = $row_access['tPenerimaanBarangI'];
                        if (strpos($access_level, 'C') !== false) {
                          $can_update = true;
                        }
                      } else {
                        die("Error: Gagal mengambil data akses pengguna.");
                      }
                      $reception_id = $row["ReceptionID"];
                      $query_images = "SELECT documentimageI_1, documentimageI_2 FROM importreceptiondetail WHERE ReceptionID = '$reception_id'";
                      $result_images = mysqli_query($conn, $query_images);
                      $images_complete = false;
                      if ($result_images) {
                        $row_images = mysqli_fetch_assoc($result_images);
                        if (!empty($row_images['documentimageI_1']) && !empty($row_images['documentimageI_2'])) {
                          $images_complete = true;
                        }
                      } else {
                        die("Error: Gagal mengambil data gambar.");
                      }
                      if ($row["Status"] == 0 && $can_update && $images_complete) {
                        echo '<button class="btn btn-primary" style="margin-right:3px;" type="button" onclick="toInvoicing(this)" value="' . $row["ReceptionID"] . '">Proses</button>';
                      }
                      echo '<button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg">Edit</button>';
                      ?>
                      <!-- Modal -->
                      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                        aria-labelledby="myExtraLargeModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title" id="myExtraLargeModal">Form Edit Dokumen</h4>
                              <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            </div>
                            <div class="modal-body dark-modal">
                              <div class="card-body custom-input">
                                <form class="row g-3" action="../Process/editdocumenti.php" method="POST"
                                  enctype="multipart/form-data">
                                  <div class=" tab-pane fade show " id=" dokumen" role="tabpanel"
                                    aria-labelledby="dokumen-tab">
                                    <div class="row">
                                      <div class="col-6">
                                        <?php
                                        $receptionID = isset($_GET['id']) ? $_GET['id'] : '';
                                        error_log("ReceptionID: " . $receptionID);
                                        $sql = "SELECT Invoice, PackingList, BL, Insurance FROM importreceptionheader WHERE ReceptionID = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("s", $receptionID);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                          $data = $result->fetch_assoc();
                                          $noInvoice = isset($data['Invoice']) ? $data['Invoice'] : '';
                                          $noPackingList = isset($data['PackingList']) ? $data['PackingList'] : '';
                                          $noBL = isset($data['BL']) ? $data['BL'] : '';
                                          $noInsurance = isset($data['Insurance']) ? $data['Insurance'] : '';
                                        } else {
                                          $noSuratJalan = '';
                                          $noInvoice = '';
                                          error_log("Data not found for ReceptionID: " . $receptionID);
                                        }
                                        $stmt->close();
                                        $conn->close();
                                        ?>
                                        <input type="hidden" name="ReceptionID"
                                          value="<?php echo htmlspecialchars($row['ReceptionID']); ?>">
                                        <label class="form-label" for="noInvoice">No. Invoice</label>
                                        <input class="form-control" id="noInvoice" name="noInvoice" type="text"
                                          value="<?php echo htmlspecialchars($noInvoice); ?>">
                                      </div>
                                      <div class="col-6">
                                        <label class="form-label" for="formInvoice">Dok. Invoice</label>
                                        <input class="form-control" id="formInvoice" name="dokInvoice" type="file">
                                      </div>
                                    </div>
                                    <!-- packing list -->
                                    <div class="row">
                                      <div class="col-6">
                                        <label class="form-label" for="noPackingList">No. Packing List</label>
                                        <input class="form-control" id="noPackingList" name="noPackingList" type="text"
                                          value="<?php echo htmlspecialchars($noPackingList); ?>">
                                      </div>
                                      <div class="col-6">
                                        <label class="form-label" for="formPackingList">Dok. Packing List</label>
                                        <input class="form-control" id="formPackingList" name="dokPackingList"
                                          type="file">
                                      </div>
                                    </div>
                                    <!-- b/l -->
                                    <div class="row">
                                      <div class="col-6">
                                        <label class="form-label" for="noBL">No. B/L</label>
                                        <input class="form-control" id="noBL" name="noBL" type="text"
                                          value="<?php echo htmlspecialchars($noBL); ?>">
                                      </div>
                                      <div class="col-6">
                                        <label class="form-label" for="formBL">Dok. B/L</label>
                                        <input class="form-control" id="formBL" name="dokBL" type="file">
                                      </div>
                                    </div>
                                    <!-- insurance -->
                                    <div class="row">
                                      <div class="col-6">
                                        <label class="form-label" for="noInsurance">No. Insurance</label>
                                        <input class="form-control" id="noInsurance" name="noInsurance" type="text"
                                          value="<?php echo htmlspecialchars($noInsurance); ?>">
                                      </div>
                                      <div class="col-6">
                                        <label class="form-label" for="formInsurance">Dok. Insurance</label>
                                        <input class="form-control" id="formInsurance" name="dokInsurance" type="file">
                                      </div>
                                    </div>
                                  </div>
                                  <div class="tab-pane fade show " id="photofiles" role="tabpanel"
                                    aria-labelledby="profile-tab">
                                    <div class="card">
                                      <div class="card-body">
                                        <label class="form-label" for="dokbarangI">Dokumen Barang</label>
                                        <input class="form-control" id="dokbarangI" name="dokbarangI[]" type="file"
                                          multiple>
                                        <div id="previewContainer" class="preview-container"></div>
                                      </div>
                                      <script>
                                        document.getElementById('dokbarangI').addEventListener('change', function (event) {
                                          const previewContainer = document.getElementById('previewContainer');
                                          previewContainer.innerHTML = ''; // Bersihkan preview sebelumnya
                                          const files = event.target.files;
                                          Array.from(files).forEach(file => {
                                            if (file.type.startsWith('image/')) {
                                              const reader = new FileReader();
                                              reader.onload = function (e) {
                                                const img = document.createElement('img');
                                                img.src = e.target.result;
                                                previewContainer.appendChild(img);
                                              }
                                              reader.readAsDataURL(file);
                                            }
                                          });
                                        });
                                      </script>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-check form-switch">
                                        <input class="form-check-input" id="flexSwitchCheckDefault" type="checkbox"
                                          role="switch" required>
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Apakah informasi
                                          diatas
                                          sudah benar?</label>
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <button class="btn btn-primary" type="submit">Submit</button>
                                    </div>
                                  </div>
                              </div>
                              </form>
                            </div>
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