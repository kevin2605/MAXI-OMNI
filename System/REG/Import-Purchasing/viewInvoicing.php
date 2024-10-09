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
                            if ($_GET["status"] == "success") {
                                echo '<div class="alert txt-success border-success outline-2x alert-dismissible fade show alert-icons" role="alert">
                                <p><b> Selamat! </b>Pembuatan Invoice dari penerimaan berhasil disimpan ke database.</p>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            } else if ($_GET["status"] == "error") {
                                echo '<div class="alert txt-danger border-danger outline-2x alert-dismissible fade show alert-icons" role="alert">
                                <p><b> Error! </b>Terjadi kesalahan saat pembuatan/penyimpanan invoicing ke database.</p>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-6 ps-0">
                                <h3>INVOICE PEMBELIAN BARANG</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Pembelian (Lokal)</li>
                                    <li class="breadcrumb-item">Invoice Pembelian</li>
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
                                    <div class="row shopping-wizard">
                                        <div class="col-12">
                                            <div class="row shipping-form g-5">
                                                <div class="col-xl-4 shipping-border">
                                                    <div class="nav nav-pills horizontal-options shipping-options"
                                                        id="cart-options-tab" role="tablist"
                                                        aria-orientation="vertical"><a class="nav-link b-r-0 active"
                                                            id="bill-wizard-tab" data-bs-toggle="pill"
                                                            href="#bill-wizard" role="tab" aria-controls="bill-wizard"
                                                            aria-selected="true">
                                                            <div class="cart-options">
                                                                <div class="stroke-icon-wizard"><i
                                                                        class="fa fa-file-text"></i></div>
                                                                <div class="cart-options-content">
                                                                    <h3>Main</h3>
                                                                </div>
                                                            </div>
                                                        </a><a class="nav-link b-r-0" id="ship-wizard-tab"
                                                            data-bs-toggle="pill" href="#ship-wizard" role="tab"
                                                            aria-controls="ship-wizard" aria-selected="false">
                                                            <div class="cart-options">
                                                                <div class="stroke-icon-wizard"><i
                                                                        class="fa fa-user"></i></div>
                                                                <div class="cart-options-content">
                                                                    <h3>Supplier</h3>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="tab-content dark-field shipping-content"
                                                        id="cart-options-tabContent">
                                                        <div class="tab-pane fade show active" id="bill-wizard"
                                                            role="tabpanel" aria-labelledby="bill-wizard-tab">
                                                            <h3>Informasi Penerimaan Barang</h3>
                                                            <p class="f-light"></p>
                                                            <?php
                                                            $rcvinvid = $_GET["rcvinvid"];
                                                            $query = "SELECT ri.RCV_InvoiceID, ri.CreatedOn, ri.CreatedBy, ri.DPP, ri.BM, ri.PPN, ri.PPH, ri.TotalAmount, r.ReceptionID,
                                                              r.CreatedOn AS rcvDate, r.CategoryCD, r.Description, p.PurchaseOrderID, ri.Status, s.SupplierNum, s.SupplierName,
                                                              s.SupplierAdd, s.ContactName, s.ContactPhone, s.Telepon
                                                              FROM importreceptioninvoiceheader ri, importreceptionheader r, importpurchaseorderheader p, supplier s 
                                                              WHERE ri.ReceptionID=r.ReceptionID
                                                                    AND r.PurchaseOrderID=p.PurchaseOrderID
                                                                    AND p.SupplierNum=s.SupplierNum
                                                                    AND ri.RCV_InvoiceID='" . $rcvinvid . "'";
                                                            $result = mysqli_query($conn, $query);
                                                            $row = mysqli_fetch_assoc($result);
                                                            ?>
                                                            <div class="row g-3">
                                                                <div class="col-sm-6">
                                                                    <label class="form-label" for="noInvoicing">Nomor
                                                                        Invoicing</label>
                                                                    <input class="form-control" id="noInvoicing"
                                                                        name="noInvoicing" type="text"
                                                                        value="<?php echo $row["RCV_InvoiceID"] ?>"
                                                                        readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="form-label" for="invdate">Tanggal
                                                                        Invoicing</label>
                                                                    <input class="form-control" id="invdate"
                                                                        name="invdate" type="text"
                                                                        value="<?php echo $row["CreatedOn"] ?>"
                                                                        readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="form-label" for="rcvId">Penerimaan
                                                                        Barang</span></label>
                                                                    <input class="form-control" id="rcvId" name="rcvId"
                                                                        type="text"
                                                                        value="<?php echo $row["ReceptionID"] ?>"
                                                                        readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="form-label"
                                                                        for="customLastname">Tanggal Penerimaan</label>
                                                                    <input class="form-control" id="customLastname"
                                                                        type="text"
                                                                        value="<?php echo $row["rcvDate"]; ?>" readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="form-label"
                                                                        for="customLastname">Purchase Order</label>
                                                                    <input class="form-control" id="customLastname"
                                                                        type="text"
                                                                        value="<?php echo $row["PurchaseOrderID"] ?>"
                                                                        readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="form-label" for="maker">Dibuat</label>
                                                                    <?php
                                                                    $queryn = "SELECT * FROM systemuser WHERE UserID='" . $row["CreatedBy"] . "'";
                                                                    $resultn = mysqli_query($conn, $queryn);
                                                                    $rown = mysqli_fetch_assoc($resultn);

                                                                    echo '<input class="form-control" id="maker" type="text" value="' . $rown["UserID"] . ' - ' . $rown["Name"] . '" readonly>';
                                                                    ?>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <label class="form-label"
                                                                        for="desc">Keterangan</label>
                                                                    <textarea class="form-control" id="desc" name="desc"
                                                                        rows="3"
                                                                        readonly><?php echo $row["Description"]; ?></textarea>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="form-label"
                                                                        for="status">Status</label>
                                                                    <?php
                                                                    if ($row["Status"] == "0") {
                                                                        echo '<input class="form-control border-warning txt-warning" id="status" type="text" value="Belum Lunas" readonly>';
                                                                    } else if ($row["Status"] == "1") {
                                                                        echo '<input class="form-control border-success txt-success" id="status" type="text" value="Lunas" readonly>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade shipping-wizard" id="ship-wizard"
                                                            role="tabpanel" aria-labelledby="ship-wizard-tab">
                                                            <h3>Informasi Supplier</h3>
                                                            <p class="f-light"></p>
                                                            <div class="row g-3">
                                                                <div class="col-sm-4">
                                                                    <label class="form-label" for="custid">ID
                                                                        Supplier</span></label>
                                                                    <input class="form-control" id="custid" type="text"
                                                                        value="<?php echo $row["SupplierNum"]; ?>"
                                                                        readonly>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <label class="form-label"
                                                                        for="custname">Nama</label>
                                                                    <input class="form-control" id="custname"
                                                                        type="text"
                                                                        value="<?php echo $row["SupplierName"]; ?>"
                                                                        readonly>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <label class="form-label"
                                                                        for="shipment">Alamat</label>
                                                                    <textarea class="form-control" id="shipment"
                                                                        rows="3"
                                                                        readonly><?php echo $row["SupplierAdd"]; ?></textarea>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <label class="form-label" for="npwp">Contact
                                                                        Person</label>
                                                                    <input class="form-control" id="npwp" type="text"
                                                                        value="<?php echo $row["ContactName"]; ?>"
                                                                        readonly>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <label class="form-label" for="nohp">No. HP</label>
                                                                    <input class="form-control" id="nohp" type="text"
                                                                        value="<?php echo $row["ContactPhone"]; ?>"
                                                                        readonly>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <label class="form-label" for="email">Tel.
                                                                        Kantor</label>
                                                                    <input class="form-control" id="email" type="text"
                                                                        value="<?php echo $row["Telepon"]; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-8">
                                                    <div class="shipping-info">
                                                        <h5><i class="fa fa-table"></i> Detail Pembelian</h5>
                                                    </div>
                                                    <div class="overflow-auto">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Barang</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Satuan</th>
                                                                    <th>Harga (exclude)</th>
                                                                    <th>DPP</th>
                                                                    <th class="text-end">Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if ($row["CategoryCD"] == "BB") {
                                                                    $total = 0;
                                                                    $queryd = "SELECT r.ItemCD, m.MaterialName, r.Quantity, r.UnitCD, r.Price, r.DPP, r.Subtotal
                                                                    FROM importreceptioninvoicedetail r, material m
                                                                    WHERE r.RCV_InvoiceID='" . $row["RCV_InvoiceID"] . "'
                                                                            AND r.ItemCD=m.MaterialCD";
                                                                    $resultd = mysqli_query($conn, $queryd);
                                                                    while ($rowd = mysqli_fetch_array($resultd)) {
                                                                        echo '<tr>
                                                                      <td>' . $rowd["MaterialName"] . '<input type="hidden" class="form-control tb-label f-14" name="items[]" value="' . $rowd["ItemCD"] . '"></td>
                                                                      <td><input type="number" class="form-control tb-label f-14" style="border-width:0" name="quantity[]" value="' . $rowd["Quantity"] . '"readonly></td>
                                                                      <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="unit[]" value="' . $rowd["UnitCD"] . '"readonly></td>
                                                                      <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="price[]" value="' . number_format($rowd["Price"], 0, '.', ',') . '"readonly></td>
                                                                      <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="dpp[]" value="' . number_format($rowd["DPP"], 0, '.', ',') . '"readonly></td>
                                                                      <td><input type="text" class="form-control tb-label f-14 text-end" style="border-width:0" name="subtotal[]" value="' . number_format($rowd["Subtotal"], 0, '.', ',') . '"readonly></td>
                                                                      </tr>';
                                                                    }
                                                                }/*else if($row["CategoryCD"] == "BPP"){
                                                                       $total = 0;
                                                                       $queryd = "SELECT r.ItemCD, s.GoodsName, r.Quantity, r.UnitCD, r.Price, r.DPP, r.Subtotal
                                                                               FROM importreceptioninvoicedetail r, supportinggoods s
                                                                               WHERE r.RCV_InvoiceID='".$row["RCV_InvoiceID"]."'
                                                                                       AND r.ItemCD=s.GoodsCD";
                                                                       $resultd = mysqli_query($conn,$queryd);
                                                                       while ($rowd = mysqli_fetch_array($resultd)) 
                                                                       {
                                                                           echo '<tr>
                                                                                   <td>'.$rowd["GoodsName"].'<input type="hidden" class="form-control tb-label f-14" name="items[]" value="'.$rowd["ItemCD"].'"></td>
                                                                                   <td><input type="number" class="form-control tb-label f-14" style="border-width:0" name="quantity[]" value="'.$rowd["Quantity"].'"readonly></td>
                                                                                   <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="unit[]" value="'.$rowd["UnitCD"].'"readonly></td>
                                                                                   <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="price[]" value="'.number_format($rowd["Price"],0,'.',',').'"readonly></td>
                                                                                   <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="dpp[]" value="'.number_format($rowd["DPP"],0,'.',',').'"readonly></td>
                                                                                   <td><input type="text" class="form-control tb-label f-14 text-end" style="border-width:0" name="subtotal[]" value="'.number_format($rowd["Subtotal"],0,'.',',').'"readonly></td>
                                                                                   </tr>';
                                                                       }
                                                                   }else if($row["CategoryCD"] == "SPR"){
                                                                       $total = 0;
                                                                       $queryd = "SELECT r.ItemCD, s.PartName, r.Quantity, r.UnitCD, r.Price, r.DPP, r.Subtotal
                                                                               FROM importreceptioninvoicedetail r, sparepart s
                                                                               WHERE r.RCV_InvoiceID='".$row["RCV_InvoiceID"]."'
                                                                                       AND r.ItemCD=s.PartCD";
                                                                       $resultd = mysqli_query($conn,$queryd);
                                                                       while ($rowd = mysqli_fetch_array($resultd)) 
                                                                       {
                                                                           echo '<tr>
                                                                                   <td>'.$rowd["PartName"].'<input type="hidden" class="form-control tb-label f-14" name="items[]" value="'.$rowd["ItemCD"].'"></td>
                                                                                   <td><input type="number" class="form-control tb-label f-14" style="border-width:0" name="quantity[]" value="'.$rowd["Quantity"].'"readonly></td>
                                                                                   <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="unit[]" value="'.$rowd["UnitCD"].'"readonly></td>
                                                                                   <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="price[]" value="'.number_format($rowd["Price"],0,'.',',').'"readonly></td>
                                                                                   <td><input type="text" class="form-control tb-label f-14" style="border-width:0" name="dpp[]" value="'.number_format($rowd["DPP"],0,'.',',').'"readonly></td>
                                                                                   <td><input type="text" class="form-control tb-label f-14 text-end" style="border-width:0" name="subtotal[]" value="'.number_format($rowd["Subtotal"],0,'.',',').'"readonly></td>
                                                                                   </tr>';
                                                                       }
                                                                   }*/
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="5"></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5">BM (5%) :</td>
                                                                    <td class="text-end">
                                                                        <?php echo number_format($row["BM"], 0, '.', ','); ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5">PPN (11%) :</td>
                                                                    <td class="text-end">
                                                                        <?php echo number_format($row["PPN"], 0, '.', ','); ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5">PPh (2,5%) :</td>
                                                                    <td class="text-end">
                                                                        <?php echo number_format($row["PPH"], 0, '.', ','); ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5">Total :</td>
                                                                    <td class="text-end">
                                                                        <?php echo number_format($row["TotalAmount"], 0, '.', ','); ?>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <h3>Biaya Lain-Lain</h3>
                                                        <br><br>
                                                        <?php
                                                        include "../DBConnection.php";
                                                        if (isset($_GET['rcvinvid']) && !empty($_GET['rcvinvid'])) {
                                                            $rcvinvid = $_GET['rcvinvid'];
                                                        } else {
                                                            die("Error: Parameter 'rcvinvid' tidak ditemukan di URL.");
                                                        }
                                                        $query = "SELECT Credit, AccountCD FROM journaldata WHERE Notes = '$rcvinvid'";
                                                        $result = mysqli_query($conn, $query);
                                                        $biayaData = [];
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $biayaData[] = [
                                                                'Credit' => $row['Credit'],
                                                                'AccountCD' => $row['AccountCD']
                                                            ];
                                                        }
                                                        $queryChart = "SELECT * FROM chartofaccount";
                                                        $resultChart = mysqli_query($conn, $queryChart);
                                                        ?>
                                                        <form method="POST"
                                                            action="../Process/createJournaldata.php?rcvinvid=<?php echo $rcvinvid; ?>">
                                                            <?php
                                                            for ($i = 0; $i < 5; $i++) {
                                                                $biaya = isset($biayaData[$i]['Credit']) ? $biayaData[$i]['Credit'] : '';
                                                                $kodeakun = isset($biayaData[$i]['AccountCD']) ? $biayaData[$i]['AccountCD'] : '';
                                                                $readonly = ($biaya !== '' && $kodeakun !== '') ? 'readonly' : '';
                                                                $disabled = ($biaya !== '' && $kodeakun !== '') ? 'disabled' : '';
                                                                ?>
                                                                <div class="mb-3 row">
                                                                    <label class="col-sm-1">Biaya
                                                                        <?php echo $i + 1; ?></label>
                                                                    <div class="col-sm-3">
                                                                        <input class="form-control" type="text"
                                                                            name="biaya[]" value="<?php echo $biaya; ?>"
                                                                            <?php echo $readonly; ?>>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input class="form-control"
                                                                            list="kodeakunOptions<?php echo $i; ?>"
                                                                            name="kodeakun[]" placeholder="Kode Akun"
                                                                            value="<?php echo $kodeakun; ?>" <?php echo $readonly; ?>>
                                                                        <datalist id="kodeakunOptions<?php echo $i; ?>">
                                                                            <?php
                                                                            mysqli_data_seek($resultChart, 0);
                                                                            while ($row = mysqli_fetch_array($resultChart)) {
                                                                                echo '<option value="' . $row["AccountCD"] . '">' . $row["AccountName"] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </datalist>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <hr>
                                                            <div class="col-md-12">
                                                                <a class="btn btn-warning" href="invoicing.php">Back</a>
                                                                <button class="btn btn-primary" type="submit">Save
                                                                    All</button>
                                                            </div>
                                                        </form>
                                                        <br><br>
                                                        <label class="txt-danger">NB: Pelunasan dilakukan di halaman
                                                            Pelunasan</label>
                                                    </div>

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
    <script src="../../assets/js/dropzone/dropzone.js"></script>
    <script src="../../assets/js/dropzone/dropzone-script.js"></script>
    <script src="../../assets/js/form-validation-custom.js"></script>
    <script src="../../assets/js/height-equal.js"></script>
    <script src="../../assets/js/notify/bootstrap-notify.min.js"></script>
    <script src="../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/datatable/datatables/datatable.custom.js"></script>
    <script src="../../assets/js/tooltip-init.js"></script>
    <script src="../../assets/js/modalpage/validation-modal.js"></script>
    <script src="../../assets/js/dropzone/dropzone.js"></script>
    <script src="../../assets/js/dropzone/dropzone-script.js"></script>
    <script src="../../assets/js/filepond/filepond-plugin-image-preview.js"></script>
    <script src="../../assets/js/filepond/filepond-plugin-file-rename.js"></script>
    <script src="../../assets/js/filepond/filepond-plugin-image-transform.js"></script>
    <script src="../../assets/js/filepond/filepond.js"></script>
    <script src="../../assets/js/filepond/custom-filepond.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="../../assets/js/script.js"></script>
    <!-- Plugin used-->
</body>

</html>