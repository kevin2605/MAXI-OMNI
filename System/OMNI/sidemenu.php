<div class="sidebar-wrapper" data-layout="fill-svg">
    <div>
        <div class="logo-wrapper"><a href="../Dashboard"><img width="50%" class="img-fluid"
                    src="../../../assets/images/logo-icon.png" alt=""></a>
            <div class="toggle-sidebar">
                <svg class="sidebar-toggle">
                    <use href="../../../assets/svg/icon-sprite.svg#toggle-icon"></use>
                </svg>
            </div>
        </div>
        <div class="logo-icon-wrapper"><a href="index.html"><img width="50px" class="img-fluid"
                    src="../../../assets/images/logo-icon.png" alt=""></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"><a href="index.html"><img class="img-fluid"
                                src="../../../assets/images/logo/logo-icon.png" alt=""></a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>
                    <li class="pin-title sidebar-main-title">
                        <div>
                            <h6>Pinned</h6>
                        </div>
                    </li>
                    <!-- HOME -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>HOME</h6>
                        </div>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa fa-thumb-tack"></i>
                        <a id="account-link" class="sidebar-link sidebar-title link-nav" href="../Dashboard/">
                            <span style="font-size: 1rem; color: white; width: fit-content;">
                                <i class="icofont icofont-home"></i>
                            </span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <!-- PRODUK -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>PRODUK</h6>
                        </div>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa fa-thumb-tack"></i>
                        <a id="account-link" class="sidebar-link sidebar-title link-nav" href="../Produk/">
                            <span style="font-size: 1rem; color: white; width: fit-content;">
                                <i class="fa fa-cubes"></i>
                            </span>
                            <span>Daftar Produk</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa fa-thumb-tack"></i>
                        <a id="account-link" class="sidebar-link sidebar-title link-nav"
                            href="../Produk/add-produk.php">
                            <span style="font-size: 1rem; color: white; width: fit-content;">
                                <i class="fa fa-plus-circle"></i>
                            </span>
                            <span>Tambah Produk</span>
                        </a>
                    </li>
                    <!--
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="#">
                            <span style="font-size: 1rem; color: white; width: fit-content;"><i
                                    class="fa fa-cubes"></i></span><span>Produk</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="#">Tambah Produk
                                    <h5 class="sub-arrow"><i class="fa fa-angle-right"></i></h5>
                                </a>
                            </li>
                            <li><a href="../Produk/">Daftar Produk
                                    <h5 class="sub-arrow"><i class="fa fa-angle-right"></i></h5>
                                </a>
                            </li>
                        </ul>
                    </li>
                    -->
                    <!-- PESANAN -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>PESANAN</h6>
                        </div>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa fa-thumb-tack"></i>
                        <a id="account-link" class="sidebar-link sidebar-title link-nav" href="../Order/new-order.php">
                            <span style="font-size: 1rem; color: white; width: fit-content;">
                                <i class="icofont icofont-basket"></i>
                            </span>
                            <span>Pesanan Baru</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa fa-thumb-tack"></i>
                        <a id="account-link" class="sidebar-link sidebar-title link-nav"
                            href="../Order/processed-order.php">
                            <span style="font-size: 1rem; color: white; width: fit-content;">
                                <i class="fa fa-plus-circle"></i>
                            </span>
                            <span>Pesanan Diproses</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa fa-thumb-tack"></i>
                        <a id="account-link" class="sidebar-link sidebar-title link-nav"
                            href="../Order/finish-order.php">
                            <span style="font-size: 1rem; color: white; width: fit-content;">
                                <i class="fa fa-plus-circle"></i>
                            </span>
                            <span>Pesanan Selesai</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa fa-thumb-tack"></i>
                        <a id="account-link" class="sidebar-link sidebar-title link-nav"
                            href="../Order/cancel-order.php">
                            <span style="font-size: 1rem; color: white; width: fit-content;">
                                <i class="fa fa-plus-circle"></i>
                            </span>
                            <span>Pesanan Batal</span>
                        </a>
                    </li>
                    <!--
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                        <a class="sidebar-link sidebar-title" href="#">
                            <span style="font-size: 1rem; color: white; width: fit-content;">
                                <i class="icofont icofont-list"></i>
                            </span>
                            <span>Pesanan</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="#">Pesanan Baru
                                    <h5 class="sub-arrow"><i class="fa fa-angle-right"></i></h5>
                                </a>
                            </li>
                            <li><a href="#">Pesanan Diproses
                                    <h5 class="sub-arrow"><i class="fa fa-angle-right"></i></h5>
                                </a>
                            </li>
                            <li><a href="#">Pesanan Selesai
                                    <h5 class="sub-arrow"><i class="fa fa-angle-right"></i></h5>
                                </a>
                            </li>
                        </ul>
                    </li>
                    -->
                    <!-- SETTING -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>SETTING</h6>
                        </div>
                    </li>
                    <li class="sidebar-list">
                        <i class="fa fa-thumb-tack"></i>
                        <a id="account-link" class="sidebar-link sidebar-title link-nav"
                            href="../Accounting/chartofaccount.php">
                            <span style="font-size: 1rem; color: white; width: fit-content;">
                                <i class="icofont icofont-gears"></i>
                            </span>
                            <span>Setting</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>