<!DOCTYPE html>
<html lang="en">
  <head>
    <?php 
      include "../headcontent.php"; 
      include "../DBConnection.php";
    ?>
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
                  <h3>HISTORI HASIL PRODUKSI</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Produksi</li>
                    <li class="breadcrumb-item">SPK Produksi</li>
                    <li class="breadcrumb-item">Histori</li>
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
                      <div class="card-header">
                        <h3>Informasi SPK</h3>
                      </div>
                      <div class="card-body">
                        <?php
                          $query = "SELECT p.ProductionOrderID, p.CreatedOn, m.MachineName, mt.MaterialName, p.MaterialOut, p.UnitCD, pr.ProductName, p.Description, p.EstimateOutcome
                                    FROM productionorder p, machine m, material mt, product pr
                                    WHERE p.MachineCD=m.MachineCD
                                          AND p.MaterialCD=mt.MaterialCD
                                          AND p.ProductCD=pr.ProductCD
                                          AND p.ProductionOrderID='".$_GET["spk"]."'";
                          $result = mysqli_query($conn,$query);
                          $row = mysqli_fetch_assoc($result);
                        ?>
                        <table class="table">
                          <tfoot>
                              <tr> 
                              <td>No. SPK</td>
                              <td colspan="1">: <?php echo $row["ProductionOrderID"] ?></td>
                              </tr>
                              <tr> 
                              <td>Tanggal</td>
                              <td colspan="1">: <?php echo $row["CreatedOn"] ?></td>
                              </tr>
                              <tr> 
                              <td>Mesin</td>
                              <td colspan="2">: <?php echo $row["MachineName"] ?></td>
                              </tr>
                              <tr> 
                              <td>Bahan Baku</td>
                              <td colspan="2">: <?php echo $row["MaterialName"] ?></td>
                              </tr>
                              <tr> 
                              <td>Bahan Keluar</td>
                              <td colspan="2">: <?php echo $row["MaterialOut"].' '.$row["UnitCD"] ?></td>
                              </tr>
                              <tr> 
                              <td>Produk Jadi</td>
                              <td colspan="2">: <?php echo $row["ProductName"] ?></td>
                              </tr>
                              <tr> 
                              <td>Estimasi</td>
                              <td colspan="2">: <?php echo number_format($row["EstimateOutcome"],0,'.',',') ?></td>
                              </tr>
                              <tr> 
                              <td>Keterangan</td>
                              <td colspan="2">: <?php echo $row["Description"] ?></td>
                              </tr>
                          </tfoot>
                        </table>
                        <br>
                        <a class="btn btn-warning" href="production-order.php">Back</a>
                      </div>
                  </div>
              </div>
              <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                      <h3>Histori Produksi</h3>
                    </div>
                    <div class="card-body">
                      <div class="overflow-auto">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Shift</th>
                                    <th scope="col">Hasil Produksi</th>
                                    <th scope="col">Kerusakan Produksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $hasilProduksi = 0;
                                $kerusakan = 0;
                                $queryd = "SELECT CreatedOn, Shift, ProdOutcome, ProdLoss FROM productionresulthistory WHERE ProductionOrderID='".$_GET["spk"]."'";
                                $resultd = mysqli_query($conn,$queryd);
                                while ($rowd = mysqli_fetch_array($resultd)) 
                                {
                                    echo '<tr>
                                            <td>'.$rowd["CreatedOn"].'</td>
                                            <td>'.$rowd["Shift"].'</td>
                                            <td>'.number_format($rowd["ProdOutcome"],0,'.',',').'</td>
                                            <td>'.number_format($rowd["ProdLoss"],0,'.',',').'</td>
                                          </tr>';
                                    $hasilProduksi += $rowd["ProdOutcome"];
                                    $kerusakan += $rowd["ProdLoss"];
                                }
                            ?>
                            </tbody>
                        </table>
                      </div>
                      <br>
                      <div class="row">
                        <label>Hasil Produksi : <?php echo number_format($hasilProduksi,0,'.',','); echo " (".(($hasilProduksi/$row["EstimateOutcome"])*100)."%)"; ?></label>
                        <label>Kerusakan Produksi : <?php echo number_format($kerusakan,0,'.',','); echo " (".(($kerusakan/$row["EstimateOutcome"])*100)."%)"; ?></label>
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