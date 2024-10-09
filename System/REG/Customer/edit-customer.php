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
                  <h3>EDIT PELANGGAN</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Edit Pelanggan</li>
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
                        <div class="card-body">
                            <?php
                                $queryc = "SELECT * FROM (customer c JOIN pricelistheader plh ON c.PriceListCD=plh.PriceListCD) WHERE CustID='".$_GET["id"]."'";
                                $resultc = mysqli_query($conn,$queryc);
                                $customer = mysqli_fetch_assoc($resultc);
                            ?>
                            <form class="row g-3" action="../Process/editCustomer.php" method="POST">
                                <input type="hidden" name="custid" value="<?php echo $_GET["id"]; ?>">
                                <div class="col-12"> 
                                    <label class="form-label" for="namapel">Nama Pelanggan<span style="color:red;">*</span></label>
                                    <input class="form-control" id="namapel" name="namapel" type="text" placeholder="-" value="<?php echo $customer["CustName"]; ?>" required>
                                </div>
                                <div class="col-12"> 
                                    <label class="form-label" for="namacom">Nama Perusahaan</label>
                                    <input class="form-control" id="namacom" name="namacom" type="text" value="<?php echo $customer["CompanyName"]; ?>" placeholder="-" readonly>
                                </div>
                                <hr>
                                <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active txt-primary" id="home-tab" data-bs-toggle="tab" href="#informasi" role="tab" aria-controls="home" aria-selected="true">Informasi</a></li>
                                    <li class="nav-item"><a class="nav-link txt-primary" id="profile-tabs" data-bs-toggle="tab" href="#pajak" role="tab" aria-controls="profile" aria-selected="false">Pajak</a></li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="informasi" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="row g-3">
                                            <!-- informasi starts here -->
                                            <div class="col-6"> 
                                                <label class="form-label" for="alamatkirim">Alamat Kirim<span style="color:red;">*</span></label>
                                                <textarea class="form-control" id="alamatkirim" name="alamatkirim" rows="3" required><?php echo $customer["ShipmentAddress"]; ?></textarea>
                                            </div>
                                            <div class="col-6"> 
                                              <label class="col-sm-12 form-label" for="kota">Kota</label>
                                              <input class="form-control" id="kota" name="kota" list="kotaOptions" placeholder="Kota" value="<?php echo $customer["CityName"]; ?>" required>
                                              <datalist id="kotaOptions">
                                                  <?php
                                                      $query = "SELECT CityName FROM city";
                                                      $result = mysqli_query($conn,$query);
                                                      while ($row = mysqli_fetch_array($result)) 
                                                      {
                                                          echo '<option value="'.$row["CityName"].'">'.$row["CityName"].'</option>';
                                                      }
                                                  ?>
                                              </datalist>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label" for="nohp1">No. HP 1<span style="color:red;">*</span></label>
                                                <input class="form-control" id="nohp1" name="nohp1" type="text" minlength="10" maxlength="15" placeholder="081xxxxxxx" value="<?php echo $customer["PhoneNumOne"]; ?>" required>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label" for="nohp2">No. HP 2</label>
                                                <input class="form-control" id="nohp2" name="nohp2" type="text" minlength="10" maxlength="15" placeholder="081xxxxxxx" value="<?php echo $customer["PhoneNumTwo"]; ?>">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="email">Email</label>
                                                <input class="form-control" id="email" name="email" type="email" placeholder="example@gmail.com" value="<?php echo $customer["Email"]; ?>" required>
                                            </div>
                                            <div class="col-6"> 
                                              <label class="col-sm-12 form-label" for="pricelist">Price List<span style="color:red;">*</span></label>
                                              <input class="form-control" id="pricelist" name="pricelist" list="plOptions" placeholder="-- Pilih Group --" value="<?php echo $customer["PriceListCD"]." - ".$customer["PriceListName"]; ?>" required>
                                              <datalist id="plOptions">
                                                  <?php
                                                      $query = "SELECT PriceListCD,PriceListName FROM pricelistheader";
                                                      $result = mysqli_query($conn,$query);
                                                      while ($row = mysqli_fetch_array($result)) 
                                                      {
                                                          echo '<option value="'.$row["PriceListCD"].'">'.$row["PriceListName"].'</option>';
                                                      }
                                                  ?>
                                              </datalist>
                                            </div>
                                            <div class="col-6"> 
                                                <label class="form-label" for="status"></label>
                                                <div class="card-wrapper border rounded-3 checkbox-checked">
                                                <h6 class="sub-title">Status?<span style="color:red;">*</span></h6>
                                                <div class="radio-form">
                                                    <div class="form-check">
                                                      <input class="form-check-input" id="flexRadioDefault3" type="radio"  value="1" <?php if($customer["Status"]==1){echo "checked";} ?> name="customerStatus" required>
                                                      <label class="form-check-label" for="flexRadioDefault3">Active</label>
                                                    </div>
                                                    <div class="form-check">
                                                      <input class="form-check-input" id="flexRadioDefault4" type="radio"  value="0" <?php if($customer["Status"]==0){echo "checked";} ?> name="customerStatus" required>
                                                      <label class="form-check-label" for="flexRadioDefault4">Inactive</label>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="pajak" role="tabpanel">
                                      <div class="row g-3">
                                        <!-- NPWP -->
                                        <div class="col-6">
                                          <h5 class="text-danger"><i>Mohon diisi sesuai dengan NPWP</i></h5>
                                          <br>
                                          <div class="col-12"> 
                                              <label class="form-label" for="nomorNPWP">No. NPWP<span style="color:red;">*</span></label>
                                              <input class="form-control" id="nomorNPWP" name="nomorNPWP" type="text" placeholder="-" value="<?php  if($customer["NPWPNum"]!="-"){echo $customer["NPWPNum"];}else{echo "-";} ?>" required>
                                          </div>
                                          <div class="col-12"> 
                                            <label class="form-label" for="namaNPWP">Nama NPWP<span style="color:red;">*</span></label>
                                            <input class="form-control" id="namaNPWP" name="namaNPWP" type="text" placeholder="-" value="<?php if($customer["NPWPName"]!="-"){echo $customer["NPWPName"];}else{echo "-";} ?>" required>
                                          </div>
                                          <div class="col-12"> 
                                              <label class="form-label" for="alamatNPWP">Alamat NPWP<span style="color:red;">*</span></label>
                                              <textarea class="form-control" id="alamatNPWP" name="alamatNPWP" rows="3" required><?php if($customer["NPWPAddress"]!="-"){echo $customer["NPWPAddress"];}else{echo "-";} ?></textarea>
                                          </div>
                                        </div>
                                        <!-- KTP -->
                                        <div class="col-6">
                                          <h5 class="text-danger"><i>Mohon diisi sesuai dengan KTP</i></h5>
                                          <br>
                                          <div class="col-12">
                                              <label class="form-label" for="nik">No. NIK<span style="color:red;">*</span></label>
                                              <input class="form-control" id="nik" name="nik" type="text" placeholder="-" value="<?php if($customer["NIK"]!="-"){echo $customer["NIK"];}else{echo "-";} ?>" required>
                                          </div>
                                          <div class="col-12">
                                              <label class="form-label" for="namaktp">Nama KTP<span style="color:red;">*</span></label>
                                              <input class="form-control" id="namaktp" name="namaktp" type="text" placeholder="-" value="<?php if($customer["KTPName"]!="-"){echo $customer["KTPName"];}else{echo "-";} ?>" required>
                                          </div>
                                          <div class="col-12">
                                              <label class="form-label" for="alamatktp">Alamat KTP<span style="color:red;">*</span></label>
                                              <textarea class="form-control" id="alamatktp" name="alamatktp" rows="3" required><?php if($customer["KTPAddress"]!="-"){echo $customer["KTPAddress"];}else{echo "-";} ?></textarea>
                                          </div>
                                        </div>
                                      </div>
                                      <br>
                                      <span style="color:red;">*Jika input kosong, mohon diisi dengan "-".</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12">
                                  <a class="btn btn-warning" href="customer.php">Back</a>
                                  <button class="btn btn-primary" type="submit">Save</button>
                                </div>
                            </form>
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
    <script src="../../assets/js/select2/custom-inputsearch.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="../../assets/js/script.js"></script>
    <!-- Plugin used-->
  </body>
</html>