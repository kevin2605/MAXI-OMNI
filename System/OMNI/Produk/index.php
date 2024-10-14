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

  .header-table {
    border-top: 1px solid #DADCE0;
    border-bottom: 1px solid #DADCE0;
    padding: 10px;
    /*padding-bottom:10px;*/
  }

  .body-table {
    /*border-top: 1px solid #DADCE0;*/
    border-bottom: 1px solid #DADCE0;
    padding: 20px 10px 20px 10px;
    /*padding-bottom:10px;*/
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
              <div class="col-xs-6 p-0">
                <h3>DAFTAR PRODUK</h3>
              </div>
              <div class="col-xs-6 p-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">
                      <svg class="stroke-icon">
                        <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Produk</li>
                  <li class="breadcrumb-item">Daftar</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid <?php echo $accessDenied ? 'hiddenn' : ''; ?>">
          <!-- Container-fluid start -->

          <div class="col-xs-12">
            <div class="card">
              <div class="card-header pb-0 card-no-border">

              </div>
              <div class="card-body">
                <div class="table-responsive custom-scrollbar">
                  <table class="table">
                    <thead>
                      <tr class="border-bottom-primary">
                        <th scope="col">INFO PRODUK</th>
                        <th scope="col"></th>
                        <th scope="col">HARGA</th>
                        <th scope="col">STOCK</th>
                        <th scope="col">STATUS</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="border-bottom-secondary">
                        <td style="width: 10%;"> <!-- Set the width of the image column -->
                          <img class="img-fluid" src="../../Product-Image/1.jpg" alt="profile"
                            style="width: 80px; height: auto;"> <!-- Set image size -->
                        </td>
                        <td style="width: 30%;">IMP Mangkok Kertas 360ml 500ml 650ml 800ml 1000ml /
                          Paper Bowl 12oz 17oz 23oz 28oz 33oz /
                          Mangkok Kertas Polos + Tutup Isi 50 pcs</td>
                        <td>Wolfe</td>
                        <td>RamJacob@twitter</td>
                        <td>Developer</td>
                        <td>Apple Inc.</td>
                      </tr>
                      <tr class="border-bottom-secondary">
                        <td style="width: 10%; "> <!-- Set the width of the image column -->
                          <img class="img-fluid" src="../../Product-Image/1.jpg" alt="profile"
                            style="width: 80px; height: auto;"> <!-- Set image size -->
                        </td>
                        <td style="width: 30%;">IMP Mangkok Kertas 360ml 500ml 650ml 800ml 1000ml /
                          Paper Bowl 12oz 17oz 23oz 28oz 33oz /
                          Mangkok Kertas Polos + Tutup Isi 50 pcs</td>
                        <td>Wolfe</td>
                        <td>RamJacob@twitter</td>
                        <td>Developer</td>
                        <td>Apple Inc.</td>
                      </tr>
                      <tr class="border-bottom-secondary">
                        <td style="width: 10%; "> <!-- Set the width of the image column -->
                          <img class="img-fluid" src="../../Product-Image/1.jpg" alt="profile"
                            style="width: 80px; height: auto;"> <!-- Set image size -->
                        </td>
                        <td style="width: 30%;">IMP Mangkok Kertas 360ml 500ml 650ml 800ml 1000ml /
                          Paper Bowl 12oz 17oz 23oz 28oz 33oz /
                          Mangkok Kertas Polos + Tutup Isi 50 pcs</td>
                        <td>Wolfe</td>
                        <td>RamJacob@twitter</td>
                        <td>Developer</td>
                        <td>Apple Inc.</td>
                      </tr>
                      <tr class="border-bottom-secondary">
                        <td style="width: 10%; "> <!-- Set the width of the image column -->
                          <img class="img-fluid" src="../../Product-Image/1.jpg" alt="profile"
                            style="width: 80px; height: auto;"> <!-- Set image size -->
                        </td>
                        <td style="width: 30%;">IMP Mangkok Kertas 360ml 500ml 650ml 800ml 1000ml /
                          Paper Bowl 12oz 17oz 23oz 28oz 33oz /
                          Mangkok Kertas Polos + Tutup Isi 50 pcs</td>
                        <td>Wolfe</td>
                        <td>RamJacob@twitter</td>
                        <td>Developer</td>
                        <td>Apple Inc.</td>
                      </tr>
                    </tbody>


                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xs-12">
            <div class="card">
              <div class="card-header pb-0 card-no-border">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="input-group"><span class="input-group-text" id="basic-addon1"><i
                          class="fa fa-search"></i></span>
                      <input class="form-control" type="text" placeholder="Cari nama produk atau SKU">
                    </div>
                  </div>
                  <div class="col-lg-1"></div>
                  <div class="col-lg-2">
                    <select class="form-select" id="validationDefault04">
                      <option selected="" disabled="" value="">Urutan</option>
                      <option>Stok Tertinggi</option>
                      <option>Stok Terendah</option>
                      <option>Harga Tertinggi</option>
                      <option>Harga Terendah</option>
                      <option>Nama : A-Z</option>
                      <option>Nama : Z-A</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <h5>235 Produk</h5>
                <br>
                <div class="row header-table">
                  <div class="col-lg-4 col-xs-4">INFO PRODUK</div>
                  <div class="col-lg-2 col-xs-2">HARGA</div>
                  <div class="col-lg-2 col-xs-2">STOK</div>
                  <div class="col-lg-2 col-xs-2">STATUS</div>
                  <div class="col-lg-2 col-xs-2"></div>
                </div>
                <div class="row body-table">
                  <div class="col-lg-1 col-xs-1">
                    <img class="img-fluid" src="../../Product-Image/1.jpg" alt="product_image" width="75px">
                  </div>
                  <div class="col-lg-3 col-xs-3">
                    <div style="height:40px; overflow-x: hidden;">
                      IMP Mangkok Kertas 360ml 500ml 650ml 800ml 1000ml / Paper Bowl 12oz 17oz 23oz 28oz 33oz / Mangkok
                      Kertas Polos + Tutup Isi 50 pcs
                    </div>
                    <div style="margin-top: 5px; color: #9AA0A6;">
                      ID (SKU): -
                    </div>
                  </div>
                  <div class="col-lg-2 col-xs-2">
                    <div style="width:80%;">
                      <div class="input-group"><span class="input-group-text" id="basic-addon1">Rp</span>
                        <input class="form-control" type="text" value="25.000">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-1 col-xs-1">
                    <input class="form-control digits" type="number" value="100">
                  </div>
                  <div class="col-lg-1 col-xs-1">

                  </div>
                  <div class="col-lg-2 col-xs-2">
                    <div class="flex-grow-1 icon-state">
                      <label class="switch">
                        <input type="checkbox" checked=""><span class="switch-state"></span>
                      </label>
                    </div>
                  </div>
                  <div class="col-lg-2 col-xs-2">
                    <select class="form-select" id="validationDefault04">
                      <option selected="" disabled="" value="">Action</option>
                      <option>Edit Produk </option>
                      <option>Non-Aktif</option>
                    </select>
                  </div>
                </div>
                <div class="row body-table">
                  <div class="col-lg-1 col-xs-1">
                    <img class="img-fluid" src="../../Product-Image/1.jpg" alt="product_image" width="75px">
                  </div>
                  <div class="col-lg-3 col-xs-3">
                    <div style="height:40px; overflow-x: hidden;">
                      IMP Mangkok Kertas 360ml 500ml 650ml 800ml 1000ml / Paper Bowl 12oz 17oz 23oz 28oz 33oz / Mangkok
                      Kertas Polos + Tutup Isi 50 pcs
                    </div>
                    <div style="margin-top: 5px; color: #9AA0A6;">
                      ID (SKU): -
                    </div>
                  </div>
                  <div class="col-lg-2 col-xs-2">
                    <div style="width:80%;">
                      <div class="input-group"><span class="input-group-text" id="basic-addon1">Rp</span>
                        <input class="form-control" type="text" value="25.000">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-1 col-xs-1">
                    <input class="form-control digits" type="number" value="100">
                  </div>
                  <div class="col-lg-1 col-xs-1">

                  </div>
                  <div class="col-lg-2 col-xs-2">
                    <div class="flex-grow-1 icon-state">
                      <label class="switch">
                        <input type="checkbox" checked=""><span class="switch-state"></span>
                      </label>
                    </div>
                  </div>
                  <div class="col-lg-2 col-xs-2">
                    <select class="form-select" id="validationDefault04">
                      <option selected="" disabled="" value="">Action</option>
                      <option>Edit Produk </option>
                      <option>Non-Aktif</option>
                    </select>
                  </div>
                </div>
                <div class="row body-table">
                  <div class="col-lg-1 col-xs-1">
                    <img class="img-fluid" src="../../Product-Image/1.jpg" alt="product_image" width="75px">
                  </div>
                  <div class="col-lg-3 col-xs-3">
                    <div style="height:40px; overflow-x: hidden;">
                      IMP Mangkok Kertas 360ml 500ml 650ml 800ml 1000ml / Paper Bowl 12oz 17oz 23oz 28oz 33oz / Mangkok
                      Kertas Polos + Tutup Isi 50 pcs
                    </div>
                    <div style="margin-top: 5px; color: #9AA0A6;">
                      ID (SKU): -
                    </div>
                  </div>
                  <div class="col-lg-2 col-xs-2">
                    <div style="width:80%;">
                      <div class="input-group"><span class="input-group-text" id="basic-addon1">Rp</span>
                        <input class="form-control" type="text" value="25.000">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-1 col-xs-1">
                    <input class="form-control digits" type="number" value="100">
                  </div>
                  <div class="col-lg-1 col-xs-1">

                  </div>
                  <div class="col-lg-2 col-xs-2">
                    <div class="flex-grow-1 icon-state">
                      <label class="switch">
                        <input type="checkbox" checked=""><span class="switch-state"></span>
                      </label>
                    </div>
                  </div>
                  <div class="col-lg-2 col-xs-2">
                    <select class="form-select" id="validationDefault04">
                      <option selected="" disabled="" value="">Action</option>
                      <option>Edit Produk </option>
                      <option>Non-Aktif</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
      </div>
    </div>
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
    <script src="../../../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../assets/js/datatable/datatables/datatable.custom.js"></script>
    <!-- Plugins JS Ends-->

    <!-- Plugin notification wajib start -->
    <script src="../../../assets/js/notify/bootstrap-notify.min.js"></script>
    <script src="../../../assets/js/notify/index.js"></script>
    <!-- Plugin notification wajib end -->

    <!-- Theme js-->
    <script src="../../../assets/js/script.js"></script>

    <!-- Plugin used-->
</body>

</html>