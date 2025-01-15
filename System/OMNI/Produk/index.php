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
                <div class="row header-table">
                  <div class="col-lg-4 col-xs-4">INFO PRODUK</div>
                  <div class="col-lg-2 col-xs-2">HARGA</div>
                  <div class="col-lg-1 col-xs-1">STOK</div>
                  <div class="col-lg-2 col-xs-2">STORE</div>
                  <div class="col-lg-1 col-xs-1">STATUS</div>
                  <div class="col-lg-2 col-xs-2"></div>
                </div>
                <?php
                require_once '../RequestAPI/tokopedia-get-all-product.php';
                require_once '../Process/addproduct.php';

                $addNewProduct = new ProdukTokopedia();
                $products = getAllProducts();
                if ($products) {
                  $addNewProduct->processSimpan($products);
                }

                $query = "SELECT * FROM productomni";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                  die("Query gagal dijalankan: " . mysqli_error($conn));
                }
                ?>
                <?php if (mysqli_num_rows($result) > 0): ?>
                  <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <div class="row body-table">
                      <div class="col-lg-1 col-xs-1">
                        <?php
                        $imageUrl = !empty($product['Img']) ? $product['Img'] : 'default.jpg';
                        echo '<img class="img-fluid" src="' . $imageUrl . '" alt="product_image" width="75px">';
                        ?>
                      </div>
                      <div class="col-lg-3 col-xs-3">
                        <div style="height:40px; overflow-x: hidden;">
                          <?php echo htmlspecialchars($product['ProductName']); ?>
                        </div>
                        <div style="margin-top: 5px; color: #9AA0A6;">
                          ID: <?php echo htmlspecialchars($product['ProductID']); ?>
                        </div>
                      </div>

                      <div class="col-lg-2 col-xs-2">
                        <div style="width:80%;">
                          <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">Rp </span>
                            <input class="form-control price-input" type="number"
                              value="<?php echo htmlspecialchars($product['SellingPrice']); ?>"
                              data-id="<?php echo htmlspecialchars($product['ProductID']); ?>" />
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-1 col-xs-1">
                        <input class="form-control stock-input" type="number"
                          value="<?php echo htmlspecialchars($product['StockValue']); ?>"
                          data-id="<?php echo htmlspecialchars($product['ProductID']); ?>" />
                      </div>

                      <div class="col-lg-2 col-xs-2">
                        <input class="form-control digits" type="text"
                          value="<?php echo htmlspecialchars($product['AvailableIn']); ?>">
                      </div>

                      <div class="col-lg-1 col-xs-1">
                        <div class="flex-grow-1 icon-state">
                          <label class="switch">
                            <input type="checkbox" class="status" name="status" <?php echo $product['Status'] == 1 ? 'checked' : ''; ?> data-id="<?php echo htmlspecialchars($product['ProductID']); ?>">
                            <span class="switch-state"></span>
                          </label>
                        </div>
                      </div>

                      <div class="col-lg-2 col-xs-2">
                        <select class="form-select" id="validationDefault04">
                          <option selected="" disabled="">Action</option>
                          <option>Edit Produk</option>
                          <option>Non-Aktif</option>
                        </select>
                      </div>
                    </div>

                    <?php if (!empty($product['ChildID'])): ?>
                      <div class="row" style="margin-top: 10px; margin-left: 0px;">
                        <button class="btn btn-info" type="button" data-bs-toggle="collapse"
                          data-bs-target="#<?php echo $product['ProductID']; ?>" aria-expanded="false"
                          aria-controls="variantList">
                          Lihat Varian
                        </button>
                        <div class="collapse mt-2" id="<?php echo $product['ProductID']; ?>">
                          <ul class="list-group">
                            <?php
                            $childIds = explode(',', $product['ChildID']);

                            $variantQuery = "SELECT * FROM productvariantomni WHERE ProductIDVariant IN ('" . implode("', '", $childIds) . "')";
                            $variantResult = mysqli_query($conn, $variantQuery);

                            if (!$variantResult) {
                              die("Query gagal dijalankan: " . mysqli_error($conn));
                            }

                            while ($variant = mysqli_fetch_assoc($variantResult)): ?>
                              <li class="list-group-item">
                                <div class="row">
                                    <div class="col-lg-1 col-xs-1">
                                        <img class="img-fluid" src="<?php echo 'default_variant.jpg'; ?>" alt="variant_image" width="75px">
                                    </div>
                                    <div class="col-lg-3 col-xs-3">
                                        <div><?php echo htmlspecialchars($variant['ProductName']); ?></div>
                                        <div style="color: #9AA0A6;">
                                            ID: <?php echo htmlspecialchars($variant['ProductIDVariant']); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-xs-2">
                                        <div style="width:80%;">
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">Rp </span>
                                                <input class="form-control price-input" type="number"
                                                      value="<?php echo htmlspecialchars($variant['SellingPrice']); ?>"
                                                      data-id="<?php echo htmlspecialchars($variant['ProductIDVariant']); ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-xs-1">
                                        <input class="form-control stock-input" type="number"
                                              value="<?php echo htmlspecialchars($variant['StockValue']); ?>"
                                              data-id="<?php echo htmlspecialchars($variant['ProductIDVariant']); ?>"/>
                                    </div>
                                    <div class="col-lg-1 col-xs-1">
                                        <div class="flex-grow-1 icon-state">
                                            <label class="switch">
                                                <input type="checkbox" class="status" name="status" 
                                                      <?php echo $variant['Status'] == 1 ? 'checked' : ''; ?>
                                                      data-id="<?php echo htmlspecialchars($variant['ProductIDVariant']); ?>">
                                                <span class="switch-state"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                              </li>
                            <?php endwhile; ?>
                          </ul>
                        </div>
                      </div>
                    <?php endif; ?>
                  <?php endwhile; ?>
                <?php else: ?>
                  <div class="row">
                    <div class="col">
                      <p>Tidak ada produk ditemukan</p>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
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

              $(document).ready(function () {
                $('.stock-input').on('blur', function () {
                  const newStock = $(this).val();
                  const productID = $(this).data('id');

                  console.log('New Stock:', newStock);
                  console.log('Product ID:', productID);

                  if (!isNaN(newStock) && newStock.trim() !== "" && productID !== 'ID tidak tersedia') {
                    updateProductStockPHP(productID, newStock);
                  } else {
                    alert("Please enter a valid number for the stock.");
                  }
                });
              });

              function updateProductStockPHP(productID, newStock) {
                $.ajax({
                  url: '../RequestAPI/tokopedia-update-stock.php',
                  type: 'POST',
                  data: {
                    product_id: productID,
                    new_stock: newStock
                  },
                  success: function (response) {
                    console.log('Response:', response);
                    const responseData = JSON.parse(response);
                    if (responseData.error) {
                      alert('Error updating stock: ' + responseData.error);
                    } else {
                      alert('Stock updated successfully!');
                    }
                  },
                  error: function (jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                    alert('Error updating stock: ' + errorThrown);
                  }
                });
              }
              document.querySelectorAll('.status').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                  var productID = parseInt(this.getAttribute('data-id'));
                  var isChecked = this.checked;
                  var productType = this.getAttribute('data-type');
                  var parentID = this.getAttribute('data-parent-id');

                  var url = isChecked ? '../RequestAPI/tokopedia-set-active.php' : '../RequestAPI/tokopedia-set-inactive.php';

                  $.ajax({
                    type: 'POST',
                    url: url,
                    data: { product_id: [productID] },
                    success: function (response) {
                      console.log('Response: ', response);

                      try {
                        var result = JSON.parse(response);
                        if (result.success) {
                          alert('Status berhasil diperbarui' + productID);

                          // Update UI
                          if (productType === 'main') {
                            // Update status semua varian jika produk utama diubah
                            document.querySelectorAll(`.variant-status[data-parent-id="${productID}"]`).forEach(function (variantCheckbox) {
                              variantCheckbox.checked = isChecked;
                            });
                          } else if (productType === 'variant') {
                            // Periksa apakah semua varian memiliki status yang sama
                            var allVariants = document.querySelectorAll(`.variant-status[data-parent-id="${parentID}"]`);
                            var allChecked = Array.from(allVariants).every(cb => cb.checked);
                            var mainCheckbox = document.querySelector(`.main-product-status[data-id="${parentID}"]`);
                            if (mainCheckbox) {
                              mainCheckbox.checked = allChecked;
                            }
                          }
                        } else {
                          throw new Error(result.error || 'Unknown error');
                        }
                      } catch (e) {
                        throw new Error('Failed to parse response: ' + e.message);
                      }
                    },
                    error: function (xhr, status, error) {
                      console.error('Error: ' + error + ' | Product ID: ' + productID);
                      alert('Terjadi kesalahan saat memperbarui status untuk Product ID: ' + productID);
                      // Kembalikan checkbox ke status sebelumnya jika terjadi error
                      checkbox.checked = !isChecked;
                    }
                  });
                });
              });
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