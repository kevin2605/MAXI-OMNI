<!DOCTYPE html>
<html lang="en">
  <head>
    <?php 
      include "../headcontent.php"; 
      include "../DBConnection.php";
    ?>

    <!-- script sweetaler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      function approveEvent(str) {
        var InvID = str.parentElement.parentElement.parentElement.cells[1].getElementsByTagName("a")[0].innerHTML;
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Approve SO kode " + InvID + " ?",
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ya, setuju!",
            cancelButtonColor: "#d33",
            cancelButtonText: "Tidak"
        }).then((result) => {
            if (result.isConfirmed) {
                document.location = "../Process/eventApprove.php?id=" + str.value;
            }
        });
      }

      function rejectEvent(str) {
        var InvID = str.parentElement.parentElement.parentElement.cells[1].getElementsByTagName("a")[0].innerHTML;
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Reject SO kode " + InvID + " ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ya, setuju!",
            cancelButtonColor: "#d33",
            cancelButtonText: "Tidak"
        }).then((result) => {
            if (result.isConfirmed) {
                document.location = "../Process/eventReject.php?id=" + str.value;
            }
        });
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
              if(isset($_GET["status"])){
                if($_GET["status"] == "success"){
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Approval berhasil dilakukan, silahkan cek di halaman tersebut.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }else if($_GET["status"] == "error"){
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Approval gagal dilakukan, silahkan cek di halaman tersebut.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }if($_GET["status"] == "success-reject"){
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Reject telah dilakukan, silahkan cek di halaman tersebut.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }
              }
            ?>
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>APPROVAL</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item">Approval</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
            <div class="col-sm-12">
                <div class="card">
                  <div class="card-header pb-0 card-no-border">
                    <h3>Dafta Approval Pending</h3>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive custom-scrollbar">
                      <table class="display" id="basic-1">
                        <thead>
                          <tr>
                            <th>Number</th>
                            <th>Ref. Number</th>
                            <th>Sector</th>
                            <th>Action</th>
                            <th>Approval</th>
                            <th>Approval By</th>
                            <th>Approval On</th>
                            <th>Confirmation</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php   
                                $query = "SELECT * FROM approvallist";
                                $result = mysqli_query($conn,$query);
                                while ($row = mysqli_fetch_array($result)) 
                                {
                                    echo '<tr>
                                            <td>'.$row["AppNumber"].'</td>';

                                            //reference link
                                            if($row["RefTable"] == "salesorderheader"){
                                                echo '<td><a href="../Sales/viewSalesOrder.php?id='.$row["RefNumber"].'">'.$row["RefNumber"].'</a></td>
                                                      <td>Sales Order</td>';
                                            }else if($row["RefTable"] == "invoiceheader"){
                                              echo '<td><a href="../Sales/viewInvoice.php?id='.$row["RefNumber"].'">'.$row["RefNumber"].'</a></td>
                                                    <td>Invoice</td>';
                                            }

                                    echo   '<td>'.$row["ActionDone"].'</td>';

                                            if($row["Approval"] == NULL){
                                              echo '<td><span class="badge rounded-pill badge-warning">Pending</span></td>';
                                            }else if($row["Approval"] == 0){
                                              echo '<td><span class="badge rounded-pill badge-danger">Reject</span></td>';
                                            }else if($row["Approval"] == 1){
                                              echo '<td><span class="badge rounded-pill badge-success">Approved</span></td>';
                                            }

                                    echo   '<td>'.$row["ApprovalBy"].'</td>
                                            <td>'.$row["ApprovalOn"].'</td>';
                                            
                                            if($row["ActionDone"] == "No"){
                                              echo '<td> 
                                                    <ul> 
                                                        <button onclick="approveEvent(this)" type="button" class="bg-success b-r-10" value="'.$row["AppNumber"].'">Approve</button>
                                                        <button onclick="rejectEvent(this)" type="button" class="bg-danger border b-r-10" value="'.$row["AppNumber"].'">Reject</button>
                                                    </ul>
                                                    </td>';
                                            }else if($row["ActionDone"] == "Yes"){
                                              echo '<td><span class="badge rounded-pill badge-info">Done</span></td>';
                                            }

                                    echo   '</tr>';
                                }
                            ?>
                          
                        </tbody>
                      </table>
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