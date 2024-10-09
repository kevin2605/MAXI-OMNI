<!DOCTYPE html>
<html lang="en">
  <head>
    <?php 
      include "../headcontent.php"; 
      include "../DBConnection.php";
    ?>

    <script>
      function calTarget(){
          var speed = document.getElementById("speed").value;
          var cavity = document.getElementById("cavity").value;
          var target = speed*cavity*60;
          document.getElementById("mintarget").value = target*0.85;
          document.getElementById("maxtarget").value = target;
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
                  <h3>MESIN</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Mesin</li>
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
                            <div class="modal-body dark-modal">
                                <div class="card-body custom-input">
                                    <?php
                                        $query = "SELECT *
                                                    FROM machine
                                                    WHERE MachineCD='".$_GET["maccd"]."'";
                                        $result = mysqli_query($conn,$query);
                                        $row=mysqli_fetch_assoc($result);
                                    ?>
                                    <form class="row g-3" action="../Process/editMachine.php" method="POST">
                                        <div class="col-3"> 
                                            <label class="form-label" for="machinecd">Kode Mesin</label>
                                            <input class="form-control" id="machinecd" name="machinecd" type="text" placeholder="1" value="<?php echo $row["MachineCD"] ?>" readonly>
                                        </div>
                                        <div class="col-9">
                                            <label class="form-label" for="machinename">Nama Mesin</label>
                                            <input class="form-control" id="machinename" name="machinename" type="text" placeholder="Mesin A" value="<?php echo $row["MachineName"] ?>" required>
                                        </div>
                                        <div class="col-2"> 
                                            <label class="col-sm-12 col-form-label" for="seq">Urutan</label>
                                            <input class="form-control" id="seq" name="seq" type="number" placeholder="1" value="<?php echo $row["Sequence"] ?>" required>
                                        </div>
                                        <div class="col-2"> 
                                            <label class="col-sm-12 col-form-label" for="speed">Kecepatan Mesin</label>
                                            <input class="form-control digits" id="speed" name="speed" type="number" placeholder="0" onkeyup="calTarget()" value="<?php echo $row["Speed"] ?>" required>
                                        </div>
                                        <div class="col-2"> 
                                            <label class="col-sm-12 col-form-label" for="cavity">Cavity</label>
                                            <input class="form-control digits" id="cavity" name="cavity" type="number" placeholder="0" onkeyup="calTarget()" value="<?php echo $row["Cavity"] ?>" required>
                                        </div>
                                        <div class="col-3"> 
                                            <label class="col-sm-12 col-form-label" for="mintarget">Target 85%</label>
                                            <input class="form-control" id="mintarget" name="mintarget" type="number" placeholder="0" value="<?php echo $row["MinTargetPerHour"] ?>" readonly>
                                        </div>
                                        <div class="col-3"> 
                                            <label class="col-sm-12 col-form-label" for="maxtarget">Target 100%</label>
                                            <input class="form-control" id="maxtarget" name="maxtarget" type="number" placeholder="0" value="<?php echo $row["MaxTargetPerHour"] ?>" readonly>
                                        </div>
                                        <div class="col-12"> 
                                            <div class="card-wrapper border rounded-3 checkbox-checked">
                                            <h6 class="sub-title">Status?</h6>
                                            <div class="radio-form">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="flexRadioDefault3" type="radio" value="1" <?php if($row["Status"]==1){echo "checked";} ?> name="machineStatus" required="">
                                                    <label class="form-check-label" for="flexRadioDefault3">Active</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" id="flexRadioDefault4" type="radio" value="0" <?php if($row["Status"]==0){echo "checked";} ?> name="machineStatus" required="">
                                                    <label class="form-check-label" for="flexRadioDefault4">Inactive</label>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-12"> 
                                            <div class="form-check form-switch">
                                            <input class="form-check-input" id="flexSwitchCheckDefault" type="checkbox" role="switch" required>
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Are you sure above information are true</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <a class="btn btn-warning" href="machine.php">Back</a>
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
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