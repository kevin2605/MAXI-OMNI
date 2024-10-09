<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include "../headcontent.php";
    session_start();
    include "../DBConnection.php";


    ?>

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
                            <div class="col-sm-6 p-0">
                                <h3>DASHBOARD</h3>
                            </div>
                            <div class="col-sm-6 p-0">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">
                                            <svg class="stroke-icon">
                                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                                            </svg></a></li>
                                    <li class="breadcrumb-item">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container "
                    style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; text-align: center; transform: translateY(-100px);">
                    <img src="no_access.PNG" alt="Ikon Akses Ditolak" style="max-width: 100%; height: auto;">
                    <h1>Oops, kamu tidak punya izin untuk mengakses halaman Dashboard</h1>
                    <p>Hubungi Admin untuk bisa mengakses halaman ini, ya.</p>
                </div>
                <div class="container-fluid default-dashboard ">
                    <!-- Konten dashboard -->

                    <!-- Container-fluid Ends-->
                    <!-- footer start-->
                    <footer class="footer">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 p-0 footer-copyright">
                                    <p class="mb-0">Copyright 2024 © MAXI.</p>
                                </div>
                                <div class="col-md-6 p-0">
                                    <p class="heart mb-0">Business System and Information
                                    </p>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
        <style>
            .bell-icon {
                fill: gray;
                /* Warna abu-abu untuk ikon */
                transition: transform 0.3s ease;
            }

            @keyframes ringBell {
                0% {
                    transform: rotate(0deg);
                }

                25% {
                    transform: rotate(10deg);
                }

                50% {
                    transform: rotate(-10deg);
                }

                75% {
                    transform: rotate(5deg);
                }

                100% {
                    transform: rotate(0deg);
                }
            }

            .bell-icon.ringing {
                animation: ringBell 1s infinite;

                body {
                    font-family: sans-serif;
                    text-align: center;
                }

                .container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: right;
                    height: 100vh;
                }

                img {
                    max-width: 200px;
                    margin-bottom: 20px;
                }

                .button {
                    background-color: #4CAF50;
                    border: none;
                    color: white;
                    padding: 10px 20px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 4px 2px;
                    cursor: pointer;
                    border-radius: 4px;
                }
            }
        </style>
        <script>
            document.querySelector('.bell-icon').addEventListener('mouseenter', function () {
                this.classList.add('ringing');
            });
            document.querySelector('.bell-icon').addEventListener('mouseleave', function () {
                this.classList.remove('ringing');
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
        <!-- Apex Chart JS-->
        <script src="../../assets/js/chart/apex-chart/apex-chart.js"></script>
        <script src="../../assets/js/chart/apex-chart/stock-prices.js"></script>
        <script src="../../assets/js/chart/apex-chart/chart-custom.js"></script>

        <script src="../../assets/js/notify/bootstrap-notify.min.js"></script>
        <script src="../../assets/js/dashboard/default.js"></script>
        <script src="../../assets/js/notify/index.js"></script>
        <script src="../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
        <script src="../../assets/js/datatable/datatables/datatable.custom.js"></script>
        <script src="../../assets/js/datatable/datatables/datatable.custom1.js"></script>
        <script src="../../assets/js/owlcarousel/owl.carousel.js"></script>
        <script src="../../assets/js/owlcarousel/owl-custom.js"></script>
        <script src="../../assets/js/typeahead/handlebars.js"></script>
        <script src="../../assets/js/typeahead/typeahead.bundle.js"></script>
        <script src="../../assets/js/typeahead/typeahead.custom.js"></script>
        <script src="../../assets/js/typeahead-search/handlebars.js"></script>
        <script src="../../assets/js/typeahead-search/typeahead-custom.js"></script>
        <script src="../../assets/js/height-equal.js"></script>
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="../../assets/js/script.js"></script>
        <!-- Plugin used-->
</body>

</html>