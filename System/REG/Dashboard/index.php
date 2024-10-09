<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
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
        <div class="container-fluid default-dashboard <?php echo $accessDenied ? 'hiddenn' : ''; ?>">
          <!-- Konten dashboard -->
          <div class="row">
            <div class="col-xxl-4 col-xl-100 box-col-12 ps-4 pe-4 left-background">
              <div class="row bg-light h-55 p-3 pt-4 pb-4">
                <div class="col-12 col-xl-50 box-col-6">
                  <div class="row">
                    <div class="col-12 col-lg-12 col-md-6 box-col-12">
                      <div class="card total-earning" id="show-sales">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-7 box-col-7">
                              <div class="d-flex">
                                <h3 class="font-primary">Total Sales</h3>
                              </div>
                              <?php
                              if ($conn->connect_error) {
                                die("Koneksi gagal: " . $conn->connect_error);
                              }
                              $sql = "SELECT SUM(Price * Quantity) AS totalQuantity FROM salesorderdetail";
                              $result = $conn->query($sql);
                              $totalQuantity = 0;
                              if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $totalQuantity = $row['totalQuantity'];
                              } else {
                                $totalQuantity = 0;
                              }
                              $sqlThisMonth = "SELECT SUM(Price * Quantity) AS totalQuantityThisMonth FROM salesorderdetail 
                              WHERE YEAR(CreatedOn) = YEAR(CURDATE()) 
                              AND MONTH(CreatedOn) = MONTH(CURDATE())";
                              $resultThisMonth = $conn->query($sqlThisMonth);
                              $totalQuantityThisMonth = 0;
                              if ($resultThisMonth->num_rows > 0) {
                                $rowThisMonth = $resultThisMonth->fetch_assoc();
                                $totalQuantityThisMonth = $rowThisMonth['totalQuantityThisMonth'];
                              } else {
                                $totalQuantityThisMonth = 0;
                              }
                              $sqlLastMonth = "SELECT SUM(Price * Quantity) AS totalQuantityLastMonth FROM salesorderdetail 
                              WHERE YEAR(CreatedOn) = YEAR(CURDATE() - INTERVAL 1 MONTH) 
                              AND MONTH(CreatedOn) = MONTH(CURDATE() - INTERVAL 1 MONTH)";
                              $resultLastMonth = $conn->query($sqlLastMonth);
                              $totalQuantityLastMonth = 0;
                              if ($resultLastMonth->num_rows > 0) {
                                $rowLastMonth = $resultLastMonth->fetch_assoc();
                                $totalQuantityLastMonth = $rowLastMonth['totalQuantityLastMonth'];
                              } else {
                                $totalQuantityLastMonth = 0;
                              }
                              $percentageChange = 0;
                              if ($totalQuantityLastMonth > 0) {
                                $percentageChange = (($totalQuantityThisMonth - $totalQuantityLastMonth) / $totalQuantityLastMonth) * 100;
                              }
                              ?>
                              <h5>Rp.<?php echo number_format($totalQuantity, 0); ?> </h5>
                              <span><?php echo number_format($percentageChange, 0); ?>% than last month</span>
                            </div>
                            <div class="col-sm-5 box-col-5 p-0">
                              <div>
                                <svg width="1in" height="1in" viewBox="0 0 600 450" style="display:block;margin:auto;">
                                  <?php if ($percentageChange < 0): ?>
                                    <use href="../../assets/svg/icon-sprite.svg#arrow-chart"></use>
                                  <?php else: ?>
                                    <use href="../../assets/svg/icon-sprite.svg#arrow-chart-up"></use>
                                  <?php endif; ?>
                                </svg>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-lg-12 col-md-6 box-col-12">
                      <div class="card total-earning" id="show-purchasing">
                        <div class="card-body pb-0">
                          <div class="row">
                            <div class="col-sm-7 box-col-7">
                              <div class="d-flex">
                                <h3 class="font-primary">Total Purchasing</h3>
                              </div>
                              <?php
                              if ($conn->connect_error) {
                                die("Koneksi gagal: " . $conn->connect_error);
                              }
                              $sqlImportPurchase = "SELECT SUM(TotalPurchase) AS totalImportPurchase FROM importpurchaseorderheader";
                              $resultImportPurchase = $conn->query($sqlImportPurchase);
                              $totalImportPurchase = 0;
                              if ($resultImportPurchase->num_rows > 0) {
                                $rowImportPurchase = $resultImportPurchase->fetch_assoc();
                                $totalImportPurchase = $rowImportPurchase['totalImportPurchase'];
                              }
                              $sqlPurchaseOrder = "SELECT SUM(TotalPurchase) AS totalPurchaseOrder FROM purchaseorderheader";
                              $resultPurchaseOrder = $conn->query($sqlPurchaseOrder);
                              $totalPurchaseOrder = 0;
                              if ($resultPurchaseOrder->num_rows > 0) {
                                $rowPurchaseOrder = $resultPurchaseOrder->fetch_assoc();
                                $totalPurchaseOrder = $rowPurchaseOrder['totalPurchaseOrder'];
                              }
                              $totalPurchase = $totalImportPurchase + $totalPurchaseOrder;
                              $sqlThisMonthImport = "SELECT SUM(TotalPurchase) AS totalImportPurchaseThisMonth 
                              FROM importpurchaseorderheader 
                              WHERE YEAR(CreatedOn) = YEAR(CURDATE()) 
                              AND MONTH(CreatedOn) = MONTH(CURDATE())";
                              $resultThisMonthImport = $conn->query($sqlThisMonthImport);
                              $totalImportPurchaseThisMonth = 0;
                              if ($resultThisMonthImport->num_rows > 0) {
                                $rowThisMonthImport = $resultThisMonthImport->fetch_assoc();
                                $totalImportPurchaseThisMonth = $rowThisMonthImport['totalImportPurchaseThisMonth'];
                              }
                              $sqlThisMonthPurchaseOrder = "SELECT SUM(TotalPurchase) AS totalPurchaseOrderThisMonth 
                                FROM purchaseorderheader 
                                WHERE YEAR(CreatedOn) = YEAR(CURDATE()) 
                                AND MONTH(CreatedOn) = MONTH(CURDATE())";
                              $resultThisMonthPurchaseOrder = $conn->query($sqlThisMonthPurchaseOrder);
                              $totalPurchaseOrderThisMonth = 0;
                              if ($resultThisMonthPurchaseOrder->num_rows > 0) {
                                $rowThisMonthPurchaseOrder = $resultThisMonthPurchaseOrder->fetch_assoc();
                                $totalPurchaseOrderThisMonth = $rowThisMonthPurchaseOrder['totalPurchaseOrderThisMonth'];
                              }
                              $totalPurchaseThisMonth = $totalImportPurchaseThisMonth + $totalPurchaseOrderThisMonth;
                              $sqlLastMonthImport = "SELECT SUM(TotalPurchase) AS totalImportPurchaseLastMonth 
                                FROM importpurchaseorderheader 
                                WHERE YEAR(CreatedOn) = YEAR(CURDATE() - INTERVAL 1 MONTH) 
                                AND MONTH(CreatedOn) = MONTH(CURDATE() - INTERVAL 1 MONTH)";
                              $resultLastMonthImport = $conn->query($sqlLastMonthImport);
                              $totalImportPurchaseLastMonth = 0;
                              if ($resultLastMonthImport->num_rows > 0) {
                                $rowLastMonthImport = $resultLastMonthImport->fetch_assoc();
                                $totalImportPurchaseLastMonth = $rowLastMonthImport['totalImportPurchaseLastMonth'];
                              }
                              $sqlLastMonthPurchaseOrder = "SELECT SUM(TotalPurchase) AS totalPurchaseOrderLastMonth 
                                FROM purchaseorderheader 
                                WHERE YEAR(CreatedOn) = YEAR(CURDATE() - INTERVAL 1 MONTH) 
                                AND MONTH(CreatedOn) = MONTH(CURDATE() - INTERVAL 1 MONTH)";
                              $resultLastMonthPurchaseOrder = $conn->query($sqlLastMonthPurchaseOrder);
                              $totalPurchaseOrderLastMonth = 0;
                              if ($resultLastMonthPurchaseOrder->num_rows > 0) {
                                $rowLastMonthPurchaseOrder = $resultLastMonthPurchaseOrder->fetch_assoc();
                                $totalPurchaseOrderLastMonth = $rowLastMonthPurchaseOrder['totalPurchaseOrderLastMonth'];
                              }
                              $totalPurchaseLastMonth = $totalImportPurchaseLastMonth + $totalPurchaseOrderLastMonth;
                              $percentageChange = 0;
                              if ($totalPurchaseLastMonth > 0) {
                                $percentageChange = (($totalPurchaseThisMonth - $totalPurchaseLastMonth) / $totalPurchaseLastMonth) * 100;
                              }
                              ?>
                              <h5>Rp.<?php echo number_format($totalPurchase, 0); ?> </h5>
                              <span><?php echo number_format($percentageChange, 0); ?>% than last month</span>
                            </div>
                            <div class="col-sm-5 box-col-5 p-0">
                              <div>
                                <svg width="1in" height="1in" viewBox="0 0 600 450" style="display:block;margin:auto;">
                                  <use href="../../assets/svg/icon-sprite.svg#arrow-chart"></use>
                                </svg>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <hr style="border: 1px solid #000; width: 86%; margin-left: 4%; margin-bottom: 7%;">
                    <?php
                    $query = "SELECT SUM(TotalInvoice - DPAmount) AS TotalPiutang FROM invoiceheader";
                    $result = mysqli_query($conn, $query);
                    $totalPiutang = 0;
                    if ($result) {
                      $row = mysqli_fetch_assoc($result);
                      $totalPiutang = $row['TotalPiutang'];
                    } else {
                      die("Error: Gagal menghitung total piutang.");
                    }
                    ?>
                    <div class="col-12 col-md-12">
                      <div class="card since">
                        <div class="card-body invoice-profit">
                          <div class="customer-card d-flex b-l-success border-2">
                            <div class="ms-3">
                              <h3 class="mt-1">Total Piutang</h3>
                              <h5 class="mt-1">Rp.<?php echo number_format($totalPiutang, 0, ',', '.'); ?></h5>
                            </div>
                            <div class="dashboard-user bg-light-success"><span></span>
                              <svg>
                                <use href="../../assets/svg/icon-sprite.svg#invoice"></use>
                              </svg>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                    $query = "SELECT SUM(TotalAmount) AS TotalHutang FROM receptioninvoiceheader WHERE Status = 0";
                    $result = mysqli_query($conn, $query);
                    $totalHutang = 0;
                    if ($result) {
                      $row = mysqli_fetch_assoc($result);
                      $totalHutang = $row['TotalHutang'];
                    } else {
                      die("Error: Gagal menghitung total hutang.");
                    }
                    ?>
                    <div class="col-12 col-md-12">
                      <div class="card since">
                        <div class="card-body profit">
                          <div class="customer-card d-flex b-l-danger border-2">
                            <div class="ms-3">
                              <h3 class="mt-1">Total Hutang</h3>
                              <h5 class="mt-1">Rp. <?php echo number_format($totalHutang, 0, ',', '.'); ?></h5>
                            </div>
                            <div class="dashboard-user bg-light-danger"><span></span>
                              <svg>
                                <use href="../../assets/svg/icon-sprite.svg#profile"></use>
                              </svg>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr style="border: 1px solid #000; width: 86%; margin-left: 4%; margin-bottom: 7%;">
                  </div>
                </div>
                <div class="col-xl-12 col-xl-50 col-md-6 box-col-6">
                  <div class="card news-update">
                    <div class="card-header pb-0">
                      <div class="header-top">
                        <h4>Pengingat Stok</h4>
                      </div>
                    </div>
                    <div class="card-body" style="max-height: 580px; overflow-y: auto;">
                      <?php
                      $queryLowStock = "SELECT ProductName, StockQty FROM product WHERE StockQty < 1000";
                      $resultLowStock = mysqli_query($conn, $queryLowStock);
                      while ($row = mysqli_fetch_array($resultLowStock)) {
                        $productName = $row['ProductName'];
                        $stockQty = $row['StockQty'];
                        echo '
                      <div class="d-flex align-items-center pt-0">
                          <div class="notification-box">
                              <svg class="bell-icon" width="24" height="50">
                                  <use href="../../assets/svg/icon-sprite.svg#fill-Bell"></use>
                              </svg>
                          </div>
                          <div class="flex-grow-1 ms-3">
                              <h3>' . $productName . '</h3>
                              <h6>Stok: ' . $stockQty . '</h6>
                          </div>
                      </div>';
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-8 col-xl-100 box-col-12">
              <div class="row">
                <div class="col-xxl-12 col-xl-100 box-col-12 proorder-xl-12">
                  <?php
                  $query = "
                        SELECT
                            MONTH(CreatedOn) AS month, 
                            SUM(Price * Quantity) AS revenue 
                        FROM 
                            salesorderdetail 
                        GROUP BY 
                            MONTH(CreatedOn)
                        ORDER BY 
                            MONTH(CreatedOn) ASC";
                  $result = mysqli_query($conn, $query);
                  $months = [];
                  $revenues = [];
                  while ($row = mysqli_fetch_assoc($result)) {
                    $months[] = $row['month'];
                    $revenues[] = $row['revenue'];
                  }
                  if (empty($months) || empty($revenues)) {
                    echo "<p>Tidak ada data untuk ditampilkan di grafik.</p>";
                    return;
                  }
                  $months_json = json_encode($months);
                  $revenues_json = json_encode($revenues);
                  ?>
                  <div class="card sales-overview" id="salesx-chart" style="display: none;">
                    <div class="card-header card-no-border pb-0">
                      <div class="header-top">
                        <h4>Total Sales Overview</h4>
                      </div>
                      <div class="dropdown icon-dropdown">
                        <!-- <button class="btn dropdown-toggle" id="userdropdown5" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="icon-more-alt"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown5">
                            <a class="dropdown-item" href="#">Weekly</a>
                            <a class="dropdown-item" href="#">Monthly</a>
                            <a class="dropdown-item" href="#">Yearly</a>
                          </div> -->
                      </div>
                    </div>
                    <div class="card-body p-0">
                      <div class="sales-chart">
                        <div class="shap-2"></div>
                        <div id="sales-overview-unique"></div>
                        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                        <script>
                          document.addEventListener('DOMContentLoaded', function () {
                            function showSalesChart() {
                              document.getElementById('salesx-chart').style.display = 'block';
                              document.getElementById('purchasing-chart').style.display = 'none';
                            }
                            document.getElementById('show-sales').addEventListener('click', showSalesChart);
                            var months = <?php echo $months_json; ?>;
                            var revenues = <?php echo $revenues_json; ?>;
                            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                            var labels = monthNames;
                            var revenueData = Array(12).fill(0);
                            for (var i = 0; i < months.length; i++) {
                              revenueData[months[i] - 1] = revenues[i];
                            }
                            var options = {
                              series: [{
                                name: 'Pendapatan',
                                data: revenueData
                              }],
                              chart: {
                                type: 'line',
                                height: 350
                              },
                              xaxis: {
                                categories: labels
                              },
                              stroke: {
                                curve: 'smooth'
                              },
                              title: {
                                text: '',
                                align: 'center'
                              },
                              yaxis: {
                                title: {
                                  text: 'Rupiah'
                                }
                              }
                            };
                            var chart = new ApexCharts(document.querySelector("#sales-overview-unique"), options);
                            chart.render();
                            showSalesChart();
                          });
                        </script>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-xxl-12 col-xl-100 box-col-12 proorder-xl-12">
                      <?php
                      $query = "
                            SELECT MONTH(CreatedOn) AS month, SUM(TotalPurchase) AS total_purchase
                            FROM (
                                SELECT CreatedOn, TotalPurchase FROM importpurchaseorderheader
                                UNION ALL
                                SELECT CreatedOn, TotalPurchase FROM purchaseorderheader
                            ) AS combined_purchases
                            GROUP BY MONTH(CreatedOn)
                            ORDER BY MONTH(CreatedOn) ASC";
                      $result = mysqli_query($conn, $query);
                      $purchase_months = [];
                      $total_purchases = [];
                      while ($row = mysqli_fetch_assoc($result)) {
                        $purchase_months[] = $row['month'];
                        $total_purchases[] = $row['total_purchase'];
                      }
                      if (empty($purchase_months) || empty($total_purchases)) {
                        echo "<p>Tidak ada data untuk ditampilkan di grafik.</p>";
                        return;
                      }
                      $purchase_months_json = json_encode($purchase_months);
                      $total_purchases_json = json_encode($total_purchases);
                      ?>
                      <div class="card purchasing-overview" id="purchasing-chart" style="display: none;">
                        <div class="card-header card-no-border pb-0">
                          <div class="header-top">
                            <h4>Total Purchasing Overview</h4>
                          </div>
                        </div>
                        <div class="card-body p-0">
                          <div class="purchasing-chart">
                            <div class="shap-2"></div>
                            <div id="purchasing-overview-unique"></div>
                          </div>
                        </div>
                        <script>
                          document.addEventListener('DOMContentLoaded', function () {
                            function showPurchasingChart() {
                              document.getElementById('purchasing-chart').style.display = 'block';
                              document.getElementById('salesx-chart').style.display = 'none';
                            }
                            document.getElementById('show-purchasing').addEventListener('click', showPurchasingChart);
                            var purchaseMonths = <?php echo $purchase_months_json; ?>;
                            var totalPurchases = <?php echo $total_purchases_json; ?>;
                            var labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                            var purchaseData = Array(12).fill(0);
                            for (var i = 0; i < purchaseMonths.length; i++) {
                              purchaseData[purchaseMonths[i] - 1] = totalPurchases[i];
                            }
                            var purchaseOptions = {
                              series: [{
                                name: 'Total Purchasing',
                                data: purchaseData
                              }],
                              chart: {
                                type: 'line',
                                height: 350
                              },
                              xaxis: {
                                categories: labels
                              },
                              stroke: {
                                curve: 'smooth'
                              },
                              title: {
                                text: '',
                                align: 'center'
                              },
                              yaxis: {
                                title: {
                                  text: 'Rupiah '
                                }
                              }
                            };
                            var purchaseChart = new ApexCharts(document.querySelector("#purchasing-overview-unique"), purchaseOptions);
                            purchaseChart.render();
                          });
                        </script>
                      </div>
                    </div>
                  </div>
                  <div class="col-xxl-12 col-xl-100 box-col-12 proorder-xl-1">
                    <div class="card">
                      <div class="card-header card-no-border pb-0">
                        <div class="header-top">
                          <h4>Pesanan Pending</h4>
                          <div class="dropdown icon-dropdown">
                            <button class="btn dropdown-toggle" id="userdropdown2" type="button"
                              data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown2"><a
                                class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a
                                class="dropdown-item" href="#">Yearly</a></div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade show active" id="daftarBarang" role="tabpanel">
                        <div class="table-responsive custom-scrollbar user-datatable"
                          style="max-height: 525px; overflow-y: auto;">
                          <table class="display" id="basic-12">
                            <thead>
                              <tr>
                                <th>Sales Order ID</th>
                                <th>Customers name</th>
                                <th>Date </th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
                                $creator = $_COOKIE["UserID"];
                              } else {
                                die("Error: Cookie 'UserID' tidak ada atau kosong.");
                              }
                              $query_access = "SELECT tSalesOrder FROM useraccesslevel WHERE UserID = '$creator'";
                              $result_access = mysqli_query($conn, $query_access);
                              $can_update = false;
                              if ($result_access) {
                                $row_access = mysqli_fetch_assoc($result_access);
                                $access_level = $row_access['tSalesOrder'];
                                if (strpos($access_level, 'R') !== false) {
                                  $can_update = true;
                                }
                              } else {
                                die("Error: Gagal mengambil data akses pengguna.");
                              }
                              $querySO = "SELECT soh.SalesOrderID, soh.CreatedOn, c.CustName, soh.Approval, soh.ApprovalStatus, soh.Finish
                                    FROM salesorderheader soh
                                    JOIN customer c ON soh.CustID=c.CustID
                                    WHERE soh.Finish =0 ";
                              $resultSO = mysqli_query($conn, $querySO);
                              while ($rowSO = mysqli_fetch_array($resultSO)) {
                                echo '
                                      <tr>
                                          <td >' . $rowSO["SalesOrderID"] . '</td>
                                          <td >' . $rowSO["CustName"] . '</td>
                                          <td >' . $rowSO["CreatedOn"] . '</td>
                                      </tr>';
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="col-xxl-3 col-xl-50 col-sm-6 proorder-xl-2">
                  <div class="card since">
                    <div class="card-body">
                      <div class="customer-card d-flex b-l-primary border-2">
                        <div class="ms-3">
                          <h3 class="mt-1">Total Piutang</h3>
                          <h5 class="mt-1">1.485</h5>
                        </div>
                        <div class="dashboard-user bg-light-primary"><span></span>
                          <svg>
                            <use href="../../assets/svg/icon-sprite.svg#male"></use>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> -->


                  <!-- <div class="col-xxl-3 col-xl-50 col-sm-6 proorder-xl-3">
                  <div class="card since">
                    <div class="card-body money">
                      <div class="customer-card d-flex b-l-secondary border-2">
                        <div class="ms-3">
                          <h3 class="mt-1">Total Hutang</h3>
                          <h5 class="mt-1">$5.873</h5>
                        </div>
                        <div class="dashboard-user bg-light-secondary"><span></span>
                          <svg>
                            <use href="../../assets/svg/icon-sprite.svg#money"></use>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xxl-3 col-xl-50 col-sm-6 proorder-xl-2">
                  <div class="card since">
                    <div class="card-body">
                      <div class="customer-card d-flex b-l-primary border-2">
                        <div class="ms-3">
                          <h3 class="mt-1">Total Hutang</h3>
                          <h5 class="mt-1">1.485</h5>
                        </div>
                        <div class="dashboard-user bg-light-primary"><span></span>
                          <svg>
                            <use href="../../assets/svg/icon-sprite.svg#male"></use>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row proorder-xl-9 pe-0">
                  <div class="col-xl-8 col-xl-100 col-md-12">
                    <div class="row">
                      <div class="col-xxl-7 col-xl-50 col-md-6">
                        <div class="card">
                          <div class="card-header card-no-border pb-0">
                            <div class="header-top">
                              <h4>Active Tasks</h4>
                              <div class="dropdown icon-dropdown">
                                <button class="btn dropdown-toggle" id="userdropdown6" type="button"
                                  data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown6">
                                  <a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item"
                                    href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-body active-task">
                            <ul>
                              <li class="d-flex pt-0">
                                <div class="flex-shrink-0">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <label class="form-check-label"></label>
                                  </div>
                                </div>
                                <div class="flex-grow-1"> <a href="to-do.html">
                                    <h5>Regina Cooper</h5>
                                  </a>
                                  <p>Create userflow social application design</p>
                                </div><span class="delete-option"> <a href="#">
                                    <svg class="remove">
                                      <use href="../../assets/svg/icon-sprite.svg#Delete"></use>
                                    </svg></a></span>
                              </li>
                              <li class="d-flex">
                                <div class="flex-shrink-0">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <label class="form-check-label"></label>
                                  </div>
                                </div>
                                <div class="flex-grow-1"> <a href="to-do.html">
                                    <h5>Install Appointment</h5>
                                  </a>
                                  <p>Homepage design for slimmuch product</p>
                                </div><span class="delete-option"> <a href="#">
                                    <svg class="remove">
                                      <use href="../../assets/svg/icon-sprite.svg#Delete"></use>
                                    </svg></a></span>
                              </li>
                              <li class="d-flex">
                                <div class="flex-shrink-0">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <label class="form-check-label"></label>
                                  </div>
                                </div>
                                <div class="flex-grow-1"> <a href="to-do.html">
                                    <h5>Regina Cooper</h5>
                                  </a>
                                  <p>Interactive prototype design - web design</p>
                                </div><span class="delete-option"> <a href="#">
                                    <svg class="remove">
                                      <use href="../../assets/svg/icon-sprite.svg#Delete"></use>
                                    </svg></a></span>
                              </li>
                              <li class="d-flex">
                                <div class="flex-shrink-0">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <label class="form-check-label"></label>
                                  </div>
                                </div>
                                <div class="flex-grow-1"> <a href="to-do.html">
                                    <h5>Regina Cooper</h5>
                                  </a>
                                  <p>Create Application design for topbuzz</p>
                                </div><span class="delete-option"> <a href="#">
                                    <svg class="remove">
                                      <use href="../../assets/svg/icon-sprite.svg#Delete"></use>
                                    </svg></a></span>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="col-xxl-5 col-xl-50 col-md-6">
                        <div class="card">
                          <div class="card-header card-no-border pb-0">
                            <div class="header-top">
                              <h4>Total Investment</h4>
                              <div class="dropdown icon-dropdown">
                                <button class="btn dropdown-toggle" id="userdropdown7" type="button"
                                  data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown7">
                                  <a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item"
                                    href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-body p-0 pb-3 pt-3">
                            <div class="investment-card">
                              <div id="Investment-chart"></div>
                              <ul>
                                <li>
                                  <h5>Total</h5><span>$ 5,8272</span>
                                </li>
                                <li>
                                  <h5>Monthly </h5><span>$ 6,2456</span>
                                </li>
                                <li>
                                  <h5>Daily </h5><span>$ 5,8704</span>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xxl-12">
                        <div class="card leads">
                          <div class="card-header card-no-border pb-0">
                            <div class="header-top">
                              <h4>Leads Status</h4>
                              <div class="dropdown icon-dropdown">
                                <button class="btn dropdown-toggle" id="userdropdown8" type="button"
                                  data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown8">
                                  <a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item"
                                    href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="lead-status">
                              <ul>
                                <li>Customers</li>
                                <li class="border-3 b-l-primary">Lena Smith</li>
                                <li class="border-3 b-l-secondary">Nicol Green</li>
                                <li class="border-3 b-l-danger mb-0">Tom Taylor</li>
                              </ul>
                              <ul>
                                <li>Last Contacted</li>
                                <li>June 14, 2023</li>
                                <li>June 16, 2023</li>
                                <li class="mb-0">June 18, 2023</li>
                              </ul>
                              <ul>
                                <li>Sales Rep</li>
                                <li> <img src="../../assets/images/dashboard/user/2.png" alt="">Paul Miller
                                </li>
                                <li> <img src="../../assets/images/dashboard/user/1.png" alt="">Alen Lee</li>
                                <li class="mb-0"> <img src="../../assets/images/dashboard/user/3.png" alt="">Lucy
                                  White
                                </li>
                              </ul>
                              <ul>
                                <li>Status</li>
                                <li class="bg-light-primary font-primary">Deal Won</li>
                                <li class="bg-light-secondary font-secondary">Intro Call</li>
                                <li class="bg-light-danger font-danger mb-0">Stuck</li>
                              </ul>
                              <ul>
                                <li>Deal Value</li>
                                <li>$ 200.2k</li>
                                <li>$210k</li>
                                <li class="mb-0">$70k</li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-4 col-xl-100 col-md-12 pe-0">
                    <div class="row">
                      <div class="col-xl-12 col-md-6 notification-card">
                        <div class="card custom-scrollbar">
                          <div class="card-header card-no-border pb-0">
                            <div class="header-top">
                              <h4>Notifications</h4>
                              <div class="dropdown icon-dropdown">
                                <button class="btn dropdown-toggle" id="userdropdown9" type="button"
                                  data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown9">
                                  <a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item"
                                    href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-body">
                            <ul class="notification-box">
                              <li class="d-flex">
                                <div class="flex-shrink-0"><img src="../../assets/images/dashboard/user/15.png" alt="">
                                </div>
                                <div class="flex-grow-1"> <a href="private-chat.html">
                                    <h5>Paul Svensson invite you Prototyping</h5>
                                  </a>
                                  <p>05 April, 2023 | 03:00 PM</p>
                                </div>
                                <div class="dropdown icon-dropdown">
                                  <button class="btn dropdown-toggle" id="userdropdown10" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                      class="icon-more-alt"></i></button>
                                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown10">
                                    <a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item"
                                      href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a>
                                  </div>
                                </div>
                              </li>
                              <li class="d-flex">
                                <div class="flex-shrink-0"><img src="../../assets/images/dashboard/user/16.png" alt="">
                                </div>
                                <div class="flex-grow-1"> <a href="private-chat.html">
                                    <h5>Adam Nolan mentioned you in UX Basics</h5>
                                  </a>
                                  <p>04 April, 2023 | 05:00 PM</p>
                                </div>
                                <div class="dropdown icon-dropdown">
                                  <button class="btn dropdown-toggle" id="userdropdown11" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                      class="icon-more-alt"></i></button>
                                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown11">
                                    <a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item"
                                      href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a>
                                  </div>
                                </div>
                              </li>
                              <li class="d-flex">
                                <div class="flex-shrink-0"><img src="../../assets/images/dashboard/user/17.png" alt="">
                                </div>
                                <div class="flex-grow-1"> <a href="private-chat.html">
                                    <h5>Paul Morgan Commented in UI Design</h5>
                                  </a>
                                  <p>05 April, 2023 | 02:00 PM</p>
                                </div>
                                <div class="dropdown icon-dropdown">
                                  <button class="btn dropdown-toggle" id="userdropdown12" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                      class="icon-more-alt"></i></button>
                                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown12">
                                    <a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item"
                                      href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a>
                                  </div>
                                </div>
                              </li>
                              <li class="d-flex">
                                <div class="flex-shrink-0"><img src="../../assets/images/dashboard/user/18.png" alt="">
                                </div>
                                <div class="flex-grow-1"> <a href="private-chat.html">
                                    <h5>Robert Babinski Said nothing important</h5>
                                  </a>
                                  <p>01 April, 2023 | 06:00 PM</p>
                                </div>
                                <div class="dropdown icon-dropdown">
                                  <button class="btn dropdown-toggle" id="userdropdown13" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                      class="icon-more-alt"></i></button>
                                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown13">
                                    <a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item"
                                      href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a>
                                  </div>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-12 col-md-6 pe-0">
                        <div class="card statistics">
                          <div class="card-header card-no-border pb-0">
                            <div class="header-top">
                              <h4>Statistics</h4>
                              <div class="dropdown icon-dropdown">
                                <button class="btn dropdown-toggle" id="userdropdown14" type="button"
                                  data-bs-toggle="dropdown" aria-expanded="false"><i class="icon-more-alt"></i></button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown14">
                                  <a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item"
                                    href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="d-flex">
                              <div class="flex-shrink-0">
                                <h4>Weekly Target</h4><span>25% achieved</span>
                                <div class="progress">
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: 50%"
                                    aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h4>Montly Target</h4><span>40% achieved</span>
                                <div class="progress">
                                  <div class="progress-bar bg-secondary" role="progressbar" style="width: 85%"
                                    aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>  -->

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