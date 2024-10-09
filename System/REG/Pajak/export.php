<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "../headcontent.php";
  session_start();
  include "../DBConnection.php";
  $userID = $_COOKIE['UserID'];

  $query = "SELECT rInvoice FROM useraccesslevel WHERE UserID = '$userID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $hasCRUDAccess = strpos($row['rInvoice'], 'C') !== false || // Create
    strpos($row['rInvoice'], 'R') !== false || // Read
    strpos($row['rInvoice'], 'U') !== false || // Update
    strpos($row['rInvoice'], 'D') !== false;  // Delete
  
  $accessDenied = !$hasCRUDAccess;
  ?>

  <!-- AJAX SCRIPT and DYNAMIC TABLE -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
  <!-- script sweetaler2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- use xlsx.mini.min.js from version 0.20.3 -->
  <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.mini.min.js"></script>

  <script>
    /*
      function submitFilter(){
          var customer = document.getElementById("customer").value;
          var startdate = document.getElementById("startdate").value;
          var enddate = document.getElementById("enddate").value;
          var startdatefaktur = document.getElementById("startdatefaktur").value;
          var enddatefaktur = document.getElementById("enddatefaktur").value;

          $.ajax({
              type: "POST",
              url: "../Process/reportInvoice.php", 
              data: "customer="+customer+"&startdate="+startdate+"&enddate="+enddate+"&startdatefaktur="+startdatefaktur+"&enddatefaktur="+enddatefaktur,
              success: function(result){
                  $("#export-button tbody tr").remove(); 
                  var res = JSON.parse(result);
                  console.log(res.length);
                  $.each(res, function(index, value) {
                      let dpp = value.TotalInvoice/1.11;
                      let ppn = value.TotalInvoice - dpp;
                      $('#export-button tbody').append("<tr><td>"+ value.InvoiceID +"</td><td>"+ value.CreatedOn.substring(0,10) +"</td><td>"+ value.CustName +"</td><td>"+ value.NPWPNum +"</td><td>"+ value.TaxInvoiceNumber +"</td><td>"+ value.TaxInvoiceDate +"</td><td>"+ numeral(value.TotalInvoice).format("0,0.00") +"</td><td> 0 </td><td>"+ numeral(value.TotalInvoice).format("0,0.00") +"</td><td>"+ numeral(dpp).format("0,0.00") +"</td><td>"+ numeral(ppn).format("0,0.00") +"</td><td>"+ numeral(value.TotalInvoice).format("0,0.00") +"</td></tr>");
                  });
                  if(res.length < 1){
                      Swal.fire({
                          position: "center",
                          icon: "error",
                          title: "Pencarian tidak ditemukan!",
                          showConfirmButton: false,
                          timer:2000
                      });
                  }
              }
          });
      }*/
  </script>
</head>
<style>
  .hidden {
    display: none;
  }
</style>

<body>
  <?php if ($accessDenied): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            window.location.href = '../Dashboard/'; // Redirect ke halaman lain atau homepage
          }
        });
      });
    </script>
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
                  <h3>EXPORT</h3>
                </div>
                <div class="col-sm-6 pe-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">
                        <svg class="stroke-icon">
                          <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Pajak</li>
                    <li class="breadcrumb-item">Export</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid <?php echo $accessDenied ? 'hidden' : ''; ?>">
          <?php endif; ?>
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
                        <h3>EXPORT</h3>
                      </div>
                      <div class="col-sm-6 pe-0">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.html">
                              <svg class="stroke-icon">
                                <use href="../../assets/svg/icon-sprite.svg#stroke-home"></use>
                              </svg></a></li>
                          <li class="breadcrumb-item">Pajak</li>
                          <li class="breadcrumb-item">Export</li>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <h3>FILTER</h3>
                      </div>
                      <div class="card-body">
                        <form class="form theme-form" action="../Process/export-excel.php" method="POST">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="row">
                                <div class="mb-3 row">
                                  <label class="col-sm-2">Tanggal Awal</label>
                                  <div class="col-sm-10">
                                    <input class="form-control" id="startdate" name="startdate" type="date" required>
                                  </div>
                                </div>
                                <div class="mb-3 row">
                                  <label class="col-sm-2">Tanggal Akhir</label>
                                  <div class="col-sm-10">
                                    <input class="form-control" id="enddate" name="enddate" type="date" required>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <button class="btn btn-primary" name="btnSearch"><i class="fa fa-search"></i> Export</button>
                        </form>
                      </div>
                    </div>
                    <!-- <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h3>REPORT</h3>
                          </div>
                          <div class="card-body">
                            <div class="dt-ext table-responsive custom-scrollbar">
                              <table class="display">
                                <thead>
                                  <tr>
                                    <th>FK</th>
                                    <th>KD JENIS TRANSAKSI</th>
                                    <th>FG PENGGANTI</th>
                                    <th>NOMOR FAKTUR</th>
                                    <th>MASA PAJAK</th>
                                    <th>TAHUN PAJAK</th>
                                    <th>TANGGAL FAKTUR</th>
                                    <th>NPWP</th>
                                    <th>NAMA</th>
                                    <th>ALAMAT LENGKAP</th>
                                    <th>JUMLAH DPP</th>
                                    <th>JUMLAH PPN</th>
                                    <th>JUMLAH PPNBM</th>
                                    <th>ID KETERANGAN TAMBAHAN</th>
                                    <th>FG UANG MUKA</th>
                                    <th>UANG MUKA DPP</th>
                                    <th>UANG MUKA PPN</th>
                                    <th>UANG MUKA PPNBM</th>
                                    <th>REFERENSI</th>
                                    <th>KODE DOKUMEN PENDUKUNG</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  if (isset($_POST["btnSearch"])) {
                                    $startDate = $_POST['startdate'];
                                    $endDate = $_POST['enddate'];

                                    $query = "SELECT 
                                        i.InvoiceID AS FK,
                                        '01' AS KD_JENIS_TRANSAKSI,
                                        '0' AS FG_PENGGANTI,
                                        i.TaxInvoiceNumber AS NOMOR_FAKTUR,
                                        MONTH(i.CreatedOn) AS MASA_PAJAK,
                                        YEAR(i.CreatedOn) AS TAHUN_PAJAK,
                                        i.TaxInvoiceDate AS TANGGAL_FAKTUR,
                                        c.CustID,
                                        c.NPWPNum AS NPWP,
                                        c.NPWPName AS NAMA,
                                        c.NPWPAddress AS ALAMAT_LENGKAP,
                                        i.TotalInvoice AS JUMLAH_DPP,
                                        i.DPAmount,
                                        (i.TotalInvoice - i.DPAmount) * 0.1 AS JUMLAH_PPN,
                                        0 AS JUMLAH_PPNBM,
                                        '' AS ID_KETERANGAN_TAMBAHAN,
                                        0 AS FG_UANG_MUKA,
                                        i.DPAmount AS UANG_MUKA_DPP,
                                        (i.DPAmount * 0.1) AS UANG_MUKA_PPN,
                                        0 AS UANG_MUKA_PPNBM,
                                        '' AS REFERENSI,
                                        '' AS KODE_DOKUMEN_PENDUKUNG
                                        FROM invoiceheader i
                                        JOIN customer c ON i.CustID = c.CustID
                                        WHERE i.CreatedOn BETWEEN ? AND ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ss", $startDate, $endDate);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $custIDs = [];
                                    $invoiceIDs = [];
                                    if ($result->num_rows > 0) {
                                      while ($row = $result->fetch_assoc()) {
                                        $totalInvoice = $row['JUMLAH_DPP'];
                                        $dpAmount = $row['DPAmount'];
                                        $remainingDPP = $totalInvoice - $dpAmount;

                                        if ($dpAmount > 0) {
                                          $dppAfterDP = $remainingDPP / 1.1;
                                          $ppnAfterDP = $remainingDPP - $dppAfterDP;
                                        } else {
                                          $dppAfterDP = $totalInvoice / 1.1;
                                          $ppnAfterDP = $totalInvoice - $dppAfterDP;
                                        }
                                        $invoiceIDs[] = $row['FK'];

                                        echo "<tr>";
                                        echo "<td>FK</td>";
                                        echo "<td>{$row['KD_JENIS_TRANSAKSI']}</td>";
                                        echo "<td>{$row['FG_PENGGANTI']}</td>";
                                        echo "<td>{$row['NOMOR_FAKTUR']}</td>";
                                        echo "<td>{$row['MASA_PAJAK']}</td>";
                                        echo "<td>{$row['TAHUN_PAJAK']}</td>";
                                        echo "<td>{$row['TANGGAL_FAKTUR']}</td>";
                                        echo "<td>{$row['NPWP']}</td>";
                                        echo "<td>{$row['NAMA']}</td>";
                                        echo "<td>{$row['ALAMAT_LENGKAP']}</td>";
                                        echo "<td>" . number_format($dppAfterDP, 0) . "</td>";
                                        echo "<td>" . number_format($ppnAfterDP, 0) . "</td>";
                                        echo "<td>{$row['JUMLAH_PPNBM']}</td>";
                                        echo "<td>{$row['ID_KETERANGAN_TAMBAHAN']}</td>";
                                        echo "<td>{$row['FG_UANG_MUKA']}</td>";
                                        echo "<td>" . number_format($dpAmount, 0) . "</td>";
                                        echo "<td>" . number_format($dpAmount * 0.1, 0) . "</td>";
                                        echo "<td>0</td>";
                                        echo "<td>{$row['FK']}</td>";
                                        echo "<td>{$row['KODE_DOKUMEN_PENDUKUNG']}</td>";
                                        echo "</tr>";
                                        $custIDs[$row['CustID']] = $row['NAMA'];
                                      }
                                    } else {
                                      echo "<tr><td colspan='20'>No results found.</td></tr>";
                                    }
                                    $stmt->close();
                                  }
                                  ?>
                                </tbody>
                                <thead>
                                  <tr>
                                    <th>NPWP</th>
                                    <th>NAMA</th>
                                    <th>JALAN</th>
                                    <th>BLOK</th>
                                    <th>NOMOR</th>
                                    <th>RT</th>
                                    <th>RW</th>
                                    <th>KECAMATAN</th>
                                    <th>KELURAHAN</th>
                                    <th>KABUPATEN</th>
                                    <th>PROVINSI</th>
                                    <th>KODE POS</th>
                                    <th>TELEPON</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  if (!empty($custIDs)) {
                                    foreach ($custIDs as $custID => $nama) {
                                      $query2 = "SELECT CustID, NPWPNum, NPWPName, NPWPAddress, PhoneNumOne, PhoneNumTwo FROM customer WHERE CustID = ?";
                                      $stmt2 = $conn->prepare($query2);
                                      $stmt2->bind_param("s", $custID);
                                      $stmt2->execute();
                                      $result2 = $stmt2->get_result();

                                      if ($result2->num_rows > 0) {
                                        while ($row2 = $result2->fetch_assoc()) {
                                          $alamatLengkap = $row2['NPWPAddress'];
                                          $parts = explode(", ", $alamatLengkap);

                                          $kelurahan = $parts[0];
                                          $jalan = isset($parts[1]) ? $parts[1] : '';
                                          $kecamatan = isset($parts[2]) ? str_replace('Kec. ', '', $parts[2]) : '';
                                          $kabupaten = isset($parts[3]) ? $parts[3] : '';
                                          $lastPart = isset($parts[4]) ? $parts[4] : '';
                                          $pos = strrpos($lastPart, ' ');
                                          if ($pos !== false) {
                                            $provinsi = substr($lastPart, 0, $pos);
                                            $kodePos = substr($lastPart, $pos + 1);
                                          } else {
                                            $provinsi = $lastPart;
                                            $kodePos = '';
                                          }

                                          echo "<tr>";
                                          echo "<td>{$row2['NPWPNum']}</td>";
                                          echo "<td>{$row2['NPWPName']}</td>";
                                          echo "<td>{$jalan}</td>";
                                          echo "<td>-</td>"; // Blok
                                          echo "<td>-</td>"; // Nomor
                                          echo "<td>-</td>"; // RT
                                          echo "<td>-</td>"; // RW
                                          echo "<td>{$kecamatan}</td>";
                                          echo "<td>{$kelurahan}</td>";
                                          echo "<td>{$kabupaten}</td>";
                                          echo "<td>{$provinsi}</td>";
                                          echo "<td>{$kodePos}</td>";
                                          echo "<td>{$row2['PhoneNumOne']}</td>";
                                          echo "</tr>";
                                        }
                                      } else {
                                        echo "<tr><td colspan='13'>No data found for this customer.</td></tr>";
                                      }
                                    }
                                  }
                                  ?>
                                </tbody>
                                <thead>
                                  <tr>
                                    <th>OF</th>
                                    <th>KODE_OBJEK</th>
                                    <th>NAMA</th>
                                    <th>HARGA_SATUAN</th>
                                    <th>JUMLAH_BARANG</th>
                                    <th>HARGA_TOTAL</th>
                                    <th>DISKON</th>
                                    <th>DPP</th>
                                    <th>PPN</th>
                                    <th>TARIF_PPNBM</th>
                                    <th>PPNBM</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  if (isset($invoiceIDs) && count($invoiceIDs) > 0) {
                                    foreach ($invoiceIDs as $invoiceID) {
                                      $query3 = "SELECT InvoiceID, ProductCD, Quantity, Price, Discount, 
                                      (Quantity * Price) AS Subtotal 
                                      FROM invoicedetail 
                                      WHERE InvoiceID = ?";
                                      $stmt3 = $conn->prepare($query3);
                                      $stmt3->bind_param("s", $invoiceID);
                                      $stmt3->execute();
                                      $result3 = $stmt3->get_result();

                                      if ($result3->num_rows > 0) {
                                        while ($row3 = $result3->fetch_assoc()) {
                                          $ppn = $row3['Subtotal'] * 0.1;
                                          $dpp = $row3['Subtotal'] - $row3['Discount'];
                                          $ppnbm = 0;

                                          echo "<tr>";
                                          echo "<td>OF</td>";
                                          echo "<td>{$row3['ProductCD']}</td>";
                                          echo "<td>{$row3['ProductCD']}</td>";
                                          echo "<td>{$row3['Price']}</td>";
                                          echo "<td>{$row3['Quantity']}</td>";
                                          echo "<td>{$row3['Subtotal']}</td>";
                                          echo "<td>{$row3['Discount']}</td>";
                                          echo "<td>{$dpp}</td>";
                                          echo "<td>{$ppn}</td>";
                                          echo "<td>0</td>";
                                          echo "<td>{$ppnbm}</td>";
                                          echo "</tr>";
                                        }
                                      } else {
                                        echo "<tr><td colspan='11'>No data found for InvoiceID: {$invoiceID}</td></tr>";
                                      }
                                    }
                                  }
                                  ?>

                                </tbody>

                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> -->
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
  <script src="../../assets/js/notify/bootstrap-notify.min.js"></script>
  <script src="../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
  <script src="../../assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
  <script src="../../assets/js/datatable/datatable-extension/jszip.min.js"></script>
  <script src="../../assets/js/datatable/datatable-extension/buttons.colVis.min.js"></script>
  <script src="../../assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
  <script src="../../assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
  <script src="../../assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
  <script src="../../assets/js/datatable/datatable-extension/custom.js"></script>
  <!-- Plugins JS Ends-->
  <!-- Theme js-->
  <script src="../../assets/js/script.js"></script>
  <!-- Plugin used-->
</body>

</html>