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
  $url = "https://fs.tokopedia.net/inventory/v1/fs/19044/product/info?shop_id=17971369&page=1&per_page=10";

  // Inisialisasi CURL
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  // Headers (pastikan token Authorization benar)
  
  $headers = array(
    "Authorization: Bearer c:VSF-CjqmQkGmieLVm6E6gA",
  );
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

  // Disable SSL verifikasi (hanya untuk testing, hindari pada production)
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  // Eksekusi CURL dan dapatkan respons
  $resp = curl_exec($curl);
  curl_close($curl);



  // Decode respons menjadi array
  $arr = json_decode($resp, true);

  // Debug: Periksa apakah decoding berhasil
  if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error decoding JSON: " . json_last_error_msg();
    exit; // Hentikan eksekusi jika JSON tidak valid
  }
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
          <!-- <div class="col-xs-12">
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
                        <td style="width: 10%;">  Set the width of the image column 
                          <img class="img-fluid" src="../../Product-Image/1.jpg" alt="profile"
                            style="width: 80px; height: auto;">  Set image size 
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
                        <td style="width: 10%; "> 
                          <img class="img-fluid" src="../../Product-Image/1.jpg" alt="profile"
                            style="width: 80px; height: auto;"> 
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
                        <td style="width: 10%; "> 
                          <img class="img-fluid" src="../../Product-Image/1.jpg" alt="profile"
                            style="width: 80px; height: auto;"> 
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
                        <td style="width: 10%; "> 
                          <img class="img-fluid" src="../../Product-Image/1.jpg" alt="profile"
                            style="width: 80px; height: auto;">
                        </td>
                        <td style="width: 30%;">IMP Mangkok Kertas 360ml 500ml 650ml 800ml 1000ml /
                          Paper Bowl 12oz 17oz 23oz 28oz 33oz /
                          Mangkok Kertas Polos + Tutup Isi 50 pcs</td>
                        <td style="width: 10%; ">
                          <div class="col-lg-8">
                            <input class="form-control digits" type="number" value="100">
                          </div>
                        </td>
                        <td>RamJacob@twitter</td>
                        <td>Developer</td>
                        <td>Apple Inc.</td>
                      </tr>
                    </tbody>


                  </table>
                </div>
              </div>
            </div>
          </div> -->

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
                      <option selected="" disabled="">Urutan</option>
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
                <h5><?php echo count($arr["data"]); ?> Produk</h5>
                <br>
                <div class="row header-table">
                  <div class="col-lg-4 col-xs-4">INFO PRODUK</div>
                  <div class="col-lg-2 col-xs-2">HARGA</div>
                  <div class="col-lg-1 col-xs-1">STOK</div>
                  <div class="col-lg-2 col-xs-2">STORE</div>
                  <div class="col-lg-1 col-xs-1">STATUS</div>
                  <div class="col-lg-2 col-xs-2"></div>
                  <div class="col-lg-2 col-xs-2"></div>
                </div>

                <?php if (isset($arr["data"])): ?>
                  <?php foreach ($arr["data"] as $product): ?>
                    <div class="row body-table">
                      <div class="col-lg-1 col-xs-1">
                        <img class="img-fluid" src="../../Product-Image/1.jpg" alt="product_image" width="75px">
                      </div>
                      <div class="col-lg-3 col-xs-3">
                        <div style="height:40px; overflow-x: hidden;">
                          <?php echo isset($product["basic"]["name"]) ? $product["basic"]["name"] : "Product name not available"; ?>
                        </div>
                        <div style="margin-top: 5px; color: #9AA0A6;">
                          ID (SKU):
                          <?php echo isset($product["other"]["sku"]) ? $product["other"]["sku"] : "Product SKU not available"; ?>
                        </div>
                      </div>

                      <div class="col-lg-2 col-xs-2">
                        <div style="width:80%;">
                          <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">Rp </span>
                            <input class="form-control price-input" type="text"
                              value="<?php echo isset($product['price']['value']) ? $product['price']['value'] : 'Product price not available'; ?>"
                              data-id="<?php echo isset($product['basic']['productID']) ? $product['basic']['productID'] : 'ID tidak tersedia'; ?>" />
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-1 col-xs-1">
                        <input class="form-control digits" type="text"
                          value="<?php echo isset($product["stock"]["value"]) ? $product["stock"]["value"] : ""; ?>">
                      </div>
                      <div class="col-lg-2 col-xs-2">
                        <input class="form-control digits" type="text" value="">
                      </div>

                      <div class="col-lg-1 col-xs-1">
                        <div class="flex-grow-1 icon-state">
                          <label class="switch">
                            <input type="checkbox" <?php echo isset($product["basic"]["status"]) && $product["basic"]["status"] == 1 ? 'checked' : ''; ?>>
                            <span class="switch-state"></span>
                          </label>
                        </div>
                      </div>
                      <div class="col-lg-2 col-xs-1">
                        <select class="form-select" id="validationDefault04">
                          <option selected="" disabled="">Action</option>
                          <option>Edit Produk</option>
                          <option>Non-Aktif</option>
                        </select>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
              <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
              <script>
                $(document).ready(function () {
                  $('.price-input').on('blur', function () {
                    const newPrice = $(this).val();
                    const productID = $(this).data('id');

                    console.log('New Price:', newPrice);
                    console.log('Product ID:', productID);

                    if (!isNaN(newPrice) && newPrice.trim() !== "" && productID !== 'ID tidak tersedia') {
                      updateProductPricePHP(productID, newPrice);
                    } else {
                      alert("Please enter a valid number for the price.");
                    }
                  });
                });

                function updateProductPricePHP(productID, newPrice) {
                  $.ajax({
                    url: '../RequestAPI/tokopedia-update-price.php',
                    type: 'POST',
                    data: {
                      //parameter
                      product_id: productID,
                      new_price: newPrice
                    },
                    success: function (response) {
                      console.log('Response:', response);
                      const responseData = JSON.parse(response);
                      if (responseData.error) {
                        alert('Error updating price: ' + responseData.error);
                      } else {
                        alert('Price updated successfully!');
                      }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                      console.error('AJAX Error:', textStatus, errorThrown);
                      alert('Error updating price: ' + errorThrown);
                    }
                  });
                }
              </script>
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