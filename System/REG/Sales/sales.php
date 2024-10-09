<!DOCTYPE html>
<html lang="en">
  <head>
    <?php 
      include "../headcontent.php"; 
      include "../DBConnection.php";
    ?>

    <!-- AJAX SCRIPT and DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

    <script>
        var i =1;
        function appendProductTable(){
            i++;
            $('#dinamis #dbody').append('<tr id="row'+i+'"><td><input type="text" class="form-control prodlist" onChange="appendProductTable()" name="products[]" list="namelist" required><datalist id="namelist" style="width:3rem;"><?php $queryp = "SELECT * FROM product";$resultp = mysqli_query($conn,$queryp);while ($rowp = mysqli_fetch_array($resultp)){echo '<option value="'.$rowp["ProductCD"].'">'.$rowp["ProductName"].'</option>';}?></datalist></td><td><input type="text" class="form-control" name="prices[]" placeholder="0" required></td><td><input type="text" class="form-control" name="quantities[]" placeholder="0" required></td><td><button id="'+i+'" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button></td></tr>');
        }

        $("document").ready(function(){
            $(document).on('click','.bremove',function(){
                i--;
                var button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
            });

            $("#buttonGen").click(function(){
                //get customer
                var soid = document.getElementById("salesorders").value;
                $.ajax({
                    type: "POST",
                    url: "../Process/getSOCust.php", 
                    data: "id="+soid,
                    success: function(result){
                        var res = JSON.parse(result);
                        $.each(res, function(index, value) {
                            i++;
                            document.getElementById("custid").value = value.CustID;
                            document.getElementById("custname").value = value.CustName;
                            getAppStat(value.CustID);
                        });
                    }
                });
                //get so detail
                $.ajax({
                    type: "POST",
                    url: "../Process/getSODetail.php", 
                    data: "id="+soid,
                    success: function(result){
                        $("#tInv #tInvBody tr").remove(); 
                        var res = JSON.parse(result);
                        $.each(res, function(index, value) {
                            i++;
                            $('#tInv #tInvBody').append('<tr id="row'+i+'"><td style="width:30%"><input type="text" class="form-control prodlist" name="products[]" value="'+ value.ProductCD + " - " + value.ProductName +'" readonly></td><td style="width:10%"><input type="text" class="form-control" name="prices[]" placeholder="0" value="'+ value.Price +'" readonly></td><td style="width:20%"><input type="text" class="form-control" name="quantities[]" placeholder="'+ value.Quantity +'" onChange="countSubtotal(this)" required></td><td style="width:10%"><input type="text" class="form-control" name="discounts[]" onChange="countSubtotal(this)" placeholder="0"></td><td style="width:20%"><input type="text" class="form-control" style="border-style:none;" placeholder="0" readonly></td><td style="width:10%"><button id="'+i+'" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button></td></tr>');
                        });
                    }
                });
            });
        });

        function countSubtotal(x) {
            var harga = x.parentElement.parentElement.cells[1].getElementsByTagName("input")[0].value;
            var jumlah = x.parentElement.parentElement.cells[2].getElementsByTagName("input")[0].value;
            var discount = x.parentElement.parentElement.cells[3].getElementsByTagName("input")[0].value;
            let subtotal = (harga*jumlah)*(1-(discount/100));

            x.parentElement.parentElement.cells[4].getElementsByTagName("input")[0].value = numeral(subtotal).format("0,0.00");
        }

        function getAppStat(cust){
            if (cust=="") {
                document.getElementById("txtHint").innerHTML="No customer data thrown!";
                return;
            }  else {
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    document.getElementById("approval").innerHTML=this.responseText;
                    document.getElementById("approvalinv").innerHTML=this.responseText;
                }
                }
                xmlhttp.open("GET","../Process/getCustAppStatus.php?id="+cust,true);
                xmlhttp.send();
            }
        }

        function viewSales(str) {
            document.location = "viewSalesOrder.php?id=" + str.value;
        }

        function editSales(str) {
            document.location = "editSalesOrder.php?id=" + str.value;
        }

        function deleteSales(str) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Produk dengan kode " + str.value + " akan dihapus dari database!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, setuju!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    alert(str.value);
                    //document.location = "";
                }
            });
        }
    </script>

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
            <?php
              if(isset($_GET["status"])){
                if($_GET["status"] == "success-so"){
                  echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Selamat! </b>Sales Order baru berhasil disimpan ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }else if($_GET["status"] == "error-so"){
                  echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Sales Order ke database.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                }if($_GET["status"] == "success-inv"){
                    echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Selamat! </b>Invoice baru berhasil disimpan ke database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                  }else if($_GET["status"] == "error-inv"){
                    echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan Invoice ke database.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                  }
              }
            ?>
              <div class="row">
                <div class="col-sm-6 ps-0">
                  <h3>PENJUALAN</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Penjualan</li>
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
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target=".modal-sales-order"><i class="fa fa-plus-circle"></i> New Sales Order</button>
                            <div class="modal fade modal-sales-order" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModal" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myExtraLargeModal">Sales Order Baru</h4>
                                            <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body dark-modal">
                                            <div class="card-body custom-input">
                                                <form class="row g-3" action="../Process/createSalesOrder.php" method="POST">
                                                    <div class="col-4"> 
                                                        <label class="form-label" for="soid">SO ID<span style="color:red;">*</span></label>
                                                        <input class="form-control" id="soid" type="text" placeholder="auto-generated" aria-label="First name" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label" for="exampleFormControlInput1">Tanggal<span style="color:red;">*</span></label>
                                                        <input class="form-control" id="exampleFormControlInput1" type="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                                                    </div>
                                                    <div class="col-4"> 
                                                        <label class="form-label" for="creator">Pembuat SO<span style="color:red;">*</span></label>
                                                        <input class="form-control" id="creator" name="creator" type="text" value="12345 - Kevin C Mulia" readonly>
                                                    </div>
                                                    <div class="col-8"> 
                                                        <label class="form-label" for="customer">Pelanggan<span style="color:red;">*</span></label>
                                                        <input class="form-control" id="customer" name="customer" list="custOptions" placeholder="Pilih Pelanggan --" onchange="getAppStat(this.value)" required>
                                                        <datalist id="custOptions">
                                                            <?php
                                                                $queryc = "SELECT * FROM customer";
                                                                $resultc = mysqli_query($conn,$queryc);
                                                                while ($rowc = mysqli_fetch_array($resultc)) 
                                                                {
                                                                    echo '<option value="'.$rowc["CustID"].'">'.$rowc["CustName"].'</option>';
                                                                }
                                                            ?>
                                                        </datalist>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label" for="approval"><i>Approval</i></label>
                                                        <div id="approval">
                                                            -
                                                        </div>
                                                    </div>
                                                    <div class="col-3"> 
                                                        <label class="form-label" for="logo">Logo</label>
                                                        <input class="form-control" id="logo" name="logo" list="logoOptions">
                                                        <datalist id="logoOptions">
                                                            <option value="San Francisco"></option>
                                                            <option value="New York"></option>
                                                            <option value="Seattle"></option>
                                                            <option value="Los Angeles"></option>
                                                            <option value="Chicago"></option>
                                                            <option value="India"> </option>
                                                        </datalist>
                                                    </div>
                                                    <div class="col-9"> 
                                                        <label class="form-label" for="desc">Keterangan</label>
                                                        <input class="form-control" id="desc" name="desc" type="text" placeholder="-" required>
                                                    </div>
                                                    <hr>
                                                    <h3>Detil Order</h3>
                                                    <table id="dinamis" class="table">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">Produk</th>
                                                            <th scope="col">Harga</th>
                                                            <th scope="col">Jumlah</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="dbody">
                                                            <tr id="row1">
                                                                <td>
                                                                    <input type="text" class="form-control prodlist" name="products[]" list="prodOptions" onChange="appendProductTable()" required>
                                                                    <datalist id="prodOptions">
                                                                        <?php
                                                                            $queryp = "SELECT * FROM product";
                                                                            $resultp = mysqli_query($conn,$queryp);
                                                                            while ($rowp = mysqli_fetch_array($resultp)) 
                                                                            {
                                                                                echo '<option value="'.$rowp["ProductCD"].'">'.$rowp["ProductName"].'</option>';
                                                                            }
                                                                        ?>
                                                                    </datalist>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="prices[]" placeholder="0" required>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="quantities[]" placeholder="0" required>
                                                                </td>
                                                                <td>
                                                                    
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <hr>
                                                    <div class="col-12"> 
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" id="flexSwitchCheckDefault" type="checkbox" role="switch" required>
                                                        <label class="form-check-label" for="flexSwitchCheckDefault">Apakah informasi diatas sudah benar?</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <button class="btn btn-primary" type="submit" name="submitSO">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target=".modal-invoice"><i class="fa fa-plus-circle"></i> New Invoice</button>
                            <div class="modal fade modal-invoice" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModal" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myExtraLargeModal">Invoice Baru</h4>
                                            <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body dark-modal">
                                            <div class="card-body custom-input">
                                                <form class="row g-3" action="../Process/createInvoice.php" method="POST">
                                                    <div class="col-3">
                                                        <label class="form-label" for="exampleFormControlInput1">Tanggal</label>
                                                        <input class="form-control" id="exampleFormControlInput1" type="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                                                    </div>
                                                    <div class="col-6"> 
                                                        <label class="form-label" for="salesorders">Pilih Sales Order</label>
                                                        <input class="form-control" id="salesorders" name="salesorder" list="soOptions" placeholder="Pilih Sales Order --" required>
                                                        <datalist id="soOptions">
                                                            <?php
                                                                $query = "SELECT SalesOrderID FROM salesorderheader WHERE ApprovalStatus='Approved' AND Finish=0";
                                                                $result = mysqli_query($conn,$query);
                                                                while ($row = mysqli_fetch_array($result)) 
                                                                {
                                                                    echo '<option value="'.$row["SalesOrderID"].'">'.$row["SalesOrderID"].'</option>';
                                                                }
                                                            ?>
                                                        </datalist>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="form-label" for="buttonGen"><i>Generate</i></label>
                                                        <button class="form-control btn btn-primary" type="button" id="buttonGen">Generate</button>
                                                    </div>
                                                    <div class="col-3"> 
                                                        <label class="form-label" for="custid">ID Pelanggan<span style="color:red;">*</span></label>
                                                        <input class="form-control" id="custid" name="custid" type="text" readonly>
                                                    </div>
                                                    <div class="col-6"> 
                                                        <label class="form-label" for="custname">Nama Pelanggan<span style="color:red;">*</span></label>
                                                        <input class="form-control" id="custname" name="custname" type="text" readonly>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="form-label" for="approvalinv"><i>Approval</i></label>
                                                        <div id="approvalinv">
                                                            -
                                                        </div>
                                                    </div>
                                                    <div class="col-4"> 
                                                        <label class="form-label" for="kodejurnal">Jurnal</label>
                                                        <input class="form-control" id="kodejurnal" name="kodejurnal" list="jurnalOptions" placeholder="Jurnal" required>
                                                        <datalist id="jurnalOptions">
                                                            <?php
                                                                $query = "SELECT JournalCD, JournalName FROM journal";
                                                                $result = mysqli_query($conn,$query);
                                                                while ($row = mysqli_fetch_array($result)) 
                                                                {
                                                                    echo '<option value="'.$row["JournalCD"].'">'.$row["JournalName"].'</option>';
                                                                }
                                                            ?>
                                                        </datalist>
                                                    </div>
                                                    <div class="col-4"> 
                                                        <label class="form-label" for="tipepembayaran">Tipe Pembayaran</label>
                                                        <input class="form-control" id="tipepembayaran" name="tipepembayaran" list="payOptions" placeholder="Pembayaran" required>
                                                        <datalist id="payOptions">
                                                            <?php
                                                                $query = "SELECT PaymentCD, PaymentName FROM payment";
                                                                $result = mysqli_query($conn,$query);
                                                                while ($row = mysqli_fetch_array($result)) 
                                                                {
                                                                    echo '<option value="'.$row["PaymentCD"].'">'.$row["PaymentName"].'</option>';
                                                                }
                                                            ?>
                                                        </datalist>
                                                    </div>
                                                    <div class="col-4"> 
                                                        <label class="form-label" for="gudang">Gudang</label>
                                                        <input class="form-control" id="gudang" name="gudang" list="gudangOptions" placeholder="Gudang" required>
                                                        <datalist id="gudangOptions">
                                                            <?php
                                                                $query = "SELECT WarehCD, WarehName FROM warehouse";
                                                                $result = mysqli_query($conn,$query);
                                                                while ($row = mysqli_fetch_array($result)) 
                                                                {
                                                                    echo '<option value="'.$row["WarehCD"].'">'.$row["WarehName"].'</option>';
                                                                }
                                                            ?>
                                                        </datalist>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="desc">Keterangan</label>
                                                        <input class="form-control" id="desc" name="desc" type="text">
                                                    </div>
                                                    <hr>
                                                    <h3>Detil Order</h3>
                                                    <table id="tInv" class="table" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th style="width:30%">Produk</th>
                                                            <th style="width:10%">Harga</th>
                                                            <th style="width:20%">Jumlah</th>
                                                            <th style="width:10%">Discount</th>
                                                            <th style="width:20%">Subtotal</th>
                                                            <th style="width:10%">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tInvBody">
                                                            <!-- APPEND BT AJAX -->
                                                        </tbody>
                                                    </table>
                                                    <div class="col-12">
                                                        <button class="btn btn-primary" type="submit" name="submitInv">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Menu</button>
                            <ul class="dropdown-menu dropdown-block" id="myTab" role="tablist">
                                <li class="nav-item"><a class="dropdown-item active txt-primary f-w-500 f-18" id="home-tab" data-bs-toggle="tab" href="#daftarSO" role="tab" aria-controls="home" aria-selected="true">Daftar Sales Order</a></li>
                                <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18" id="profile-tabs" data-bs-toggle="tab" href="#daftarInv" role="tab" aria-controls="profile" aria-selected="false">Daftar Invoice</a></li>
                                <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18" id="contact-tab" data-bs-toggle="tab" href="#downPayment" role="tab" aria-controls="contact" aria-selected="false">Down Payment</a></li>
                                <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18" id="contact-tab" data-bs-toggle="tab" href="#returPenjualan" role="tab" aria-controls="contact" aria-selected="false">Retur Penjualan</a></li>
                                <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18" id="contact-tab" data-bs-toggle="tab" href="#settingNota" role="tab" aria-controls="contact" aria-selected="false">Setting Nota</a></li>
                            </ul>
                            <hr>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="daftarSO" role="tabpanel">
                                    <h3>Daftar Sales Order</h3>
                                    <br>
                                    <div class="container-fluid general-widget">
                                        <div class="row">
                                        <div class="col-xl-3 col-lg-3 col-md-6">
                                            <div class="card web-card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <div class="header-top">
                                                            <div class="mobile-app bg-light-primary"><span></span>
                                                            <svg>
                                                                <use href="../../assets/svg/icon-sprite.svg#improvement"></use>
                                                            </svg>
                                                            </div>
                                                            <div class="flex-grow-1"> 
                                                            <h4>TOTAL PENJUALAN</h4><span>Akumulasi Sales Order</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment">
                                                        <ul> 
                                                            <li> 
                                                                <span>Total 1230 Sales Order Baru</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-shrink-0"> 
                                                        <p class="f-28 f-w-500">Rp 1.234.567.890</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6">
                                            <div class="card web-card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <div class="header-top">
                                                            <div class="mobile-app bg-light-primary"><span></span>
                                                            <svg>
                                                                <use href="../../assets/svg/icon-sprite.svg#improvement"></use>
                                                            </svg>
                                                            </div>
                                                            <div class="flex-grow-1"> 
                                                            <h4>TOTAL PENJUALAN</h4><span>Akumulasi Sales Order</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment">
                                                        <ul> 
                                                            <li> 
                                                                <span>Total 1230 Sales Order Baru</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-shrink-0"> 
                                                        <p class="f-28 f-w-500">Rp 1.234.567.890</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6">
                                            <div class="card web-card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <div class="header-top">
                                                            <div class="mobile-app bg-light-primary"><span></span>
                                                            <svg>
                                                                <use href="../../assets/svg/icon-sprite.svg#improvement"></use>
                                                            </svg>
                                                            </div>
                                                            <div class="flex-grow-1"> 
                                                            <h4>TOTAL PENJUALAN</h4><span>Akumulasi Sales Order</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment">
                                                        <ul> 
                                                            <li> 
                                                                <span>Total 1230 Sales Order Baru</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-shrink-0"> 
                                                        <p class="f-28 f-w-500">Rp 1.234.567.890</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6">
                                            <div class="card web-card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <div class="header-top">
                                                            <div class="mobile-app bg-light-primary"><span></span>
                                                            <svg>
                                                                <use href="../../assets/svg/icon-sprite.svg#improvement"></use>
                                                            </svg>
                                                            </div>
                                                            <div class="flex-grow-1"> 
                                                            <h4>TOTAL PENJUALAN</h4><span>Akumulasi Sales Order</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment">
                                                        <ul> 
                                                            <li> 
                                                                <span>Total 1230 Sales Order Baru</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-shrink-0"> 
                                                        <p class="f-28 f-w-500">Rp 1.234.567.890</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive custom-scrollbar user-datatable">
                                        <table class="table" id="basic-12">
                                            <thead>
                                            <tr>
                                                <th>Sales Order ID</th>
                                                <th>Tanggal</th>
                                                <th>Pelanggan</th>
                                                <th>Approval?</th>
                                                <th>Status</th>
                                                <th>Approval Oleh</th>
                                                <th>Waktu Approval</th>
                                                <th>Last Edit</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                              $querySO = "SELECT soh.SalesOrderID, soh.CreatedOn, c.CustName, soh.Approval, soh.ApprovalStatus, soh.ApprovalBy, soh.ApprovalOn, soh.LastEdit, soh.Finish
                                                            FROM (salesorderheader soh JOIN customer c ON soh.CustID=c.CustID)";
                                              $resultSO = mysqli_query($conn,$querySO);
                                              while ($rowSO = mysqli_fetch_array($resultSO)) 
                                              {
                                                echo '
                                                    <tr>
                                                        <td>'.$rowSO["SalesOrderID"].'</td>
                                                        <td>'.$rowSO["CreatedOn"].'</td>
                                                        <td>'.$rowSO["CustName"].'</td>';
                                                        //approval yes or no
                                                        if($rowSO["Approval"]==1){
                                                            echo '<td><span class="badge badge-light-danger">Yes</span></td>';
                                                        }else{
                                                            echo '<td><span class="badge badge-light-success">No</span></td>';
                                                        }

                                                        //approval status
                                                        if($rowSO["ApprovalStatus"] == "Pending"){
                                                            echo '<td><span class="badge badge-light-danger">Pending</span></td>';
                                                        }else if($rowSO["ApprovalStatus"] == "Reject"){
                                                            echo '<td><span class="badge badge-light-danger">Reject</span></td>';
                                                        }else if($rowSO["ApprovalStatus"] == "Approved"){
                                                            echo '<td><span class="badge badge-light-success">Approved</span></td>';
                                                        }

                                                        //get user name 
                                                        if($rowSO["ApprovalBy"]==NULL){
                                                            echo '<td><span class="badge badge-light-primary">None</span></td>';
                                                        }else{
                                                            $queryN = "SELECT * FROM systemuser WHERE UserID='".$rowSO["ApprovalBy"]."'";
                                                            $resultN = mysqli_query($conn,$queryN);
                                                            $rowN=mysqli_fetch_assoc($resultN);
                                                            echo '<td><span class="badge badge-light-primary">'.$rowN["Name"].'</span></td>';
                                                        }

                                                        //approval time
                                                        if($rowSO["ApprovalOn"]==NULL){
                                                            echo '<td><span class="badge badge-light-primary">None</span></td>';
                                                        }else{
                                                            echo '<td><span class="badge badge-light-primary">'.$rowSO["ApprovalOn"].'</span></td>';
                                                        }

                                                echo   '<td>'.$rowSO["LastEdit"].'</td>';
                                                        
                                                        //complete or not
                                                        if($rowSO["Finish"] == 1){
                                                            echo '<td><span class="badge badge-light-success">Complete</span></td>';
                                                        }else{
                                                            echo '<td><span class="badge badge-light-danger">Pending</span></td>';
                                                        }

                                                echo   '<td> 
                                                        <ul> 
                                                            <button onclick="viewSales(this)" type="button" class="light-card border-primary border b-r-10" value="'.$rowSO["SalesOrderID"].'"><i class="fa fa-eye txt-primary"></i></button>
                                                            <button onclick="editSales(this)" type="button" class="light-card border-warning border b-r-10" value="'.$rowSO["SalesOrderID"].'"><i class="fa fa-pencil-square-o txt-warning"></i></button>
                                                            <button onclick="printSales(this)" type="button" class="light-card border-info border b-r-10" value="'.$rowSO["SalesOrderID"].'"><i class="fa fa-print txt-info"></i></button>
                                                            <button onclick="deleteSales(this)" type="button" class="light-card border-danger border b-r-10" value="'.$rowSO["SalesOrderID"].'"><i class="icon-trash txt-danger"></i></button>
                                                        </ul>
                                                        </td>
                                                    </tr>
                                                ';
                                              }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="daftarInv" role="tabpanel">
                                    <h3>Daftar Invoice</h3>
                                    <br>
                                    <div class="container-fluid general-widget">
                                        <div class="row">
                                        <div class="col-xl-3 col-lg-3 col-md-6">
                                            <div class="card web-card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <div class="header-top">
                                                            <div class="mobile-app bg-light-primary"><span></span>
                                                            <svg>
                                                                <use href="../../assets/svg/icon-sprite.svg#improvement"></use>
                                                            </svg>
                                                            </div>
                                                            <div class="flex-grow-1"> 
                                                            <h4>TOTAL NOMINAL</h4><span>Akumulasi Nominal Invoice</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment">
                                                        <ul> 
                                                            <li> 
                                                                <span>Total 1230 Invoice</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-shrink-0"> 
                                                        <p class="f-28 f-w-500">Rp 1.234.567.890</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6">
                                            <div class="card web-card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <div class="header-top">
                                                            <div class="mobile-app bg-light-primary"><span></span>
                                                            <svg>
                                                                <use href="../../assets/svg/icon-sprite.svg#improvement"></use>
                                                            </svg>
                                                            </div>
                                                            <div class="flex-grow-1"> 
                                                            <h4>PAID INVOICE</h4><span>Invoice yang telah dibayar</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment">
                                                        <ul> 
                                                            <li> 
                                                                <span>123 Invoice</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-shrink-0"> 
                                                        <p class="f-28 f-w-500">Rp 500.758.291</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6">
                                            <div class="card web-card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <div class="header-top">
                                                            <div class="mobile-app bg-light-primary"><span></span>
                                                            <svg>
                                                                <use href="../../assets/svg/icon-sprite.svg#improvement"></use>
                                                            </svg>
                                                            </div>
                                                            <div class="flex-grow-1"> 
                                                            <h4>UNPAID INVOICE</h4><span>Invoice yang belum terbayar</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment">
                                                        <ul> 
                                                            <li> 
                                                                <span>1005 Invoice</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-shrink-0"> 
                                                        <p class="f-28 f-w-500">Rp 700.123.419</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6">
                                            <div class="card web-card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <div class="header-top">
                                                            <div class="mobile-app bg-light-primary"><span></span>
                                                            <svg>
                                                                <use href="../../assets/svg/icon-sprite.svg#improvement"></use>
                                                            </svg>
                                                            </div>
                                                            <div class="flex-grow-1"> 
                                                            <h4>-</h4><span>-</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="comment">
                                                        <ul> 
                                                            <li> 
                                                                <span>-</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-shrink-0"> 
                                                        <p class="f-28 f-w-500">Rp 0</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive custom-scrollbar user-datatable">
                                        <table class="table" id="basic-100">
                                            <thead>
                                            <tr>
                                                <th>Invoice ID</th>
                                                <th>Tanggal</th>
                                                <th>Pelanggan</th>
                                                <th>Nominal</th>
                                                <th>Status</th>
                                                <th>Approval Oleh</th>
                                                <th>Waktu Approval</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                              $queryINV = "SELECT invh.InvoiceID, invh.SalesOrderID, invh.CreatedOn, c.CustName, invh.TotalInvoice, invh.Approval, invh.ApprovalStatus, 
                                                          invh.ApprovalBy, invh.ApprovalOn, invh.LastEdit FROM (invoiceheader invh JOIN customer c ON invh.CustID=c.CustID) WHERE invh.InvoiceStatus=0";
                                              $resultINV = mysqli_query($conn,$queryINV);
                                              while ($rowINV = mysqli_fetch_array($resultINV)) 
                                              {
                                                echo '
                                                    <tr>
                                                        <td>'.$rowINV["InvoiceID"].'</td>
                                                        <td>'.$rowINV["CreatedOn"].'</td>
                                                        <td>'.$rowINV["CustName"].'</td>
                                                        <td>Rp '.number_format($rowINV["TotalInvoice"],0,'.',',').'</td>';

                                                        //approval status
                                                        if($rowINV["ApprovalStatus"] == "Pending"){
                                                            echo '<td><span class="badge badge-light-danger">Pending</span></td>';
                                                        }else if($rowINV["ApprovalStatus"] == "Reject"){
                                                            echo '<td><span class="badge badge-light-danger">Reject</span></td>';
                                                        }else if($rowINV["ApprovalStatus"] == "Approved"){
                                                            echo '<td><span class="badge badge-light-success">Approved</span></td>';
                                                        }

                                                        //get user name 
                                                        if($rowINV["ApprovalBy"]==NULL){
                                                            echo '<td><span class="badge badge-light-primary">None</span></td>';
                                                        }else{
                                                            $queryN = "SELECT * FROM systemuser WHERE UserID='".$rowINV["ApprovalBy"]."'";
                                                            $resultN = mysqli_query($conn,$queryN);
                                                            $rowN=mysqli_fetch_assoc($resultN);
                                                            echo '<td><span class="badge badge-light-primary">'.$rowN["Name"].'</span></td>';
                                                        }

                                                        //approval time
                                                        if($rowINV["ApprovalOn"]==NULL){
                                                            echo '<td><span class="badge badge-light-primary">None</span></td>';
                                                        }else{
                                                            echo '<td><span class="badge badge-light-primary">'.$rowINV["ApprovalOn"].'</span></td>';
                                                        }

                                                echo   '<td> 
                                                        <ul> 
                                                            <button onclick="viewInt(this)" type="button" class="light-card border-primary border b-r-10" value="'.$rowINV["InvoiceID"].'"><i class="fa fa-eye txt-primary"></i></button>
                                                            <button onclick="editInv(this)" type="button" class="light-card border-warning border b-r-10" value="'.$rowINV["InvoiceID"].'"><i class="fa fa-pencil-square-o txt-warning"></i></button>
                                                            <button onclick="printInv(this)" type="button" class="light-card border-info border b-r-10" value="'.$rowINV["InvoiceID"].'"><i class="fa fa-print txt-info"></i></button>
                                                            <button onclick="deleteInv(this)" type="button" class="light-card border-danger border b-r-10" value="'.$rowINV["InvoiceID"].'"><i class="icon-trash txt-danger"></i></button>
                                                        </ul>
                                                        </td>
                                                    </tr>
                                                ';
                                              }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="downPayment" role="tabpanel">
                                    <h3>Down Payment</h3>
                                    <br>
                                    <div class="row">
                                        <form action="../testForm.php" method="post">
                                            <div class="select-box" style="float:left;">
                                                <div class="options-container">
                                                    <div class="selection-option">
                                                        <input class="radio" id="webdesigner" type="radio" name="barang" value="SINV-2404-0027">
                                                        <label class="mb-0" for="webdesigner">SINV-2404-0027</label>
                                                    </div>
                                                    <div class="selection-option">
                                                        <input class="radio" id="film" type="radio" name="barang" value="SINV-2404-0028">
                                                        <label class="mb-0" for="film">SINV-2404-0028</label>
                                                    </div>
                                                    <div class="selection-option">
                                                        <input class="radio" id="software" type="radio" name="barang" value="SINV-2404-0029">
                                                        <label class="mb-0" for="software">SINV-2404-0029</label>
                                                    </div>
                                                </div>
                                                <div class="selected-box">Pilih Invoice</div>
                                                <div class="search-box">
                                                    <input type="text" placeholder="Cari disini...">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-md" style="margin-top:5px;float:left;"><i class="icofont icofont-file-document"></i> Generate</button>
                                        </form>
                                    </div>
                                    <div class="row">
                                        PAGE
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="returPenjualan" role="tabpanel">
                                    <h3>Retur Penjualan</h3>
                                    <br>
                                    <div class="row">
                                        <form action="../testForm.php" method="post">
                                            <div class="select-box" style="float:left;">
                                                <div class="options-container">
                                                    <div class="selection-option">
                                                        <input class="radio" id="webdesigner" type="radio" name="barang" value="SINV-2404-0027">
                                                        <label class="mb-0" for="webdesigner">SINV-2404-0027</label>
                                                    </div>
                                                    <div class="selection-option">
                                                        <input class="radio" id="film" type="radio" name="barang" value="SINV-2404-0028">
                                                        <label class="mb-0" for="film">SINV-2404-0028</label>
                                                    </div>
                                                    <div class="selection-option">
                                                        <input class="radio" id="software" type="radio" name="barang" value="SINV-2404-0029">
                                                        <label class="mb-0" for="software">SINV-2404-0029</label>
                                                    </div>
                                                </div>
                                                <div class="selected-box">Pilih Invoice</div>
                                                <div class="search-box">
                                                    <input type="text" placeholder="Cari disini...">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-md" style="margin-top:5px;float:left;"><i class="icofont icofont-file-document"></i> Generate</button>
                                        </form>
                                    </div>
                                    <div class="row">
                                        PAGE
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="settingNota" role="tabpanel">
                                    <h3>Setting Nota</h3>
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
    <script src="../../assets/js/select2/custom-inputsearch.js"></script>
    <!-- Plugins JS Ends-->
    
    <!-- JS FOR NOTF -->
    <script src="../../assets/js/notify/index.js"></script>
    <!-- Theme js-->
    <script src="../../assets/js/script.js"></script>
    <!-- Plugin used-->
  </body>
</html>