<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    include "../../DBConnection.php";
    ?>
    <script>
        window.onload = function () {
            var url = new URL(window.location);
            var partname = url.searchParams.get("partname");
            showSparepartDetails(partname);
        };
        function showSparepartDetails(partname) {
            if (partname != "") {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("sparepartDetail").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "../Process/getSparepartDetail.php?partname=" + encodeURIComponent(partname), true);
                xmlhttp.send();
                var xmlhttp2 = new XMLHttpRequest();
                xmlhttp2.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("flowHistory").innerHTML = this.responseText;
                    }
                };
                xmlhttp2.open("GET", "../Process/getSparepartFlowHist.php?partname=" + encodeURIComponent(partname), true);
                xmlhttp2.send();
            } else {
                alert("Nama sparepart kosong!");
            }
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
                        <div class="row">
                            <div class="col-sm-6 ps-0">
                                <h3>HISTORI BARANG</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Barang</li>
                                    <li class="breadcrumb-item">Penunjang Produksi</li>
                                    <li class="breadcrumb-item">Histori</li>
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
                                <div class="card">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4" id="sparepartDetail">
                                                    <!-- Detail sparepart akan muncul di sini -->
                                                </div>
                                                <div class="col-md-8" id="flowHistory">
                                                    <!-- Histori sparepart akan muncul di sini -->
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
    <script src="../../../assets/js/jquery.min.js"></script>
    <!-- Bootstrap js-->
    <script src="../../../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
    <script src="../../../assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="../../../assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- scrollbar js-->
    <script src="../../../assets/js/scrollbar/simplebar.js"></script>
    <script src="../../../assets/js/scrollbar/custom.js"></script>
    <!-- Sidebar jquery-->
    <script src="../../../assets/js/config.js"></script>
    <!-- Plugins JS start-->
    <script src="../../../assets/js/sidebar-menu.js"></script>
    <script src="../../../assets/js/sidebar-pin.js"></script>
    <script src="../../../assets/js/slick/slick.min.js"></script>
    <script src="../../../assets/js/slick/slick.js"></script>
    <script src="../../../assets/js/header-slick.js"></script>
    <script src="../../../assets/js/form-validation-custom.js"></script>
    <script src="../../../assets/js/notify/bootstrap-notify.min.js"></script>
    <script src="../../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../assets/js/datatable/datatables/datatable.custom.js"></script>
    <script src="../../../assets/js/tooltip-init.js"></script>
    <script src="../../../assets/js/modalpage/validation-modal.js"></script>
    <script src="../../../assets/js/height-equal.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Sweetalert2 -->
    <script>
    </script>
    <!-- end sweetaler2 -->
    <!-- DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script>
        $("document").ready(function () {
            var i = 1;
            $(document).on('input', '.prodlist', function () {
                i++;
                $('#dinamis #dbody').append('<tr id="row' + i + '"><td><input type="text" class="form-control prodlist" onselect="myFunction()" name="products[]" list="namelist"><datalist id="namelist" style="width:3rem;"><option value="PC4">Paper Cup 4 oz</option><option value="PC7">Paper Cup 7 oz</option></datalist></td><td><input type="text" class="form-control" name="quantities[]" placeholder="0"></td><td><input type="text" class="form-control" name="discs[]" placeholder="0"></td><td><button id="' + i + '" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button></td></tr>');
            });
            $(document).on('click', '.bremove', function () {
                i--;
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
    </script>
    <!-- Theme js-->
    <script src="../../../assets/js/script.js"></script>
    <!-- Plugin used-->
</body>

</html>