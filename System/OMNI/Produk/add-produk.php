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
  .header-table{
    border-top: 1px solid #DADCE0;
    border-bottom: 1px solid #DADCE0;
    padding:10px;
    /*padding-bottom:10px;*/
  }
  .body-table{
    /*border-top: 1px solid #DADCE0;*/
    border-bottom: 1px solid #DADCE0;
    padding:20px 10px 20px 10px;
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
                <div class="row"> 
                <div class="col-12"> 
                  <div class="card"> 
                    <div class="card-header">
                      <h3>Product Form</h3>
                    </div>
                    <div class="card-body">
                      <div class="row g-xl-5 g-3">
                        <div class="col-xxl-3 col-xl-4 box-col-4e sidebar-left-wrapper">
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
                                </div></a></li>
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
                                </div></a></li>
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
                                </div></a></li>
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
                                </div></a></li>
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
                                </div></a></li>
                          </ul>
                        </div>
                        <div class="col-xxl-9 col-xl-8 box-col-8 position-relative">
                              <div class="sidebar-body">
                                <form class="row g-2">
                                  <label class="form-label col-12 m-0" for="productID1">ID Produk (SKU) <span class="txt-danger"> *</span></label>
                                  <div class="col-12 custom-input">
                                    <input class="form-control is-invalid" id="productID1" type="text" required="">
                                    <div class="valid-feedback">Looks good!</div>
                                    <div class="invalid-feedback">A product id is required and recommended to be unique.</div>
                                  </div>
                                  <label class="form-label col-12 m-0" for="productTitle1">Nama Produk <span class="txt-danger"> *</span></label>
                                  <div class="col-12 custom-input">
                                    <input class="form-control is-invalid" id="productTitle1" type="text" required="">
                                    <div class="valid-feedback">Looks good!</div>
                                    <div class="invalid-feedback">A product name is required.</div>
                                  </div>
                                  <div class="col-12"> 
                                    <div class="toolbar-box">
                                      <div id="toolbar2"><span class="ql-formats">
                                          <select class="ql-size"></select></span><span class="ql-formats">
                                          <button class="ql-bold">Bold </button>
                                          <button class="ql-italic">Italic </button>
                                          <button class="ql-underline">underline</button>
                                          <button class="ql-strike">Strike </button></span><span class="ql-formats">
                                          <button class="ql-list" value="ordered">List </button>
                                          <button class="ql-list" value="bullet"> </button>
                                          <button class="ql-indent" value="-1"> </button>
                                          <button class="ql-indent" value="+1"></button></span><span class="ql-formats">
                                          <button class="ql-link"></button>
                                          <button class="ql-image"></button>
                                          <button class="ql-video"></button></span></div>
                                      <div id="editor2"></div>
                                    </div>
                                    <p class="f-light">Silahkan mengisi deskripsi produk di kotak ini.</p>
                                  </div>
                                </form>
                                <div class="product-buttons">
                                  <div class="btn">
                                    <div class="d-flex align-items-center gap-sm-2 gap-1">Next
                                      <svg>
                                        <use href="../../../assets/svg/icon-sprite.svg#front-arrow">  </use>
                                      </svg>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="sidebar-body">
                                <div class="product-upload">
                                  <p>Product Image </p>
                                  <form class="dropzone dropzone-light" id="multiFileUploadA" action="/upload.php">
                                    <div class="dz-message needsclick">
                                      <svg>
                                        <use href="../assets/svg/icon-sprite.svg#file-upload"></use>
                                      </svg>
                                      <h6>Drag your image here, or <a class="txt-primary" href="#!">browser</a></h6><span class="note needsclick">SVG,PNG,JPG or GIF</span>
                                    </div>
                                  </form>
                                </div>
                                <div class="product-upload">
                                  <p>Product Gallery</p>
                                  <form class="dropzone dropzone-light" id="multiFileUploadB" action="/upload.php">
                                    <div class="dz-message needsclick">
                                      <svg>
                                        <use href="../assets/svg/icon-sprite.svg#file-upload1"></use>
                                      </svg>
                                      <h6>Drag files here</h6><span class="note needsclick">Add Product Gallery Images</span>
                                    </div>
                                  </form>
                                </div>
                                <div class="product-buttons">
                                  <div class="btn">
                                    <div class="d-flex align-items-center gap-sm-2 gap-1">
                                      <svg>
                                        <use href="../assets/svg/icon-sprite.svg#back-arrow"></use>
                                      </svg>Previous
                                    </div>
                                  </div>
                                  <div class="btn">
                                    <div class="d-flex align-items-center gap-sm-2 gap-1">Next
                                      <svg>
                                        <use href="../assets/svg/icon-sprite.svg#front-arrow">     </use>
                                      </svg>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="sidebar-body">
                                <form>
                                  <div class="row g-lg-4 g-3">
                                    <div class="col-12">
                                      <div class="row g-3">
                                        <div class="col-sm-6">
                                          <div class="row g-2"> 
                                            <div class="col-12">
                                              <label class="form-label m-0" for="validationDefault04">Add Category</label>
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
                                        <div class="col-sm-6">
                                          <div class="row g-2 product-tag">
                                            <div class="col-12">
                                              <label class="form-label d-block m-0">Add Tag</label>
                                            </div>
                                            <div class="col-12">
                                              <input name="basic-tags" value="watches, sports, clothes, bottles">
                                              <p class="f-light">Products can be tagged</p>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-12">
                                          <div class="category-buton"><a class="btn button-primary" href="#!" data-bs-toggle="modal" data-bs-target="#category-pill-modal"><i class="me-2 fa fa-plus"> </i>Create New Category </a></div>
                                          <div class="modal fade" id="category-pill-modal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h3 class="modal-title fs-5">Create new category</h3>
                                                  <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body custom-input"> 
                                                  <div class="create-category">
                                                    <div>
                                                      <label class="form-label" for="categoryName">Category Name <span class="txt-danger"> *</span></label>
                                                      <input class="form-control m-0" id="categoryName" type="text" required="">
                                                      <div class="toolbar-box">
                                                        <div id="toolbar3"><span class="ql-formats">
                                                            <select class="ql-size"></select></span><span class="ql-formats">
                                                            <button class="ql-bold">Bold </button>
                                                            <button class="ql-italic">Italic </button>
                                                            <button class="ql-underline">underline</button>
                                                            <button class="ql-strike">Strike </button></span><span class="ql-formats">
                                                            <button class="ql-list" value="ordered">List </button>
                                                            <button class="ql-list" value="bullet"> </button>
                                                            <button class="ql-indent" value="-1"> </button>
                                                            <button class="ql-indent" value="+1"></button></span><span class="ql-formats">
                                                            <button class="ql-link"></button>
                                                            <button class="ql-image"></button>
                                                            <button class="ql-video"></button></span></div>
                                                        <div id="editor3"></div>
                                                      </div>
                                                    </div>
                                                    <p class="f-light">Improve product visibility by adding a compelling description.</p>
                                                  </div>
                                                </div>
                                                <div class="modal-footer">
                                                  <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                                                  <button class="btn btn-primary" type="button">Add</button>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12"> 
                                      <div class="row g-3">
                                        <div class="col-sm-6">
                                          <div class="row">
                                            <div class="col-12">
                                              <label class="form-label" for="publishStatus">Publish Status</label>
                                              <select class="form-select" id="publishStatus" required="">
                                                <option selected="" value="">Publish</option>
                                                <option>Drafts</option>
                                                <option>Unpublish</option>
                                              </select>
                                              <p class="f-light">Choose the status</p>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-sm-6">
                                          <div class="row">
                                            <div class="col-12">
                                              <label class="form-label" for="datetime-local1">Publish Date & Time</label>
                                              <div class="input-group flatpicker-calender product-date">
                                                <input class="form-control" id="datetime-local1" type="date">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="product-buttons">
                                    <div class="btn">
                                      <div class="d-flex align-items-center gap-sm-2 gap-1">
                                        <svg>
                                          <use href="../assets/svg/icon-sprite.svg#back-arrow"></use>
                                        </svg>Previous
                                      </div>
                                    </div>
                                    <div class="btn">
                                      <div class="d-flex align-items-center gap-sm-2 gap-1">Next
                                        <svg>
                                          <use href="../assets/svg/icon-sprite.svg#front-arrow"></use>
                                        </svg>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </div>
                              <div class="sidebar-body">
                                <form class="price-wrapper">
                                  <div class="row g-3 custom-input">
                                    <div class="col-sm-6"> 
                                      <label class="form-label" for="initialCost">Initial cost <span class="txt-danger">*</span></label>
                                      <input class="form-control" id="initialCost" type="number">
                                    </div>
                                    <div class="col-sm-6"> 
                                      <label class="form-label" for="sellingPrice">Selling price <span class="txt-danger">*</span></label>
                                      <input class="form-control" id="sellingPrice" type="number">
                                    </div>
                                    <div class="col-sm-6">
                                      <label class="form-label">Choose your currency</label>
                                      <select class="form-select" aria-label="Default select example">
                                        <option selected="">Dollar $</option>
                                        <option value="1">Euro €</option>
                                        <option value="2">Rupees ₹</option>
                                        <option value="3">British pounds £</option>
                                        <option value="4">Russian Ruble ₽</option>
                                        <option value="5">Japanese Yen ¥</option>
                                        <option value="6">Singapore Dollar S$</option>
                                      </select>
                                    </div>
                                    <div class="col-sm-6"> 
                                      <label class="form-label" for="productStock1">Product stocks<span class="txt-danger">*</span></label>
                                      <input class="form-control" id="productStock1" type="number">
                                    </div>
                                    <div class="col-12">
                                      <label class="form-label">Types of product discount<i class="icon-help-alt ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Choose the kind of discount that will be used on that particular item."></i></label>
                                      <ul class="radio-wrapper"> 
                                        <li> 
                                          <input class="form-check-input" id="radio-icon" type="radio" name="radio5" value="option5">
                                          <label class="form-check-label" for="radio-icon"><span>Fixed Price</span></label>
                                        </li>
                                        <li> 
                                          <input class="form-check-input" id="radio-icon4" type="radio" name="radio5" value="option5" checked="">
                                          <label class="form-check-label" for="radio-icon4"><span>BOGO(Buy one, Get one)</span></label>
                                        </li>
                                        <li> 
                                          <input class="form-check-input" id="radio-icon5" type="radio" name="radio5" value="option5">
                                          <label class="form-check-label" for="radio-icon5"><span>Seasonal or holiday discount</span></label>
                                        </li>
                                        <li> 
                                          <input class="form-check-input" id="radio-icon6" type="radio" name="radio5" value="option5">
                                          <label class="form-check-label" for="radio-icon6"><span>Percentage-based discount(%)</span></label>
                                        </li>
                                        <li> 
                                          <input class="form-check-input" id="radio-icon7" type="radio" name="radio5" value="option5">
                                          <label class="form-check-label" for="radio-icon7"><span>Volume or bulk discount</span></label>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                  <div class="product-buttons">
                                    <div class="btn">
                                      <div class="d-flex align-items-center gap-sm-2 gap-1">
                                        <svg>
                                          <use href="../assets/svg/icon-sprite.svg#back-arrow"></use>
                                        </svg>Previous
                                      </div>
                                    </div>
                                    <div class="btn">
                                      <div class="d-flex align-items-center gap-sm-2 gap-1">Next
                                        <svg>
                                          <use href="../assets/svg/icon-sprite.svg#front-arrow">   </use>
                                        </svg>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </div>
                              <div class="sidebar-body advance-options">
                                <ul class="nav nav-tabs border-tab mb-0" id="advance-option-tab" role="tablist">
                                  <li class="nav-item"><a class="nav-link active" id="manifest-option-tab" data-bs-toggle="tab" href="#manifest-option" role="tab" aria-controls="manifest-option" aria-selected="true">Inventory</a></li>
                                  <li class="nav-item"><a class="nav-link" id="additional-option-tab" data-bs-toggle="tab" href="#additional-option" role="tab" aria-controls="additional-option" aria-selected="false">Additional Options</a></li>
                                  <li class="nav-item"><a class="nav-link" id="dropping-option-tab" data-bs-toggle="tab" href="#dropping-option" role="tab" aria-controls="dropping-option" aria-selected="false">Shipping</a></li>
                                </ul>
                                <div class="tab-content" id="advance-option-tabContent">
                                  <div class="tab-pane fade show active" id="manifest-option" role="tabpanel" aria-labelledby="manifest-option-tab">
                                    <div class="meta-body"> 
                                      <form id="advance-tab">
                                        <div class="row g-3 custom-input">
                                          <div class="col-sm-6">
                                            <label class="form-label">Stock Availability</label>
                                            <select class="form-select" aria-label="Default select example">
                                              <option selected="">In stock</option>
                                              <option value="1">Out of stock</option>
                                              <option value="2">Pre-order</option>
                                            </select>
                                          </div>
                                          <div class="col-sm-6">
                                            <label class="form-label">Low Stock</label>
                                            <select class="form-select" aria-label="Default select example">
                                              <option selected="">Low Stock (5 or less)</option>
                                              <option value="1">Low Stock (10 or less)</option>
                                              <option value="2">Low Stock (20 or less)</option>
                                              <option value="2">Low Stock (25 or less)</option>
                                              <option value="2">Low Stock (30 or less)</option>
                                            </select>
                                          </div>
                                          <div class="col-lg-3 col-sm-6">
                                            <label class="form-label" for="exampleFormControlInput1">SKU <span class="txt-danger">*</span></label>
                                            <input class="form-control" id="exampleFormControlInput1" type="text">
                                          </div>
                                          <div class="col-lg-3 col-sm-6">
                                            <label class="form-label" for="exampleFormControlInputa">Stock Quantity <span class="txt-danger">*</span></label>
                                            <input class="form-control" id="exampleFormControlInputa" type="number" value="7" min="0">
                                          </div>
                                          <div class="col-lg-3 col-sm-6"> 
                                            <label class="form-label" for="exampleFormControlInputb">Restock Date <span class="txt-danger">*</span></label>
                                            <input class="form-control" id="exampleFormControlInputb" type="number">
                                          </div>
                                          <div class="col-lg-3 col-sm-6"> 
                                            <label class="form-label" for="exampleFormControlInputc">Pre-Order <span class="txt-danger">*</span></label>
                                            <input class="form-control" id="exampleFormControlInputc" type="number">
                                          </div>
                                          <div class="col-12"> 
                                            <label class="form-label">Allow Backorders</label>
                                            <div class="form-check">
                                              <input class="form-check-input" id="gridCheck" type="checkbox">
                                              <label class="form-check-label m-0" for="gridCheck">This is a digital Product</label>
                                              <p class="f-light">Decide if the product is a digital or physical item. Shipping may be necessary for real-world items.</p>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="product-buttons">
                                          <div class="btn">
                                            <div class="d-flex align-items-center gap-sm-2 gap-1">
                                              <svg>
                                                <use href="../assets/svg/icon-sprite.svg#back-arrow"></use>
                                              </svg>Previous
                                            </div>
                                          </div>
                                          <div class="btn">
                                            <div class="d-flex align-items-center gap-sm-2 gap-1">Next
                                              <svg>
                                                <use href="../assets/svg/icon-sprite.svg#front-arrow"></use>
                                              </svg>
                                            </div>
                                          </div>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                  <div class="tab-pane fade" id="additional-option" role="tabpanel" aria-labelledby="additional-option-tab">
                                    <div class="meta-body">
                                      <form>
                                        <div class="row g-3"> 
                                          <div class="col-12"> 
                                            <div class="row g-3">
                                              <div class="col-sm-6">
                                                <div class="row custom-input">
                                                  <div class="col-12">
                                                    <label class="form-label" for="tagTitle">Additional Tag Title</label>
                                                  </div>
                                                  <div class="col-12">
                                                    <input class="form-control" id="tagTitle" type="text">
                                                    <p class="f-light">Add a new tag title. Keywords should be simple and accurate.</p>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="col-sm-6">
                                                <div class="row product-tag">
                                                  <label class="form-label col-12">Specific Tags</label>
                                                  <div class="col-12"> 
                                                    <input id="specificTag" name="basic-tags1" value="watches, sports, clothes, bottles">
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="col-12">
                                                <div class="row">
                                                  <div class="col-12">
                                                    <label class="form-label col-12">Additional Description</label>
                                                    <div class="toolbar-box">
                                                      <div id="toolbar4"><span class="ql-formats">
                                                          <select class="ql-size"></select></span><span class="ql-formats">
                                                          <button class="ql-bold">Bold </button>
                                                          <button class="ql-italic">Italic </button>
                                                          <button class="ql-underline">underline</button>
                                                          <button class="ql-strike">Strike </button></span><span class="ql-formats">
                                                          <button class="ql-list" value="ordered">List </button>
                                                          <button class="ql-list" value="bullet"> </button>
                                                          <button class="ql-indent" value="-1"> </button>
                                                          <button class="ql-indent" value="+1"></button></span><span class="ql-formats">
                                                          <button class="ql-link"></button>
                                                          <button class="ql-image"></button>
                                                          <button class="ql-video"></button></span></div>
                                                      <div id="editor4"></div>
                                                    </div>
                                                    <p class="f-light">Enhance your SEO ranking with an added tag description for the product.</p>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="product-buttons">
                                          <div class="btn">
                                            <div class="d-flex align-items-center gap-sm-2 gap-1">
                                              <svg>
                                                <use href="../assets/svg/icon-sprite.svg#back-arrow"></use>
                                              </svg>Previous
                                            </div>
                                          </div>
                                          <div class="btn">
                                            <div class="d-flex align-items-center gap-sm-2 gap-1">Next
                                              <svg>
                                                <use href="../assets/svg/icon-sprite.svg#front-arrow"></use>
                                              </svg>
                                            </div>
                                          </div>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                  <div class="tab-pane fade" id="dropping-option" role="tabpanel" aria-labelledby="dropping-option-tab">
                                    <div class="meta-body">
                                      <form>
                                        <div class="row g-3 custom-input">
                                          <div class="col-12">
                                            <div class="row gx-xl-3 gx-md-2 gy-md-0 g-2">
                                              <div class="col-12">
                                                <label class="form-label" for="exampleFormControlInput1">Where can I pick up my order?</label>
                                              </div>
                                              <div class="col-md-4 col-sm-6">
                                                <input class="form-control" id="inputZip" type="number" placeholder="Zip code (10001)">
                                              </div>
                                              <div class="col-md-4 col-sm-6">
                                                <input class="form-control" id="inputCity" type="text" placeholder="City">
                                              </div>
                                              <div class="col-md-4">
                                                <select class="form-select" id="inputState">
                                                  <option selected="">State</option>
                                                  <option>Gujarat</option>
                                                  <option>Punjab</option>
                                                  <option>Himachal pradesh</option>
                                                  <option>Goa </option>
                                                  <option>Sikkim </option>
                                                  <option>Telangana</option>
                                                </select>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-12">
                                            <div class="row">
                                              <div class="col-12">
                                                <label class="form-label" for="exampleFormControlInput1">Weight (kg)</label><i class="icon-help-alt ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="set proper weight for product items."></i>
                                              </div>
                                              <div class="col-12">
                                                <input class="form-control" id="inputCitya" type="number">
                                                <p class="f-light">Decide if the product is a digital or physical item. Shipping may be necessary for real-world items.</p>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-12">
                                            <div class="row gx-xl-3 gx-md-2 gy-md-0 g-2">
                                              <div class="col-12">
                                                <label class="form-label" for="exampleFormControlInput1">Dimensions </label><i class="icon-help-alt ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="set proper length/width and height for product items."></i>
                                              </div>
                                              <div class="col-md-4 col-sm-6"> 
                                                <input class="form-control" id="inputCityb" type="number" placeholder="Length[l]">
                                              </div>
                                              <div class="col-md-4 col-sm-6"> 
                                                <input class="form-control" id="inputCityc" type="number" placeholder="Width[w]">
                                              </div>
                                              <div class="col-md-4"> 
                                                <input class="form-control" id="inputCityd" type="number" placeholder="Height[h]">
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-12">
                                            <div class="row"> 
                                              <div class="col-12">
                                                <label class="form-label" for="exampleFormControlInput1">Shipping Class</label>
                                              </div>
                                              <div class="col-md-12">
                                                <select class="form-select" id="inputState1">
                                                  <option selected="">Basic Shipping</option>
                                                  <option>Expedited Shipping</option>
                                                  <option>International Shipping</option>
                                                  <option>Free Shipping</option>
                                                  <option>Same-Day or Next-Day Shipping</option>
                                                  <option>Flat Rate Shipping</option>
                                                  <option>Local Pickup </option>
                                                </select>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="product-buttons">
                                          <div class="btn">
                                            <div class="d-flex align-items-center gap-sm-2 gap-1">
                                              <svg>
                                                <use href="../assets/svg/icon-sprite.svg#back-arrow"></use>
                                              </svg>Previous 
                                            </div>
                                          </div>
                                          <div class="btn">
                                            <div class="d-flex align-items-center gap-sm-2 gap-1">Submit
                                              <svg>
                                                <use href="../assets/svg/icon-sprite.svg#front-arrow"></use>
                                              </svg>
                                            </div>
                                          </div>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
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