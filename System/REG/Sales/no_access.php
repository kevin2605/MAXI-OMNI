<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    include "../DBConnection.php";
    ?>
    <<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
                        text: 'Anda tidak memiliki akses untuk Approve.',
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

                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                </div>
                            </div>
                            <!-- Container-fluid Ends-->

                        </div>
                    </div>
                </div>
            </div>
            <!-- footer start-->
        </div>
    </div>
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
    <script src="../../assets/js/cleave/cleave.min.js"></script>
    <script src="../../assets/js/cleave/custom-cleave.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="../../assets/js/script.js"></script>
    <!-- Plugin used-->
</body>

</html>