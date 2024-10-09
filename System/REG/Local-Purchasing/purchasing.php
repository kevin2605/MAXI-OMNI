<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../headcontent.php"; ?>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Function to handle URL parameters
        function getQueryParams() {
            const query = window.location.search.substring(1);
            const params = new URLSearchParams(query);
            return {
                error: params.get('error')
            };
        }

        // Check URL parameters and show alert if needed
        window.addEventListener('DOMContentLoaded', (event) => {
            const params = getQueryParams();

            if (params.error === 'access_denied') {
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: 'Anda tidak memiliki akses untuk ubah data penerimaan barang.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
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
                        <div class="row">
                            <div class="col-sm-6 ps-0">
                                <h3>PEMBELIAN (LOKAL)</h3>
                            </div>
                            <div class="col-sm-6 pe-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">
                                            <svg class="stroke-icon">
                                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Pembelian (Lokal)</li>
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
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                        data-bs-target=".bd-example-modal-lg"><i class="fa fa-plus-circle"></i> New
                                        PO</button>
                                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                                        aria-labelledby="myExtraLargeModal" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myExtraLargeModal">Purchase Order Baru
                                                    </h4>
                                                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body dark-modal">
                                                    <div class="card-body custom-input">
                                                        <form class="row g-3">
                                                            <div class="col-4">
                                                                <label class="form-label" for="first-name">Purchase
                                                                    Order ID</label>
                                                                <input class="form-control" id="first-name" type="text"
                                                                    placeholder="auto-generated" aria-label="First name"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="form-label"
                                                                    for="exampleFormControlInput1">Tanggal Order</label>
                                                                <input class="form-control"
                                                                    id="exampleFormControlInput1" type="date"
                                                                    value="<?php echo date('Y-m-d'); ?>" readonly>
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="form-label"
                                                                    for="exampleFormControlInput1">Tanggal Kirim</label>
                                                                <input class="form-control"
                                                                    id="exampleFormControlInput1" type="date" required>
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="form-label" for="inputPassword2">Pembuat
                                                                    PO</label>
                                                                <input class="form-control" id="inputPassword2"
                                                                    type="text" placeholder="01 - Kevin" required>
                                                            </div>
                                                            <div class="col-8">
                                                                <label class="form-label"
                                                                    for="exampleDataList">Supplier</label>
                                                                <input class="form-control" id="exampleDataList"
                                                                    list="datalistOptions" placeholder="supplier"
                                                                    required>
                                                                <datalist id="datalistOptions">
                                                                    <option value="San Francisco"></option>
                                                                    <option value="New York"></option>
                                                                    <option value="Seattle"></option>
                                                                    <option value="Los Angeles"></option>
                                                                    <option value="Chicago"></option>
                                                                    <option value="India"> </option>
                                                                </datalist>
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="form-label"
                                                                    for="exampleDataList">Termin</label>
                                                                <input class="form-control" id="exampleDataList"
                                                                    list="datalistOptions" placeholder="Satuan"
                                                                    required>
                                                                <datalist id="datalistOptions">
                                                                    <option value="5"></option>
                                                                    <option value="10"></option>
                                                                    <option value="15"></option>
                                                                    <option value="20"></option>
                                                                    <option value="25"></option>
                                                                    <option value="30"> </option>
                                                                </datalist>
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="form-label"
                                                                    for="exampleFormControlInput1">Alamat
                                                                    Pengiriman</label>
                                                                <input class="form-control"
                                                                    id="exampleFormControlInput1" type="text" readonly>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label"
                                                                    for="inputPassword2">Keterangan</label>
                                                                <input class="form-control" id="inputPassword2"
                                                                    type="text" placeholder="..." required>
                                                            </div>
                                                            <hr>
                                                            <div class="d-flex pb-0">
                                                                <h3>Detil Order</h3>
                                                                <button id="add_item"
                                                                    class="btn btn-success btn-sm b-r-7"
                                                                    style="position:absolute; right:5px;">
                                                                    <i class="fa fa-plus-circle"></i> Produk
                                                                </button>
                                                            </div>
                                                            <table id="dinamis" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">Barang</th>
                                                                        <th scope="col">Harga</th>
                                                                        <th scope="col">Jumlah</th>
                                                                        <th scope="col">Satuan</th>
                                                                        <th scope="col">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="dbody">
                                                                    <!-- table content -->
                                                                </tbody>
                                                            </table>
                                                            <hr>
                                                            <div class="col-12">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input"
                                                                        id="flexSwitchCheckDefault" type="checkbox"
                                                                        role="switch" required>
                                                                    <label class="form-check-label"
                                                                        for="flexSwitchCheckDefault">Apakah informasi
                                                                        diatas sudah benar?</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <button class="btn btn-primary"
                                                                    type="submit">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                        data-bs-target=".bd-example-modal-lg1"><i class="fa fa-plus-circle"></i> New
                                        Penerimaan</button>
                                    <div class="modal fade bd-example-modal-lg1" tabindex="-1" role="dialog"
                                        aria-labelledby="myExtraLargeModal" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myExtraLargeModal">Form Penerimaan
                                                        Barang</h4>
                                                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body dark-modal">
                                                    <div class="card-body custom-input">
                                                        <form class="row g-3">
                                                            <div class="col-6">
                                                                <label class="form-label" for="exampleDataList">Pilih
                                                                    Purchase Order</label>
                                                                <input class="form-control" id="exampleDataList"
                                                                    list="datalistOptions" placeholder="Satuan"
                                                                    required>
                                                                <datalist id="datalistOptions">
                                                                    <option value="San Francisco"></option>
                                                                    <option value="New York"></option>
                                                                    <option value="Seattle"></option>
                                                                    <option value="Los Angeles"></option>
                                                                    <option value="Chicago"></option>
                                                                    <option value="India"> </option>
                                                                </datalist>
                                                            </div>
                                                            <div class="col-3">
                                                                <label class="form-label" for="buttonGen">.</label>
                                                                <button class="form-control btn btn-primary"
                                                                    id="buttonGen">Generate</button>
                                                            </div>
                                                            <div class="col-3">
                                                                <label class="form-label"
                                                                    for="exampleFormControlInput1">Tanggal</label>
                                                                <input class="form-control"
                                                                    id="exampleFormControlInput1" type="date"
                                                                    value="<?php echo date('Y-m-d'); ?>" readonly>
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="form-label"
                                                                    for="exampleDataList">Jurnal</label>
                                                                <input class="form-control" id="exampleDataList"
                                                                    list="datalistOptions" placeholder="Satuan"
                                                                    required>
                                                                <datalist id="datalistOptions">
                                                                    <option value="San Francisco"></option>
                                                                    <option value="New York"></option>
                                                                    <option value="Seattle"></option>
                                                                    <option value="Los Angeles"></option>
                                                                    <option value="Chicago"></option>
                                                                    <option value="India"> </option>
                                                                </datalist>
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="form-label" for="exampleDataList">Tipe
                                                                    Pembayaran</label>
                                                                <input class="form-control" id="exampleDataList"
                                                                    list="datalistOptions" placeholder="Satuan"
                                                                    required>
                                                                <datalist id="datalistOptions">
                                                                    <option value="San Francisco"></option>
                                                                    <option value="New York"></option>
                                                                    <option value="Seattle"></option>
                                                                    <option value="Los Angeles"></option>
                                                                    <option value="Chicago"></option>
                                                                    <option value="India"> </option>
                                                                </datalist>
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="form-label"
                                                                    for="exampleDataList">Gudang</label>
                                                                <input class="form-control" id="exampleDataList"
                                                                    list="datalistOptions" placeholder="Satuan"
                                                                    required>
                                                                <datalist id="datalistOptions">
                                                                    <option value="San Francisco"></option>
                                                                    <option value="New York"></option>
                                                                    <option value="Seattle"></option>
                                                                    <option value="Los Angeles"></option>
                                                                    <option value="Chicago"></option>
                                                                    <option value="India"> </option>
                                                                </datalist>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label"
                                                                    for="exampleFormControlInput1">Keterangan</label>
                                                                <input class="form-control"
                                                                    id="exampleFormControlInput1" type="text">
                                                            </div>
                                                            <hr>
                                                            <div class="col-6">
                                                                <label class="form-label"
                                                                    for="exampleFormControlInput1">No. Surat Jalan
                                                                    (optional)</label>
                                                                <input class="form-control"
                                                                    id="exampleFormControlInput1" type="text">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label"
                                                                    for="exampleFormControlInput1">No. Invoice
                                                                    (optional)</label>
                                                                <input class="form-control"
                                                                    id="exampleFormControlInput1" type="text">
                                                            </div>
                                                            <hr>
                                                            <h3>Detil Produk</h3>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">Barang</th>
                                                                        <th scope="col">Harga</th>
                                                                        <th scope="col">Jumlah</th>
                                                                        <th scope="col">Satuan</th>
                                                                        <th scope="col">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- table content -->
                                                                </tbody>
                                                            </table>
                                                            <hr>
                                                            <div class="col-12">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input"
                                                                        id="flexSwitchCheckDefault" type="checkbox"
                                                                        role="switch" required>
                                                                    <label class="form-check-label"
                                                                        for="flexSwitchCheckDefault">Apakah informasi
                                                                        diatas sudah benar?</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <button class="btn btn-primary"
                                                                    type="submit">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">Menu</button>
                                    <ul class="dropdown-menu dropdown-block" id="myTab" role="tablist">
                                        <li class="nav-item"><a class="dropdown-item active txt-primary f-w-500 f-18"
                                                id="home-tab" data-bs-toggle="tab" href="#daftarPO" role="tab"
                                                aria-controls="home" aria-selected="true">Daftar Purchase Order</a></li>
                                        <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18"
                                                id="profile-tabs" data-bs-toggle="tab" href="#penerimaanBarang"
                                                role="tab" aria-controls="profile" aria-selected="false">Penerimaan
                                                Barang</a></li>
                                        <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18"
                                                id="contact-tab" data-bs-toggle="tab" href="#invoicing" role="tab"
                                                aria-controls="contact" aria-selected="false">Invoicing Pembelian</a>
                                        </li>
                                        <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18"
                                                id="contact-tab" data-bs-toggle="tab" href="#settingNota" role="tab"
                                                aria-controls="contact" aria-selected="false">Setting Nota</a></li>
                                        <li class="nav-item"><a class="dropdown-item txt-primary f-w-500 f-18"
                                                id="contact-tab" data-bs-toggle="tab" href="#retur" role="tab"
                                                aria-controls="contact" aria-selected="false">Retur</a></li>
                                    </ul>
                                    <hr>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="daftarPO" role="tabpanel">
                                            <h3>Daftar Purchase Order</h3>
                                            <div class="table-responsive custom-scrollbar user-datatable">
                                                <table class="display" id="basic-12">
                                                    <thead>
                                                        <tr>
                                                            <th>Purchase Order ID</th>
                                                            <th>Tanggal PO</th>
                                                            <th>Supplier ID</th>
                                                            <th>Nama Supplier</th>
                                                            <th>Nominal</th>
                                                            <th>Dibuat</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>PO202405160005</td>
                                                            <td>2024-05-16</td>
                                                            <td>SUPP-0001</td>
                                                            <td>Tjiwi</td>
                                                            <td>143.502.139</td>
                                                            <td>FINANCE</td>
                                                            <td>
                                                                <ul class="action">
                                                                    <li class="edit"> <a href="#"><i
                                                                                class="icon-pencil-alt"></i></a></li>
                                                                    <li class="delete"><a href="#"><i
                                                                                class="icon-trash"></i></a></li>
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>PO202405160005</td>
                                                            <td>2024-05-16</td>
                                                            <td>SUPP-0003</td>
                                                            <td>PT Sentosa Jaya Selalu</td>
                                                            <td>54.391.595</td>
                                                            <td>FINANCE</td>
                                                            <td>
                                                                <ul class="action">
                                                                    <li class="edit"> <a href="#"><i
                                                                                class="icon-pencil-alt"></i></a></li>
                                                                    <li class="delete"><a href="#"><i
                                                                                class="icon-trash"></i></a></li>
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="penerimaanBarang" role="tabpanel">
                                            <h3>Daftar Penerimaan</h3>
                                            <div class="table-responsive custom-scrollbar user-datatable">
                                                <table class="display" id="basic-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Position</th>
                                                            <th>Office</th>
                                                            <th>Age</th>
                                                            <th>Start date</th>
                                                            <th>Salary</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td> <img class="img-fluid table-avtar"
                                                                    src="../../assets/images/user/1.jpg" alt="">Tiger
                                                                Nixon</td>
                                                            <td>System Architect</td>
                                                            <td>Edinburgh</td>
                                                            <td>61</td>
                                                            <td>2011/04/25</td>
                                                            <td>$320,800</td>
                                                            <td>
                                                                <ul class="action">
                                                                    <li class="edit"> <a href="#"><i
                                                                                class="icon-pencil-alt"></i></a></li>
                                                                    <li class="delete"><a href="#"><i
                                                                                class="icon-trash"></i></a></li>
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="invoicing" role="tabpanel">
                                            <h3>Invoicing Pembelian</h3>
                                        </div>
                                        <div class="tab-pane fade" id="settingNota" role="tabpanel">
                                            <h3>Setting Nota</h3>
                                        </div>
                                        <div class="tab-pane fade" id="retur" role="tabpanel">
                                            <h3>Retur Pembelian</h3>
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
    <!-- DYNAMIC TABLE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script>
        $("document").ready(function () {
            var i = 0;
            var x = 0;
            $('#add_item').click(function () {
                i++;
                x++;
                $('#dinamis #dbody').append('<tr id="row' + i + '"><td><input type="text" class="form-control" name="products[]" list="namelist"><datalist id="namelist" style="width:3rem;"><option value="PC4">Paper Cup 4 oz</option><option value="PC7">Paper Cup 7 oz</option></datalist></td><td><input type="text" class="form-control" name="prices[]" placeholder="0"></td><td><input type="text" class="form-control" name="quantities[]" placeholder="0"></td><td><input type="text" class="form-control" name="discs[]" placeholder="0"></td><td><button id="' + i + '" type="button" class="btn btn-danger bremove"><i class="icofont icofont-close-line-circled"></i></button></td></tr>');
            });

            $(document).on('click', '.bremove', function () {
                x--;
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
    </script>
    <!-- Theme js-->
    <script src="../../assets/js/script.js"></script>
    <!-- Plugin used-->
</body>

</html>