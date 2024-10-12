<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT dashboard FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['dashboard'], 'R') !== false || // Create
    strpos($row['dashboard'], 'R') !== false || // Read
    strpos($row['dashboard'], 'R') !== false || // Update
    strpos($row['dashboard'], 'R') !== false;  // Delete
  
  $accessDenied = !$hasCRUDAccess;
  ?>
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
    window.addEventListener('DOMContentLoaded', (event) => {
      const params = getQueryParams();
      if (params.error === 'access_denied') {
        Swal.fire({
          icon: 'error',
          title: 'Akses Ditolak',
          text: 'Anda tidak memiliki akses untuk mengubah dashboard .',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        });
      }
    });
  </script>
</head>
<style>
  .hidden {
    display: none;
  }

  .hiddenn {
    display: none;
  }
</style>

<body>
  <?php if ($accessDenied): ?>
    <script>
      window.addEventListener('DOMContentLoaded', (event) => {
        Swal.fire({
          icon: 'error',
          title: 'Akses Ditolak',
          text: 'Anda tidak memiliki akses.',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '../Dashboard/noaccess.php';
          }
        });
      });
    </script>
  <?php endif; ?>
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
                <h3>ECOMMERCE DASHBOARD</h3>
              </div>
              <div class="col-sm-6 p-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">
                      <svg class="stroke-icon">
                        <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid default-dashboard <?php echo $accessDenied ? 'hiddenn' : ''; ?>">
          <!-- Konten dashboard -->
          <div class="container-fluid ecommerce-dashboard">
            <div class="row bg-light p-4 mb-4">
              <div class="col-xl-2 col-xl-33 col-sm-6 box-col-4 ps-0">
                <div class="card mb-0 online-order">
                  <div class="card-header online-order">
                    <div class="d-flex">
                      <div class="order bg-light-primary"><span></span>
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#basket"></use>
                        </svg>
                      </div>
                      <div class="arrow-chart">
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#arrow-chart"></use>
                        </svg><span class="font-danger">-6.3%</span>
                      </div>
                    </div>
                    <div class="online"><span>Online Order</span>
                      <h2>16,2873</h2>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-2 col-xl-33 col-sm-6 box-col-4 pedding-sm">
                <div class="card mb-0 online-order">
                  <div class="card-header offline-order">
                    <div class="d-flex">
                      <div class="order bg-light-secondary"><span></span>
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#delivery"></use>
                        </svg>
                      </div>
                      <div class="arrow-chart">
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#arrow-chart-up"></use>
                        </svg><span class="font-success">+8.3%</span>
                      </div>
                    </div>
                    <div class="online"><span>Offline Order</span>
                      <h2>62,5461</h2>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-2 col-xl-33 col-sm-6 box-col-4 pedding-sm">
                <div class="card mb-0 online-order">
                  <div class="card-header revenue-order">
                    <div class="d-flex">
                      <div class="order bg-light-danger"><span></span>
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#increased"></use>
                        </svg>
                      </div>
                      <div class="arrow-chart">
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#arrow-chart"></use>
                        </svg><span class="font-danger">-3.5%</span>
                      </div>
                    </div>
                    <div class="online"><span>Total Revenue</span>
                      <h2>45,9561</h2>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-2 col-xl-40 col-sm-6 box-col-4">
                <div class="card mb-0 online-order">
                  <div class="card-header feedback-card">
                    <div class="d-flex">
                      <div class="order bg-light-success"><span></span>
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#feedback"></use>
                        </svg>
                      </div>
                      <div class="arrow-chart">
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#arrow-chart-up"></use>
                        </svg><span class="font-success">+2.4%</span>
                      </div>
                    </div>
                    <div class="online"><span>Feedback</span>
                      <h2>75,5653</h2>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-2 col-xl-40 col-sm-6 box-col-4">
                <div class="card mb-0 online-order">
                  <div class="card-header feedback-card">
                    <div class="d-flex">
                      <div class="order bg-light-success"><span></span>
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#feedback"></use>
                        </svg>
                      </div>
                      <div class="arrow-chart">
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#arrow-chart-up"></use>
                        </svg><span class="font-success">+2.4%</span>
                      </div>
                    </div>
                    <div class="online"><span>Feedback</span>
                      <h2>75,5653</h2>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-2 col-xl-40 col-sm-6 box-col-4">
                <div class="card mb-0 online-order">
                  <div class="card-header feedback-card">
                    <div class="d-flex">
                      <div class="order bg-light-success"><span></span>
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#feedback"></use>
                        </svg>
                      </div>
                      <div class="arrow-chart">
                        <svg>
                          <use href="../../../assets/svg/icon-sprite.svg#arrow-chart-up"></use>
                        </svg><span class="font-success">+2.4%</span>
                      </div>
                    </div>
                    <div class="online"><span>Feedback</span>
                      <h2>75,5653</h2>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row"> 
              <div class="col-xl-6 col-xl-100 box-col-12 proorder-xl-3">
                <div class="card">
                  <div class="card-header pb-0">
                    <div class="header-top">
                      <h4>Recent Order </h4>
                      <div class="dropdown icon-dropdown">
                        <button class="btn dropdown-toggle" id="userdropdown1" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown1"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a></div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body pt-0 recent">
                    <div class="table-responsive custom-scrollbar">
                      <table class="table display" id="product-order" style="width:100%">
                        <thead>
                          <tr>
                            <th>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="">
                                <label class="form-check-label"></label>
                              </div>
                            </th>
                            <th>Product Name</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="">
                                <label class="form-check-label"></label>
                              </div>
                            </td>
                            <td>
                              <div class="d-flex">
                                <div class="flex-shrink-0"><img src="../../../assets/images/dashboard-2/product/1.png" alt=""></div>
                                <div class="flex-grow-1 ms-2"><a href="list-products.html">
                                    <h6>Rocky Shoes </h6><span>#Gh3649K</span></a></div>
                              </div>
                            </td>
                            <td> 
                              <h6>Rocky Shoes </h6><span>White Crater</span>
                            </td>
                            <td>Oct 24, 2023</td>
                            <td> 
                              <button class="badge badge-light-primary rounded-pill txt-primary">Paid</button>
                            </td>
                            <td>$21.56</td>
                          </tr>
                          <tr>
                            <td>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="">
                                <label class="form-check-label"></label>
                              </div>
                            </td>
                            <td>
                              <div class="d-flex">
                                <div class="flex-shrink-0"><img src="../../../assets/images/dashboard-2/product/2.png" alt=""></div>
                                <div class="flex-grow-1 ms-2"><a href="list-products.html">
                                    <h6>iPhone 14 Pro</h6><span>#A5647KB</span></a></div>
                              </div>
                            </td>
                            <td> 
                              <h6>iPhone 14 Pro</h6><span>World Bandung</span>
                            </td>
                            <td>Nov 13, 2023</td>
                            <td> 
                              <button class="badge badge-light-secondary rounded-pill txt-secondary">Pending</button>
                            </td>
                            <td>$65.36</td>
                          </tr>
                          <tr>
                            <td>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="">
                                <label class="form-check-label"></label>
                              </div>
                            </td>
                            <td>
                              <div class="d-flex">
                                <div class="flex-shrink-0"><img src="../../../assets/images/dashboard-2/product/3.png" alt=""></div>
                                <div class="flex-grow-1 ms-2"><a href="list-products.html">
                                    <h6>Stylish Watches</h6><span>#KO093M</span></a></div>
                              </div>
                            </td>
                            <td> 
                              <h6>Stylish Watches</h6><span>Jalan Braga</span>
                            </td>
                            <td>Sep 16, 2023</td>
                            <td> 
                              <button class="badge badge-light-primary rounded-pill txt-primary">Paid</button>
                            </td>
                            <td>$95.48</td>
                          </tr>
                          <tr>
                            <td>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="">
                                <label class="form-check-label"></label>
                              </div>
                            </td>
                            <td>
                              <div class="d-flex">
                                <div class="flex-shrink-0"><img src="../../../assets/images/dashboard-2/product/4.png" alt=""></div>
                                <div class="flex-grow-1 ms-2"><a href="list-products.html">
                                    <h6>Laptop Backpack</h6><span>#KMG403</span></a></div>
                              </div>
                            </td>
                            <td> 
                              <h6>Rachel Green</h6><span>Gedung Sate</span>
                            </td>
                            <td>Dec 20, 2023</td>
                            <td> 
                              <button class="badge badge-light-danger rounded-pill txt-danger">Overdue</button>
                            </td>
                            <td>$95.78</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-xl-50 box-col-6 proorder-xl-2">
                <div class="card order-overview">
                  <div class="card-header pb-0">
                    <div class="header-top">
                      <h4>Order Overview</h4>
                      <div class="dropdown icon-dropdown">
                        <button class="btn dropdown-toggle" id="userdropdown4" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown4"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a></div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex">
                      <h2 class="me-2">($3,512,201)</h2>
                      <h6>Total Revenue</h6>
                    </div>
                    <div class="total-revenue">
                      <div class="d-flex">
                        <h5 class="me-2">40</h5><span>(Online Order)</span>
                      </div>
                      <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 50%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="total-revenue">
                      <div class="d-flex">
                        <h5 class="me-2">60</h5><span>(Offline Order)</span>
                      </div>
                      <div class="progress">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 70%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="total-revenue">
                      <div class="d-flex">
                        <h5 class="me-2">20</h5><span>(Cash On Develery)</span>
                      </div>
                      <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-xl-50 box-col-6 proorder-xl-2">
                <div class="card categories-chart">
                  <div class="card-header pb-0">
                    <div class="header-top">
                      <h4>Categories by Sales</h4>
                      <div class="dropdown icon-dropdown">
                        <button class="btn dropdown-toggle" id="userdropdown2" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown2"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a></div>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6 p-0">
                        <div id="Categories-chart"></div>
                      </div>
                      <div class="col-6 categories-sales">
                        <div class="d-flex gap-2"> 
                          <div class="flex-shrink-0"><span class="bg-primary"> </span></div>
                          <div class="flex-grow-1"> 
                            <h6>Income</h6>
                          </div>
                          <h5>$21,654</h5>
                        </div>
                        <div class="d-flex gap-2"> 
                          <div class="flex-shrink-0"><span class="bg-secondary"> </span></div>
                          <div class="flex-grow-1"> 
                            <h6>Visitors</h6>
                          </div>
                          <h5>$62,842</h5>
                        </div>
                        <div class="d-flex gap-2"> 
                          <div class="flex-shrink-0"><span class="bg-danger"> </span></div>
                          <div class="flex-grow-1"> 
                            <h6>Expense</h6>
                          </div>
                          <h5>$37,210</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="total-earn">
                      <h2>$3,512,201</h2>
                      <h6>Total Earned</h6>
                    </div>
                    <div class="earned" id="Earned-chart"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
          <!-- footer start-->
        </div>
      </div>
    </div>
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


        .container {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: right;
          height: 100vh;
        }

        img {
          max-width: 200px;
          margin-bottom: 0px;
        }

        .button {
          background-color: #4CAF50;
          border: none;
          color: white;
          padding: 0px 0px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
          margin: 0px 0px;
          cursor: pointer;
          border-radius: 0px;
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
    <!-- Apex Chart JS-->
    <script src="../../../assets/js/chart/apex-chart/apex-chart.js"></script>
    <script src="../../../assets/js/chart/apex-chart/stock-prices.js"></script>
    <script src="../../../assets/js/chart/apex-chart/chart-custom.js"></script>

    <script src="../../../assets/js/notify/bootstrap-notify.min.js"></script>
    <script src="../../../assets/js/dashboard/default.js"></script>
    <script src="../../../assets/js/notify/index.js"></script>
    <script src="../../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../assets/js/datatable/datatables/datatable.custom.js"></script>
    <script src="../../../assets/js/datatable/datatables/datatable.custom1.js"></script>
    <script src="../../../assets/js/owlcarousel/owl.carousel.js"></script>
    <script src="../../../assets/js/owlcarousel/owl-custom.js"></script>
    <script src="../../../assets/js/typeahead/handlebars.js"></script>
    <script src="../../../assets/js/typeahead/typeahead.bundle.js"></script>
    <script src="../../../assets/js/typeahead/typeahead.custom.js"></script>
    <script src="../../../assets/js/typeahead-search/handlebars.js"></script>
    <script src="../../../assets/js/typeahead-search/typeahead-custom.js"></script>
    <script src="../../../assets/js/height-equal.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="../../../assets/js/script.js"></script>

    <!-- Plugin used-->
</body>

</html>