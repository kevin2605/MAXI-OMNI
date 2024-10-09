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
        function btnEditSales(str) {
            Swal.fire({
                title: "Edit Sales Order",
                text: "Sales Order dengan kode " + str.value + " akan di edit!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, setuju!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    //document.location = "editSalesOrder.php?id=" + str.value;
                }
            });
        }

        function closeSO(str) {
            Swal.fire({
                title: "Close Sales Order",
                text: "Sales Order dengan kode " + str.value + " akan di tutup!",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, setuju!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    //document.location = "editSalesOrder.php?id=" + str.value;
                }
            });
        }

        function approveSO(str) {
            Swal.fire({
                title: "Approve Sales Order",
                text: "Menyetujui Sales Order " + str.value + "?",
                icon: "question",
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

        function rejectSO(str) {
            Swal.fire({
                title: "Reject Sales Order",
                text: "Menolak Sales Order " + str.value + "?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, reject!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location = "../Process/eventReject.php?id=" + str.value;
                }
            });
        }

        function closeSO(str) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Sales Order dengan kode " + str.value + " akan ditutup/diselesaikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Ya, setuju!",
                cancelButtonColor: "#d33",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location = "../Process/closeSO.php?id=" + str.value;
                }
            });
        }
        $('.showinfo').click(function (e) {
            e.preventDefault();
            $(this).closest('td').find(".test").toggle();
        });
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
                  <p><b> Selamat! </b>Sales Order ' . $_GET["id"] . ' telah disetujui.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                            } else if ($_GET["status"] == "reject") {
                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> Reject! </b>Sales Order ' . $_GET["id"] . ' tidak disetujui.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                            } else if ($_GET["status"] == "error") {
                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                  <p><b> Error! </b>Sales Order ' . $_GET["id"] . ' gagal untuk diproses.</p>
                  <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                            } else if ($_GET["status"] == "so-close") {
                                echo '<div class="alert txt-warning border-warning outline-2x alert-dismissible fade show alert-icons" role="alert">
                    <p><b> SO Close! </b>Sales Order ' . $_GET["id"] . ' berhasil ditutup/diselesaikan.</p>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                            }
                        }
                        ?>
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
                                    <li class="breadcrumb-item">Detail</li>
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
                                        <div class="nav nav-pills horizontal-options shipping-options"
                                            id="cart-options-tab" role="tablist" aria-orientation="vertical"><a
                                                class="nav-link b-r-0 active" id="bill-wizard-tab" data-bs-toggle="pill"
                                                href="#bill-wizard" role="tab" aria-controls="bill-wizard"
                                                aria-selected="true">
                                                <div class="cart-options">
                                                    <div class="stroke-icon-wizard"><i class="fa fa-file-text"></i>
                                                    </div>
                                                    <div class="cart-options-content">
                                                        <h3>Main</h3>
                                                    </div>
                                                </div>
                                            </a><a class="nav-link b-r-0" id="ship-wizard-tab" data-bs-toggle="pill"
                                                href="#ship-wizard" role="tab" aria-controls="ship-wizard"
                                                aria-selected="false">
                                                <div class="cart-options">
                                                    <div class="stroke-icon-wizard"><i class="fa fa-user"></i></div>
                                                    <div class="cart-options-content">
                                                        <h3>Pelanggan</h3>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="tab-content dark-field shipping-content"
                                            id="cart-options-tabContent">
                                            <div class="tab-pane fade show active" id="bill-wizard" role="tabpanel"
                                                aria-labelledby="bill-wizard-tab">
                                                <h3>Informasi Sales Order</h3>
                                                <p class="f-light"></p>
                                                <?php
                                                $querySO = "SELECT soh.SalesOrderID, soh.CreatedOn, soh.CreatedBy, soh.Marketing, soh.Description, soh.Approval, soh.ApprovalStatus, soh.ApprovalBy, soh.ApprovalOn, c.CustID, c.CustName, c.ShipmentAddress, c.NPWPNum, c.PhoneNumOne,
                                                    c.Email, soh.Finish FROM (salesorderheader soh JOIN customer c ON soh.CustID=c.CustID) WHERE SalesOrderID='" . $_GET["id"] . "'";
                                                $resultSO = mysqli_query($conn, $querySO);
                                                $row = mysqli_fetch_assoc($resultSO);
                                                ?>
                                                <form class="row g-3">
                                                    <div class="col-sm-6">
                                                        <label class="form-label" for="customFirstname">Sales Order
                                                            ID</span></label>
                                                        <input class="form-control" id="customFirstname" type="text"
                                                            value="<?php echo $row["SalesOrderID"]; ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label" for="customLastname">Tanggal</label>
                                                        <input class="form-control" id="customLastname" type="text"
                                                            value="<?php echo $row["CreatedOn"]; ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label" for="maker">Dibuat Oleh</label>
                                                        <?php
                                                        $queryn = "SELECT * FROM systemuser WHERE UserID='" . $row["CreatedBy"] . "'";
                                                        $resultn = mysqli_query($conn, $queryn);
                                                        $rown = mysqli_fetch_assoc($resultn);

                                                        echo '<input class="form-control" id="maker" type="text" value="' . $rown["UserID"] . ' - ' . $rown["Name"] . '" readonly>';
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label" for="marketing">Marketing</label>
                                                        <?php
                                                        $queryn = "SELECT * FROM systemuser WHERE UserID='" . $row["Marketing"] . "'";
                                                        $resultn = mysqli_query($conn, $queryn);
                                                        $rown = mysqli_fetch_assoc($resultn);

                                                        echo '<input class="form-control" id="marketing" type="text" value="' . $rown["UserID"] . ' - ' . $rown["Name"] . '" readonly>';
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label class="form-label" for="customContact">Keterangan</label>
                                                        <textarea class="form-control" id="customContact" rows="3"
                                                            readonly><?php echo $row["Description"]; ?></textarea>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="form-label" for="approval">Approval?</label>
                                                        <?php
                                                        if ($row["Approval"] == 1) {
                                                            echo '<input class="form-control border-danger txt-danger" id="approval" type="text" value="Yes" readonly>';
                                                        } else {
                                                            echo '<input class="form-control border-success txt-success" id="approval" type="text" value="No" readonly>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <label class="form-label" for="approvalstatus">Status</label>
                                                        <?php
                                                        if ($row["ApprovalStatus"] == "Pending") {
                                                            echo '<input class="form-control border-warning txt-warning" id="approvalstatus" type="text" value="Pending" readonly>';
                                                        } else if ($row["ApprovalStatus"] == "Reject") {
                                                            echo '<input class="form-control border-danger txt-danger" id="approvalstatus" type="text" value="Reject" readonly>';
                                                        } else if ($row["ApprovalStatus"] == "Approved") {
                                                            echo '<input class="form-control border-success txt-success" id="approvalstatus" type="text" value="Approved" readonly>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label" for="approvalby">Approved Oleh</label>
                                                        <?php

                                                        if ($row["ApprovalBy"] != null) {
                                                            $queryN = "SELECT * FROM systemuser WHERE UserID='" . $row["ApprovalBy"] . "'";
                                                            $resultN = mysqli_query($conn, $queryN);
                                                            $rowN = mysqli_fetch_assoc($resultN);
                                                            echo '<input class="form-control" id="approvalby" type="text" value="' . $rowN["Name"] . '" readonly>';
                                                        } else {
                                                            echo '<input class="form-control" id="approvalby" type="text" value="-" readonly>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label" for="approvalon">Waktu
                                                            Approval</label>
                                                        <?php
                                                        if ($row["ApprovalOn"] == NULL) {
                                                            echo '<input class="form-control" id="approvalon" type="text" value="-" readonly>';
                                                        } else {
                                                            echo '<input class="form-control" id="approvalon" type="text" value="' . $row["ApprovalOn"] . '" readonly>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <?php
                                                        if ($row["Approval"] == 1 && $row["ApprovalBy"] == 0) {
                                                            echo '<span class="txt-danger"><b>Note:</b> Sales Order ini perlu persetujuan!</span>';
                                                        } else if ($row["Approval"] == 1 && $row["ApprovalBy"] != NULL) {
                                                            echo '<span class="txt-success"><b>Note:</b> Sales Order ini telah disetujui!</span>';
                                                        } else if ($row["Approval"] == 0) {
                                                            echo '<span class="txt-success"><b>Note:</b> Sales Order ini disetujui System!</span>';
                                                        }
                                                        ?>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade shipping-wizard" id="ship-wizard" role="tabpanel"
                                                aria-labelledby="ship-wizard-tab">
                                                <h3>Informasi Pelanggan</h3>
                                                <p class="f-light"></p>
                                                <form class="row g-3">
                                                    <div class="col-sm-4">
                                                        <label class="form-label" for="custid">ID
                                                            Pelanggan</span></label>
                                                        <input class="form-control" id="custid" type="text"
                                                            value="<?php echo $row["CustID"]; ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <label class="form-label" for="custname">Nama</label>
                                                        <input class="form-control" id="custname" type="text"
                                                            value="<?php echo $row["CustName"]; ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label class="form-label" for="shipment">Alamat
                                                            Pengiriman</label>
                                                        <textarea class="form-control" id="shipment" rows="3"
                                                            readonly><?php echo $row["ShipmentAddress"]; ?></textarea>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label" for="npwp">No. NPWP/NIK</label>
                                                        <input class="form-control" id="npwp" type="text" value="<?php if ($row["NPWPNum"] != "") {
                                                            echo $row["NPWPNum"];
                                                        } else {
                                                            echo $row["NIK"];
                                                        } ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label" for="nohp">No. HP</label>
                                                        <input class="form-control" id="nohp" type="text"
                                                            value="<?php echo $row["PhoneNumOne"]; ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label" for="email">Email</label>
                                                        <input class="form-control" id="email" type="text"
                                                            value="<?php echo $row["Email"]; ?>" readonly>
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
                                                        <th></th>
                                                        <th scope="col">Produk</th>
                                                        <th scope="col">Harga</th>
                                                        <th scope="col">Diskon</th>
                                                        <th scope="col">Jumlah</th>
                                                        <th scope="col">Dikirim</th>
                                                        <th scope="col">Sisa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $queryd = "SELECT sod.ProductCD, p.ProductName, sod.Quantity, sod.Price, sod.Discount, sod.QuantitySent FROM (salesorderdetail sod JOIN product p ON sod.ProductCD=p.ProductCD) WHERE SalesOrderID='" . $row["SalesOrderID"] . "'";
                                                    $resultd = mysqli_query($conn, $queryd);
                                                    while ($rowd = mysqli_fetch_array($resultd)) {
                                                        echo '<tr>
                                                            <td><a data-toggle="collapse" href="#' . $rowd["ProductName"] . '" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-plus-square-o"></i></a></td>
                                                            <td>' . $rowd["ProductName"] . '</td>
                                                            <td>' . $rowd["Price"] . '</td>
                                                            <td>' . number_format($rowd["Discount"], 0, '.', ',') . '</td>
                                                            <td>' . number_format($rowd["Quantity"], 0, '.', ',') . '</td>
                                                            <td>' . number_format($rowd["QuantitySent"], 0, '.', ',') . '</td>
                                                            <td>' . number_format($rowd["Quantity"] - $rowd["QuantitySent"], 0, '.', ',') . '</td>
                                                            
                                                          </tr>';
                                                        echo '<tr>
                                                            <td colspan=7 class="collapse" id="' . $rowd["ProductName"] . '">
                                                                <div class="card">
                                                                    <table class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Invoice</th>
                                                                                <th scope="col">Tanggal</th>
                                                                                <th scope="col">Jumlah</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>';
                                                        $queryp = "SELECT * FROM productflowhistory WHERE ReferenceKey IN 
                                                                                (SELECT InvoiceID FROM invoiceheader WHERE SalesOrderID='" . $row["SalesOrderID"] . "' 
                                                                                AND ProductCD='" . $rowd["ProductCD"] . "');";
                                                        $resultp = mysqli_query($conn, $queryp);
                                                        while ($rowp = mysqli_fetch_array($resultp)) {
                                                            echo '<tr>
                                                                                    <td>' . $rowp["ReferenceKey"] . '</td>
                                                                                    <td>' . $rowp["Date"] . '</td>
                                                                                    <td>' . number_format($rowp["FlowOut"], 0, '.', ',') . '</td>
                                                                                  </tr>';
                                                        }
                                                        echo '              </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                          </tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        <div>
                                            <a class="btn btn-warning" href="salesorder.php">Back</a>
                                            <?php
                                            if ($row["ApprovalStatus"] == "Approved" && $row["Finish"] == 0) {
                                                echo '<button class="btn btn-danger" onclick="closeSO(this)" value="' . $row["SalesOrderID"] . '"><i class="fa fa-close"></i> Close</button> ';
                                            }
                                            ?>
                                            <?php
                                            if ($row["ApprovalStatus"] == "Pending") {
                                                echo '<button class="btn btn-success" onclick="approveSO(this)" value="' . $row["SalesOrderID"] . '"><i class="fa fa-check-square-o"></i> Approve</button> ';
                                                echo ' <button class="btn btn-danger" onclick="rejectSO(this)" value="' . $row["SalesOrderID"] . '"><i class="fa fa-close"></i> Reject</button>';
                                            }
                                            ?>
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