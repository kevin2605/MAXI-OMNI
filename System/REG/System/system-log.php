<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "../headcontent.php";
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
              <div class="col-sm-6 ps-0">
                <h3>SYSTEM LOG</h3>
              </div>
              <div class="col-sm-6 pe-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">
                      <svg class="stroke-icon">
                        <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">System Log</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header pb-0 card-no-border">
                  <h3 class="mb-3">ACTIVITY LOG</h3><span>Semua aktivitas user web tercatat dan tersimpan dalam tabel
                    log dibawah.</span>
                </div>
                <div class="card-body">
                  <div class="table-responsive custom-scrollbar">
                    <!-- <table class="display" id="systemlog">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Tanggal</th>
                          <th>Waktu</th>
                          <th>Divisi</th>
                          <th>User</th>
                          <th>Action</th>
                          <th>Status</th>
                          <th>Message</th>
                        </tr>
                      </thead>

                    </table> -->
                    <div class="col-xl-12">
                      <div class="shipping-info">
                        <!-- <h5><i class="mb-3"></i>Semua aktivitas user web tercatat dan tersimpan dalam tabel
                          log dibawah. </h5> -->
                      </div>
                      <div class="overflow-auto">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th scope="col">LogID</th>
                              <th scope="col">Tanggal</th>
                              <th scope="col">Waktu</th>
                              <th scope="col">User</th>
                              <th scope="col">Action</th>
                              <th scope="col">Status</th>
                              <th scope="col">Message</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            // Assuming you have a connection $conn
                            $query = "SELECT sl.*, su.Name, su.userID FROM systemlog sl
                            LEFT JOIN systemuser su ON sl.UserID = su.UserID ORDER BY 1 DESC";
                            $result = mysqli_query($conn, $query);
                            if (!$result) {
                              echo "Error: " . mysqli_error($conn);
                              exit;
                            }
                            while ($row = mysqli_fetch_array($result)) {
                              $timestamp = new DateTime($row['timestamp']);
                              $tanggal = $timestamp->format('Y-m-d');
                              $waktu = $timestamp->format('H:i:s');
                              $status = $row['ActionStatus'] == 0 ? 'Success' : 'Failed';
                              $Name = $row['Name'] ? $row['Name'] : 'Unknown User';
                              $userID = $row['userID'] ? $row['userID'] : 'Unknown User';

                              echo '<tr>
                              <td>' . htmlspecialchars($row["LogNum"]) . '</td>
                              <td>' . htmlspecialchars($tanggal) . '</td>
                              <td>' . htmlspecialchars($waktu) . '</td>
                              <td>' . htmlspecialchars($userID) . '</td>
                              <td>' . htmlspecialchars($row["ActionDone"]) . '</td>
                              <td>' . htmlspecialchars($status) . '</td>
                              <td>' . htmlspecialchars($Name) . ' ' . htmlspecialchars($row["ActionMSG"]) . ' dengan nomor ' . htmlspecialchars($row["RecordID"]) . '</td>
                            </tr>';
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Container-fluid Ends-->
          </div>
          <!-- footer start-->

        </div>
      </div>
      <style>
        .overflow-auto {
          overflow-x: auto;
          max-height: 500px;
          /* Adjust height as needed */
          overflow-y: auto;
        }

        table {
          width: 100%;
          min-width: 600px;
          /* Ensure table doesn't shrink below a certain width */
        }

        th,
        td {
          text-align: left;
          padding: 8px;
        }

        th {
          background-color: #f2f2f2;
        }

        tr:nth-child(even) {
          background-color: #f9f9f9;
        }

        tr:hover {
          background-color: #d1e7fd;
        }
      </style>

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