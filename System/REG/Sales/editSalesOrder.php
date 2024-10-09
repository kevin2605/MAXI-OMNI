<!DOCTYPE html>
<html lang="en">
  <head>
    <?php 
      include "../headcontent.php"; 
      include "../DBConnection.php";
    ?>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
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
                  <h3>SALES ORDER</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Sales Order</li>
                    <li class="breadcrumb-item">Edit</li>
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
                                <div class="nav nav-pills horizontal-options shipping-options" id="cart-options-tab" role="tablist" aria-orientation="vertical"><a class="nav-link b-r-0 active" id="bill-wizard-tab" data-bs-toggle="pill" href="#bill-wizard" role="tab" aria-controls="bill-wizard" aria-selected="true"> 
                                    <div class="cart-options">
                                        <div class="stroke-icon-wizard"><i class="fa fa-file-text"></i></div>
                                        <div class="cart-options-content"> 
                                        <h3>Main</h3>
                                        </div>
                                    </div></a><a class="nav-link b-r-0" id="ship-wizard-tab" data-bs-toggle="pill" href="#ship-wizard" role="tab" aria-controls="ship-wizard" aria-selected="false"> 
                                    <div class="cart-options">
                                        <div class="stroke-icon-wizard"><i class="fa fa-user"></i></div>
                                        <div class="cart-options-content"> 
                                        <h3>Pelanggan</h3>
                                        </div>
                                    </div></a>
                                </div>
                                <div class="tab-content dark-field shipping-content" id="cart-options-tabContent">
                                    <div class="tab-pane fade show active" id="bill-wizard" role="tabpanel" aria-labelledby="bill-wizard-tab">
                                    <h3>Informasi Sales Order</h3>
                                    <p class="f-light"></p>
                                    <?php
                                        $querySO = "SELECT soh.SalesOrderID, soh.CreatedOn, soh.Description, c.CustID, c.CustName, c.ShipmentAddress, c.NPWPNum, c.PhoneNumOne,
                                                    c.Email, soh.Finish FROM (salesorderheader soh JOIN customer c ON soh.CustID=c.CustID) WHERE SalesOrderID='".$_GET["id"]."'";
                                        $resultSO = mysqli_query($conn,$querySO);
                                        $row=mysqli_fetch_assoc($resultSO);
                                    ?>
                                    <form class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="customFirstname">Sales Order ID</span></label>
                                            <input class="form-control" id="customFirstname" type="text" value="<?php echo $row["SalesOrderID"]; ?>" readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="customLastname">Tanggal</label>
                                            <input class="form-control" id="customLastname" type="text" value="<?php echo $row["CreatedOn"]; ?>" readonly>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label" for="customContact">Keterangan</label>
                                            <textarea class="form-control" id="customContact"rows="3" readonly><?php echo $row["Description"]; ?></textarea>
                                        </div>
                                    </form>
                                    </div>
                                    <div class="tab-pane fade shipping-wizard" id="ship-wizard" role="tabpanel" aria-labelledby="ship-wizard-tab">
                                    <h3>Informasi Pelanggan</h3>
                                    <p class="f-light"></p>
                                    <form class="row g-3">
                                        <div class="col-sm-4">
                                            <label class="form-label" for="custid">ID Pelanggan</span></label>
                                            <input class="form-control" id="custid" type="text" value="<?php echo $row["CustID"]; ?>" readonly>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="form-label" for="custname">Nama</label>
                                            <input class="form-control" id="custname" type="text" value="<?php echo $row["CustName"]; ?>" readonly>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label" for="shipment">Alamat Pengiriman</label>
                                            <textarea class="form-control" id="shipment"rows="3" readonly><?php echo $row["ShipmentAddress"]; ?></textarea>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label" for="npwp">No. NPWP/NIK</label>
                                            <input class="form-control" id="npwp" type="text" value="<?php if($row["NPWPNum"]!=""){echo $row["NPWPNum"];}else{echo $row["NIK"];} ?>" readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label" for="nohp">No. HP</label>
                                            <input class="form-control" id="nohp" type="text" value="<?php echo $row["PhoneNumOne"]; ?>" readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label" for="email">Email</label>
                                            <input class="form-control" id="email" type="text" value="<?php echo $row["Email"]; ?>" readonly>
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
                                                    <th scope="col">Harga</th>
                                                    <th scope="col">Jumlah</th>
                                                    <th scope="col">Dikirim</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $queryd = "SELECT p.ProductName, sod.Quantity, sod.Price, sod.QuantitySent FROM (salesorderdetail sod JOIN product p ON sod.ProductCD=p.ProductCD) WHERE SalesOrderID='".$row["SalesOrderID"]."'";
                                                $resultd = mysqli_query($conn,$queryd);
                                                while ($rowd = mysqli_fetch_array($resultd)) 
                                                {
                                                    echo '<tr>
                                                            <td>'.$rowd["ProductName"].'</td>
                                                            <td>'.$rowd["Price"].'</td>
                                                            <td>'.number_format($rowd["Quantity"],0,'.',',').'</td>
                                                            <td>'.number_format($rowd["QuantitySent"],0,'.',',').'</td>
                                                            </tr>';
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <div>
                                        <button class="btn btn-warning" onclick="history.back()">Back</button>
                                        <button class="btn btn-primary" type="submit">Save</button>
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
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="../../assets/js/script.js"></script>
    <!-- Plugin used-->
  </body>
</html>