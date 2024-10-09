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

    <script>
        var i =1;
        function appendProductTable(){
            i++;
            $('#dinamis #dbody').append(`
                <tr id="row${i}">
                  <td>
                    <input type="text" class="form-control prodlist" onChange="appendProductTable()" name="products[]" list="namelist" required>
                    <datalist id="namelist" style="width:3rem;">
                      <?php 
                        $queryp = "SELECT ProductCD,ProductName 
                                   FROM product 
                                   WHERE ProductCD NOT IN 
                                    (SELECT ProductCD 
                                     FROM pricelistdetail
                                     WHERE PriceListCD='".$_GET["plcd"]."')";
                        $resultp = mysqli_query($conn,$queryp);
                        while ($rowp = mysqli_fetch_array($resultp)){
                          echo '<option value="'.$rowp["ProductCD"].'">'.$rowp["ProductName"].'</option>';
                      }?>
                    </datalist>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="prices[]" placeholder="0" required>
                  </td>
                  <td>
                    <button id="${i}" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button>
                  </td>
                </tr>`);
        }

        $("document").ready(function(){
            $(document).on('click','.bremove',function(){
                i--;
                var button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
            });
        });

        function updatePrice(price){
          var nomor = price.parentElement.getElementsByTagName("input")[0].value;
          console.log(nomor + "|" + price.value);

          //update price in database
          $.ajax({
              type: "POST",
              url: "../Process/updateProdPrice.php", 
              data: "nomor="+nomor+"&price="+price.value,
              success: function(result){
                //alert(result);
                if(result == 1){
                  Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Edit harga berhasil!",
                    showConfirmButton: false,
                    timer: 1000
                  });
                }else{
                  Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Edit harga error!",
                    showConfirmButton: false,
                    timer:1500
                  });
                }
              }
          });
        }

        function deleteProdPrice(nomor){
          var prod = nomor.parentElement.parentElement.cells[2].innerHTML;
          Swal.fire({
              title: "Apakah anda yakin?",
              text: "Menghapus " + prod + " dari Price List?",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              confirmButtonText: "Ya, setuju!",
              cancelButtonColor: "#d33",
              cancelButtonText: "Tidak"
          }).then((result) => {
              if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "../Process/deleteProdPrice.php", 
                    data: "nomor=" + nomor.value,
                    success: function(result){
                      //alert(result);
                      if(result == 1){
                        window.location.reload();
                      }else{
                        Swal.fire({
                          position: "center",
                          icon: "error",
                          title: "Error! Gagal menghapus produk dari Price List!",
                          showConfirmButton: false,
                          timer:1500
                        });
                      }
                    }
                });
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
                  <p><b> Selamat! </b>Harga Barang Baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }else if($_GET["status"] == "error"){
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }
              }
            ?>
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>PRICE LIST</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Barang</li>
                    <li class="breadcrumb-item">Price List</li>
                    <li class="breadcrumb-item">Detail</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="col-md-6">
                        <div class="card-header">
                          <h3>Edit Price List</h3>
                        </div>
                        <div class="card-body">
                          <?php
                              $querypl = "SELECT * FROM pricelistheader WHERE PriceListCD='".$_GET["plcd"]."'";
                              $resultpl = mysqli_query($conn,$querypl);
                              $rowpl=mysqli_fetch_assoc($resultpl);
                          ?>
                          <form class="row g-3" action="../Process/editPriceList.php" method="POST">
                            <input type="hidden" name="PLcode" value="<?php echo $_GET["plcd"] ?>"/>
                            <div class="col-6"> 
                                <label class="form-label" for="PLname">Nama Price List</label>
                                <input class="form-control" id="PLname" name="PLname" type="text" value="<?php echo $rowpl["PriceListName"] ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="minorder">Minimal Pesanan</label>
                                <input class="form-control" id="minorder" name="minorder" type="text" value="<?php echo $rowpl["MinimalOrder"] ?>" required>
                            </div>
                            <div class="col-6"> 
                                <label class="col-sm-12 col-form-label" for="startdate">Start</label>
                                <input class="form-control" id="startdate" name="startdate" type="date" value="<?php echo $rowpl["StartDate"] ?>" required>
                            </div>
                            <div class="col-6"> 
                                <label class="col-sm-12 col-form-label" for="enddate">End</label>
                                <input class="form-control" id="enddate" name="enddate" type="date" value="<?php echo $rowpl["EndDate"] ?>" required>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg"><i class="fa fa-plus-circle"></i> Produk</button>
                            <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModal" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myExtraLargeModal">Form Price List Baru</h4>
                                            <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body dark-modal">
                                            <div class="card-body custom-input">
                                                <form class="row g-3" action="../Process/addPLList.php" method="POST">
                                                    <div class="col-4"> 
                                                        <label class="form-label" for="plcd">Kode PL</label>
                                                        <input class="form-control" id="plcd" name="plcd" type="text" value="<?php echo $_GET["plcd"] ?>" readonly>
                                                    </div>
                                                    <div class="col-8"> 
                                                        <label class="form-label" for="plname">Nama PL</label>
                                                        <input class="form-control" id="plname" name="plname" type="text" value="<?php echo $_GET["plname"] ?>" readonly>
                                                    </div>
                                                    <table id="dinamis" class="table" style="width:100%;">
                                                        <thead>
                                                        <tr>
                                                            <th style="width:70%;">Produk</th>
                                                            <th style="width:30%;">Harga</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="dbody">
                                                            <tr id="row1">
                                                                <td style="width:70%;">
                                                                    <input type="text" class="form-control prodlist" name="products[]" list="prodOptions" onChange="appendProductTable()" required>
                                                                    <datalist id="prodOptions">
                                                                        <?php
                                                                            $queryp = "SELECT * FROM product WHERE ProductCD NOT IN (SELECT ProductCD FROM pricelistdetail WHERE PriceListCD='".$_GET["plcd"]."')";
                                                                            $resultp = mysqli_query($conn,$queryp);
                                                                            while ($rowp = mysqli_fetch_array($resultp)) 
                                                                            {
                                                                                echo '<option value="'.$rowp["ProductCD"].'">'.$rowp["ProductName"].'</option>';
                                                                            }
                                                                        ?>
                                                                    </datalist>
                                                                </td>
                                                                <td style="width:30%;">
                                                                    <input type="text" class="form-control" name="prices[]" placeholder="0" required>
                                                                </td>
                                                                <td>
                                                                    
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="col-12">
                                                        <button class="btn btn-primary" type="submit">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h3>Detail Price List - <?php echo $_GET["plname"] ?></h3>
                            <br>
                            <div class="row">
                                <div class="table-responsive custom-scrollbar">
                                    <table class="display" id="basic-9" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="10%">Urutan</th>
                                            <th width="15%">Kode Produk</th>
                                            <th width="45%">Nama Produk</th>
                                            <th width="15%">Harga</th>
                                            <th width="15%">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $queryh = "SELECT pld.No, pld.ProductCD, p.ProductName, pld.Price, p.Sequence FROM (pricelistdetail pld JOIN product p ON pld.ProductCD=p.ProductCD) WHERE PriceListCD='".$_GET["plcd"]."'";
                                                $resulth = mysqli_query($conn,$queryh);
                                                while ($rowh = mysqli_fetch_array($resulth))
                                                {
                                                echo '
                                                    <tr>
                                                        <td width="10%">'.$rowh["Sequence"].'</td>
                                                        <td width="15%">'.$rowh["ProductCD"].'</td>
                                                        <td width="40%">'.$rowh["ProductName"].'</td>
                                                        <td width="15%">
                                                          <input type="hidden" name="nomor" value="'.$rowh["No"].'">
                                                          <input type="text" class="b-r-7" name="price" onChange="updatePrice(this)" value="'.$rowh["Price"].'" required>
                                                        </td>
                                                        <td width="15%">
                                                          <button class="btn btn-danger" onclick="deleteProdPrice(this)" value="'.$rowh["No"].'"><i class="fa fa-close"></i></button>
                                                        </td>
                                                    </tr>
                                                ';
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-warning" href="price-list.php">Back</a>
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