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
                <h3>TAMBAH PRODUK</h3>
              </div>
              <div class="col-xs-6 p-0">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">
                      <svg class="stroke-icon">
                        <use href="../../../assets/svg/icon-sprite.svg#stroke-home"></use>
                      </svg></a></li>
                  <li class="breadcrumb-item">Produk</li>
                  <li class="breadcrumb-item">Tambah</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid <?php echo $accessDenied ? 'hiddenn' : ''; ?>">
          <!-- Container-fluid start -->
          <div class="row ">
            <div class="col-12">
              <div class="card-header">
                <h3>Product Form</h3>
              </div>

              <br>
              <div class="card-body">
                <div class="row no-divider g-1-5 g-3"> <!-- Menambahkan class no-divider -->
                  <!-- Kolom pertama dengan card -->
                  <div class="col-xxl-3 col-xl-4 box-col-4e sidebar-left-wrapper">
                    <div class="card">
                      <div class="card-body">
                        <ul class="sidebar-left-icons nav nav-pills" id="add-product-pills-tab" role="tablist">
                          <li class="nav-item"> <a class="nav-link" id="detail-product-tab">
                              <div class="nav-rounded">
                                <div class="product-icons">
                                  <svg class="stroke-icon">
                                    <use href="../../../assets/svg/icon-sprite.svg#product-detail"></use>
                                  </svg>
                                </div>
                              </div>
                              <div class="product-tab-content">
                                <h6>Informasi Produk</h6>
                                <p>Silahkan isi ID, Nama, dan Deskripsi produk</p>
                              </div>
                            </a></li>
                          <li class="nav-item"> <a class="nav-link" id="gallery-product-tab">
                              <div class="nav-rounded">
                                <div class="product-icons">
                                  <svg class="stroke-icon">
                                    <use href="../../../assets/svg/icon-sprite.svg#product-gallery"></use>
                                  </svg>
                                </div>
                              </div>
                              <div class="product-tab-content">
                                <h6>Foto Produk</h6>
                                <p>Pilih foto produk maks. 2MB setiap foto</p>
                              </div>
                            </a></li>
                          <li class="nav-item"> <a class="nav-link" id="category-product-tab">
                              <div class="nav-rounded">
                                <div class="product-icons">
                                  <svg class="stroke-icon">
                                    <use href="../../../assets/svg/icon-sprite.svg#product-category"></use>
                                  </svg>
                                </div>
                              </div>
                              <div class="product-tab-content">
                                <h6>Kategori Produk</h6>
                                <p>Pilih kategori produk pada setiap platform</p>
                              </div>
                            </a></li>
                          <li class="nav-item"><a class="nav-link" id="pricings-tab">
                              <div class="nav-rounded">
                                <div class="product-icons">
                                  <svg class="stroke-icon">
                                    <use href="../../../assets/svg/icon-sprite.svg#pricing"> </use>
                                  </svg>
                                </div>
                              </div>
                              <div class="product-tab-content">
                                <h6>Harga</h6>
                                <p>Silahkan beri harga jual dan beli</p>
                              </div>
                            </a></li>
                          <li class="nav-item"><a class="nav-link" id="advance-product-tab">
                              <div class="nav-rounded">
                                <div class="product-icons">
                                  <svg class="stroke-icon">
                                    <use href="../../../assets/svg/icon-sprite.svg#advance"> </use>
                                  </svg>
                                </div>
                              </div>
                              <div class="product-tab-content">
                                <h6>Advance</h6>
                                <p>Silahkan isi komponen barang jika ada</p>
                              </div>
                            </a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <!-- Kolom kedua dengan card -->
                  <b class="col-xxl-9 col-xl-8 box-col-8 position-relative">
                    <div class="card">
                      <div class="card-body">
                        <div class="sidebar-body">
                          <form class="row g-2" action="/submit.php" method="POST"> <!-- Single form tag -->
                            <label class="form-label col-12 m-0" for="productID1">ID Produk (SKU) </label>
                            <div class="col-12 custom-input">
                              <input class="form-control" id="productID1" type="text">

                            </div>
                            <label class="form-label col-12 m-0" for="productTitle1">Nama Produk <span
                                class="txt-danger">*</span></label>
                            <div class="col-12 custom-input">
                              <input class="form-control" id="productTitle1" type="text" required="">

                            </div>
                            <div class="col-12">
                              <div class="toolbar-box">
                                <div id="toolbar2">
                                  <span class="ql-formats">
                                    <select class="ql-size"></select>
                                  </span>
                                  <span class="ql-formats">
                                    <button class="ql-bold">Bold </button>
                                    <button class="ql-italic">Italic </button>
                                    <button class="ql-underline">underline</button>
                                    <button class="ql-strike">Strike </button>
                                  </span>
                                  <span class="ql-formats">
                                    <button class="ql-list" value="ordered">List </button>
                                    <button class="ql-list" value="bullet"> </button>
                                    <button class="ql-indent" value="-1"> </button>
                                    <button class="ql-indent" value="+1"></button>
                                  </span>
                                  <span class="ql-formats">
                                    <button class="ql-link"></button>
                                    <button class="ql-image"></button>
                                    <button class="ql-video"></button>
                                  </span>
                                </div>
                                <div id="editor2"></div>
                              </div>
                              <p class="f-light">Silahkan mengisi deskripsi produk di kotak ini.</p>
                            </div>

                            <div class="card mb-3">
                              <div class="card-body">
                                <div class="sidebar-body">
                                  <div class="product-upload">
                                    <p>Product Image </p>
                                    <div class="dropzone dropzone-light" id="multiFileUploadA" action="/upload.php">
                                      <div class="dz-message needsclick">
                                        <svg>
                                          <use href="../assets/svg/icon-sprite.svg#file-upload"></use>
                                        </svg>
                                        <h6>Drag your image here, or <a class="txt-primary" href="#!">browser</a></h6>
                                        <span class="note needsclick">SVG, PNG, JPG or GIF</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="product-upload">
                                    <p>Product Gallery</p>
                                    <div class="dropzone dropzone-light" id="multiFileUploadB" action="/upload.php">
                                      <div class="dz-message needsclick">
                                        <svg>
                                          <use href="../assets/svg/icon-sprite.svg#file-upload1"></use>
                                        </svg>
                                        <h6>Drag files here</h6>
                                        <span class="note needsclick">Add Product Gallery Images</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="card mb-3">
                              <div class="card-body">
                                <div class="sidebar-body">
                                  <div class="row g-lg-4 g-3">
                                    <div class="col-12">
                                      <div class="row g-3">
                                        <div class="col-sm-4">
                                          <div class="row g-2">
                                            <div class="col-12">
                                              <label class="form-label m-0" for="validationDefault04">Add
                                                Category</label>
                                            </div>
                                            <div class="col-12">
                                              <select class="form-select" id="validationDefault04" required="">
                                                <option selected="" value="">Toys & games</option>
                                                <option>Sportswear </option>
                                                <option>Jewellery </option>
                                                <option>Furniture and Decor</option>
                                                <option>Health, Personal Care, and Beauty</option>
                                                <option>Auto and Parts </option>
                                                <option>Baby Care Products</option>
                                              </select>
                                              <p class="f-light">A product can be added to a category</p>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-sm-4">
                                          <div class="row g-2">
                                            <div class="col-12">
                                              <label class="form-label m-0" for="categoryDropdown">Add Category</label>
                                            </div>
                                            <div class="col-12">
                                              <select class="form-select" id="categoryDropdown" required="">
                                                <optgroup label="Toys & Games">
                                                  <option value="Toy Cars">Toy Cars</option>
                                                  <option value="Puzzles">Puzzles</option>
                                                  <option value="Board Games">Board Games</option>
                                                </optgroup>
                                                <optgroup label="Sportswear">
                                                  <option value="Running Shoes">Running Shoes</option>
                                                  <option value="Fitness Wear">Fitness Wear</option>
                                                  <option value="Swimwear">Swimwear</option>
                                                </optgroup>
                                                <optgroup label="Jewellery">
                                                  <option value="Necklaces">Necklaces</option>
                                                  <option value="Earrings">Earrings</option>
                                                  <option value="Rings">Rings</option>
                                                </optgroup>
                                              </select>
                                              <p class="f-light">A product can be added to a category</p>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-sm-4">
                                          <div class="row g-2">
                                            <div class="col-12">
                                              <label class="form-label m-0" for="validationDefault04">Add
                                                Category</label>
                                            </div>
                                            <div class="col-12">
                                              <select class="form-select" id="validationDefault04" required="">
                                                <option selected="" value="">Toys & games</option>
                                                <option>Sportswear </option>
                                                <option>Jewellery </option>
                                                <option>Furniture and Decor</option>
                                                <option>Health, Personal Care, and Beauty</option>
                                                <option>Auto and Parts </option>
                                                <option>Baby Care Products</option>
                                              </select>
                                              <p class="f-light">A product can be added to a category</p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="row g-3">
                                        <!-- Additional fields can be added here -->
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="card">
                              <div class="card-body">
                                <div class="sidebar-body">
                                  <div class="row g-3 custom-input">
                                    <div class="col-sm-4">
                                      <label class="form-label" for="initialCost">Initial cost <span
                                          class="txt-danger">*</span></label>
                                      <input class="form-control" id="initialCost" type="number" required>
                                    </div>
                                    <div class="col-sm-4">
                                      <label class="form-label" for="sellingPrice">Selling price <span
                                          class="txt-danger">*</span></label>
                                      <input class="form-control" id="sellingPrice" type="number" required>
                                    </div>
                                    <div class="col-sm-4">
                                      <input id="ppnCheck" type="checkbox" name="tax" value="1" onclick="withPPN()">
                                      <label class="form-label" for="usetax">PPN</label>
                                      <input class="form-control" id="usetax" name="usetax" type="text" value="-"
                                        readonly>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="card mb-3">
                              <div class="card-body">
                                <div class="sidebar-body">
                                  <label for="">Component (Optional)</label>
                                  <div class="row g-lg-4 g-3" id="input-container">
                                    <div class="col-sm-5">
                                      <div class="row g-2">
                                        <div class="col-12">
                                          <label class="form-label m-0" for="sku1">Product ID (SKU)</label>
                                        </div>
                                        <div class="col-12">
                                          <input class="form-control" id="sku1" type="text"
                                            placeholder="Enter Product ID">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-sm-5">
                                      <div class="row g-2">
                                        <div class="col-12">
                                          <label class="form-label m-0" for="qty1">Qty</label>
                                        </div>
                                        <div class="col-12">
                                          <input class="form-control" id="qty1" type="number"
                                            placeholder="Enter Quantity">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-sm-2">
                                      <div class="row g-2">
                                        <div class="col-12">
                                          <label></label>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <script>
                                    let counter = 2;
                                    const inputContainer = document.getElementById('input-container');
                                    inputContainer.addEventListener('input', function () {
                                      const inputs = inputContainer.querySelectorAll('input');
                                      const allFilled = Array.prototype.every.call(inputs, function (input) {
                                        return input.value.trim() !== '';
                                      });
                                      if (allFilled) {
                                        const newInput = `
                                                <div class="input-group" id="group${counter}" style="margin-bottom: 15px;">
                                                    <div class="col-sm-5" style="padding-right: 15px;">
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <label class="form-label m-0" for="sku${counter}">Product ID (SKU)</label>
                                                            </div>
                                                            <div class="col-12">
                                                                <input class="form-control" id="sku${counter}" type="text" placeholder="Enter Product ID" style="margin-bottom: 10px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5" style="padding-right: 5px;">
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <label class="form-label m-0" for="qty${counter}">Qty</label>
                                                            </div>
                                                            <div class="col-12">
                                                                <input class="form-control" id="qty${counter}" type="number" placeholder="Enter Quantity" style="margin-bottom: 10px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <label></label>
                                                            </div>
                                                            <div class="col-12">
                                                                <button class="btn btn-danger" onclick="removeInput('group${counter}')">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p><span class="txt-danger">*</span>remove input jika tidak diperlukan</p>
                                                </div>
                                            `;
                                        inputContainer.insertAdjacentHTML('beforeend', newInput);
                                        counter++;
                                      }
                                    });
                                    function removeInput(groupId) {
                                      const inputGroup = document.getElementById(groupId);
                                      if (inputGroup) {
                                        inputGroup.remove();
                                      }
                                    }
                                  </script>
                                </div>
                              </div>
                            </div>

                            <div class="col-xl-12 col-sm-12 order-xl-0 order-sm-1">
                              <div class="card-wrapper border rounded-3 h-100 checkbox-checked">
                                <h6 class="sub-title">Icon Checkbox </h6>
                                <div class="form-check checkbox checkbox-primary ps-0 main-icon-checkbox">
                                  <ul class="checkbox-wrapper">
                                    <li>
                                      <input class="form-check-input checkbox-shadow" id="checkbox-icon"
                                        type="checkbox">
                                      <label class="form-check-label" for="checkbox-icon"><i
                                          class="fa fa-sliders"></i><span>Tokopedia</span></label>
                                    </li>
                                    <li>
                                      <input class="form-check-input checkbox-shadow" id="checkbox-icon1"
                                        type="checkbox" checked="">
                                      <label class="form-check-label" for="checkbox-icon1"><i
                                          class="fa fa-user"></i><span>Shopee</span></label>
                                    </li>
                                    <li>
                                      <input class="form-check-input checkbox-shadow" id="checkbox-icon2"
                                        type="checkbox">
                                      <label class="form-check-label" for="checkbox-icon2"><i
                                          class="fa fa-tags"></i><span>Tiktok</span></label>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>

                            <br>
                            <div class="card-footer">
                              <button class="btn btn-light" type="button" onclick="window.history.back();">Cancel
                              </button>
                              <button class="btn btn-primary m-r-15" type="submit">Submit</button>
                            </div>
                            <br>
                          </form> <!-- End of single form tag -->
                        </div>
                      </div>
                    </div>
                  </b>
                </div>
              </div>
            </div>

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



    /* Menghilangkan garis vertikal di antara dua kolom */
    .row.no-divider>[class^="col"] {
      border-right: none !important;
      /* Hilangkan border kanan */
    }

    .row.no-divider {
      gap: 0 !important;
      /* Hilangkan jarak antar kolom jika ada */
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
  <script src="../../../assets/js/flat-pickr/flatpickr.js"></script>
  <script src="../../../assets/js/flat-pickr/custom-flatpickr.js"></script>
  <script src="../../../assets/js/dropzone/dropzone.js"></script>
  <script src="../../../assets/js/dropzone/dropzone-script.js"></script>
  <script src="../../../assets/js/select2/tagify.js"></script>
  <script src="../../../assets/js/select2/tagify.polyfills.min.js"></script>
  <script src="../../../assets/js/select2/intltelinput.min.js"></script>
  <script src="../../../assets/js/add-product/select4-custom.js"></script>
  <script src="../../../assets/js/editors/quill.js"></script>
  <script src="../../../assets/js/custom-add-product.js"></script>
  <script src="../../../assets/js/height-equal.js"></script>
  <script src="../../../assets/js/tooltip-init.js"></script>
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