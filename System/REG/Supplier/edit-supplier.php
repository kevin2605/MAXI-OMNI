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
                  <h3>VIEW SUPPLIER</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Kontak</li>
                    <li class="breadcrumb-item">Supplier</li>
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
                                $querys = "SELECT * FROM supplier WHERE SupplierNum='".$_GET["id"]."'";
                                $results = mysqli_query($conn,$querys);
                                $supplier = mysqli_fetch_assoc($results);
                            ?>
                            <form class="row g-3" action="../Process/editSupplier.php" method="POST">
                                <input type="hidden" name="suppnum" value="<?php echo $supplier["SupplierNum"]; ?>">
                                <div class="col-12"> 
                                    <label class="form-label" for="namasupplier">Nama Supplier<span style="color:red;">*</span></label>
                                    <input class="form-control" id="namasupplier" name="namasupplier" type="text" placeholder="-" value="<?php echo $supplier["SupplierName"]; ?>" required>
                                </div>
                                <hr>
                                <ul class="simple-wrapper nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active txt-primary" id="home-tab" data-bs-toggle="tab" href="#informasi" role="tab" aria-controls="home" aria-selected="true">Informasi</a></li>
                                    <li class="nav-item"><a class="nav-link txt-primary" id="profile-tabs" data-bs-toggle="tab" href="#pajak" role="tab" aria-controls="profile" aria-selected="false">Pajak</a></li>
                                    <li class="nav-item"><a class="nav-link txt-primary" id="bank-tabs" data-bs-toggle="tab" href="#bank" role="tab" aria-controls="bank" aria-selected="false">Bank</a></li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="informasi" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="row g-3">
                                            <!-- informasi starts here -->
                                            <div class="col-12"> 
                                                <label class="form-label" for="alamat">Alamat<span style="color:red;">*</span></label>
                                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $supplier["SupplierAdd"]; ?></textarea>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="email">Email</label>
                                                <input class="form-control" id="email" name="email" type="email" placeholder="example@saeoil.com" value="<?php echo $supplier["Email"]; ?>">
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label" for="telepon">No. Telepon</label>
                                                <input class="form-control" id="telepon" name="telepon" type="text" minlength="10" maxlength="12" placeholder="031xxxxxxx" value="<?php echo $supplier["Telepon"]; ?>">
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label" for="hpsupplier">No. HP Supplier</label>
                                                <input class="form-control" id="hpsupplier" name="hpsupplier" type="text" minlength="10" maxlength="12" placeholder="031xxxxxxx" value="<?php echo $supplier["PhoneNum"]; ?>">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="namakontak">Nama Kontak<span style="color:red;">*</span></label>
                                                <input class="form-control" id="namakontak" name="namakontak" type="text" placeholder="example@saeoil.com" value="<?php echo $supplier["ContactName"]; ?>" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="hpkontak">No. HP Kontak<span style="color:red;">*</span></label>
                                                <input class="form-control" id="hpkontak" name="hpkontak" type="text" minlength="10" maxlength="12" placeholder="081xxxxxxx" value="<?php echo $supplier["ContactPhone"]; ?>" required>
                                            </div>
                                            <div class="col-12"> 
                                                <label class="form-label" for="description">Description</label>
                                                <textarea class="form-control" id="description" name="description" rows="2"><?php echo $supplier["Description"]; ?></textarea>
                                            </div>
                                            <div class="col-6"> 
                                                <div class="card-wrapper border rounded-3 checkbox-checked">
                                                <h6 class="sub-title">Approval?<span style="color:red;">*</span></h6>
                                                <div class="radio-form">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="flexRadioDefault1" type="radio" value="1" <?php if($supplier["Approval"]==1){echo "checked";} ?> name="approval" required="">
                                                        <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="flexRadioDefault2" type="radio" value="0" <?php if($supplier["Approval"]==0){echo "checked";} ?> name="approval" required="">
                                                        <label class="form-check-label" for="flexRadioDefault2">No</label>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-6"> 
                                                <div class="card-wrapper border rounded-3 checkbox-checked">
                                                <h6 class="sub-title">Status?<span style="color:red;">*</span></h6>
                                                <div class="radio-form">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="flexRadioDefault3" type="radio" value="1" <?php if($supplier["Status"]==1){echo "checked";} ?> name="suppStatus" required="">
                                                        <label class="form-check-label" for="flexRadioDefault3">Active</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="flexRadioDefault4" type="radio" value="0" <?php if($supplier["Status"]==0){echo "checked";} ?> name="suppStatus" required="">
                                                        <label class="form-check-label" for="flexRadioDefault4">Inactive</label>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="pajak" role="tabpanel">
                                        <div class="row g-3">
                                        <div class="col-12"> 
                                            <label class="form-label" for="namaNPWP">Nama NPWP<span style="color:red;">*</span></label>
                                            <input class="form-control" id="namaNPWP" name="namaNPWP" type="text" placeholder="-" value="<?php echo $supplier["NPWPName"]; ?>" required>
                                        </div>
                                        <div class="col-12"> 
                                            <label class="form-label" for="nomorNPWP">Nomor NPWP<span style="color:red;">*</span></label>
                                            <input class="form-control" id="nomorNPWP" name="nomorNPWP" type="text" placeholder="-" value="<?php echo $supplier["NPWPNum"]; ?>" required>
                                        </div>
                                        <div class="col-12"> 
                                            <label class="form-label" for="alamatNPWP">Alamat NPWP<span style="color:red;">*</span></label>
                                            <input class="form-control" id="alamatNPWP" name="alamatNPWP" type="text" placeholder="-" value="<?php echo $supplier["NPWPAddress"]; ?>" required>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="bank" role="tabpanel">
                                        <?php
                                            $queryb = "SELECT * FROM bank WHERE BankCode='".$supplier["BankCode"]."'";
                                            $resultb = mysqli_query($conn,$queryb);
                                            $bank = mysqli_fetch_assoc($resultb);
                                        ?>
                                        <div class="row g-3">
                                            <div class="col-4"> 
                                                <label class="col-sm-12 form-label" for="beneficiaryBank">Bank Penerima</label>
                                                <input class="form-control" id="beneficiaryBank" name="beneficiaryBank" list="banksOptions" value="<?php echo $supplier["BankCode"]; ?>" required>
                                                <datalist id="banksOptions">
                                                    <?php
                                                        $queryc = "SELECT * FROM bank";
                                                        $resultc = mysqli_query($conn,$queryc);
                                                        while ($rowc = mysqli_fetch_array($resultc)) 
                                                        {
                                                            echo '<option value="'.$rowc["BankCode"].'">'.$rowc["BankName"].'</option>';
                                                        }
                                                    ?>
                                                </datalist>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="norek">Nomor Rekening<span style="color:red;">*</span></label>
                                                <input class="form-control" id="norek" name="norek" type="text" minlength="10" maxlength="12" placeholder="xxxxxxxxxx" value="<?php echo $supplier["BeneficiaryNumber"]; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <a class="btn btn-warning" href="supplier.php">Back</a>
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