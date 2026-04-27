<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>@yield('page_title')</title>

    <!-- Fontfaces CSS-->
    <link href="{{ asset('assets/css/font-face.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/fontawesome-7.1.0/css/all.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet"
        media="all">

    <!-- Bootstrap CSS-->
    <link href="{{ asset('assets/vendor/bootstrap-5.3.8.min.css') }}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{ asset('assets/css/aos.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/css/swiper-bundle-12.0.3.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('assets/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css') }}" rel="stylesheet"
        media="all">

    <!-- Main CSS-->
    <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" media="all">

</head>

<body>
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        @include('admin.include.mediamodal')
        @include('admin.include.eventmodal')
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
                            {{-- <img src="images/icon/logo.png" alt="CoolAdmin" /> --}}
                            {{ Config::get('constans.site_name') }}
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="has-sub @yield('dashboard_select')">
                            <a href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <li class="has-sub @yield('calender_select')">
                            <a href="{{ route('calendar') }}">
                                <i class="fas fa-tachometer-alt"></i>Calendar</a>
                        </li>
                        <li class="has-sub @yield('category_select')">
                            <a href="{{ route('category') }}">
                                <i class="fa-solid fa-layer-group"></i>category</a>
                        </li>
                        <li class="has-sub @yield('coupon_select')">
                            <a href="{{ route('coupons') }}">
                                <i class="fa-solid fa-ticket"></i>Coupons</a>
                        </li>
                        <li class="has-sub @yield('size_select')">
                            <a href="{{ route('size') }}">
                                <i class="fa-solid fa-minimize"></i>size</a>
                        </li>
                        <li class="has-sub @yield('color_select')">
                            <a href="{{ route('color') }}">
                                <i class="fa-solid fa-fill-drip"></i>color</a>
                        </li>
                        <li class="has-sub @yield('brands_select')">
                            <a href="{{ route('brands') }}">
                                <i class="fa-solid fa-fill-drip"></i>Brands</a>
                        </li>
                        {{-- Product Submenu --}}
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fa-brands fa-product-hunt"></i>Product
                            </a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list ps-sm-5">
                                <li>
                                    <a href="{{ route('product') }}">
                                        <i class="fas fa-sign-in-alt"></i>All Product
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.manage') }}">
                                        <i class="fas fa-user"></i>Add Product
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.linkproduct') }}">
                                        <i class="fas fa-user"></i>Link Product
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.addlinkproduct') }}">
                                        <i class="fas fa-user"></i>Add Link Product
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.tecnicalspacs') }}">
                                        <i class="fas fa-user"></i>Tecnical Specs
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.addtecnicalspecs') }}">
                                        <i class="fas fa-user"></i>Add Tecnical Specs
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- Media Submenu --}}
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Media
                            </a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list ps-sm-5">
                                <li>
                                    <a href="{{ route('media') }}">
                                        <i class="fas fa-sign-in-alt"></i>Library
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('media.managemedia') }}">
                                        <i class="fas fa-user"></i>Add Media File
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-sub @yield('customer_select')">
                            <a href="{{ route('customer') }}">
                                <i class="fa-solid fa-fill-drip"></i>Customers</a>
                        </li>
                        <li class="has-sub @yield('customer_select')">
                            <a href="{{ route('report') }}">
                                <i class="fa-solid fa-fill-drip"></i>Report</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    {{-- <img src="images/icon/logo.png" alt="Cool Admin" /> --}}
                    {{ Config::get('constans.site_name') }}
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li class="has-sub @yield('dashboard_select')">
                            <a href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <li class="has-sub @yield('calender_select')">
                            <a href="{{ route('calendar') }}">
                                <i class="fa-regular fa-calendar-days"></i>Calender</a>
                        </li>
                        <li class="has-sub @yield('category_select')">
                            <a href="{{ route('category') }}">
                                <i class="fa-solid fa-layer-group"></i>Category</a>
                        </li>
                        <li class="has-sub @yield('coupon_select')">
                            <a href="{{ route('coupons') }}">
                                <i class="fa-solid fa-ticket"></i>Coupons</a>
                        </li>
                        <li class="has-sub @yield('size_select')">
                            <a href="{{ route('size') }}">
                                <i class="fa-solid fa-minimize"></i>size</a>
                        </li>
                        <li class="has-sub @yield('color_select')">
                            <a href="{{ route('color') }}">
                                <i class="fa-solid fa-fill-drip"></i>color</a>
                        </li>
                        <li class="has-sub @yield('brands_select')">
                            <a href="{{ route('brands') }}">
                                <i class="fa-solid fa-fill-drip"></i>Brans</a>
                        </li>
                        {{-- <li class="has-sub @yield('product_select')">
                            <a href="{{ route('product') }}">
                                <i class="fa-brands fa-product-hunt"></i>product</a>
                        </li> --}}
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>product
                                {{-- <span class="arrow">
                                    <i class="fas fa-angle-down"></i>
                                </span> --}}
                            </a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="{{ route('product') }}">
                                        <i class="fas fa-sign-in-alt"></i>All Product</a>
                                </li>
                                <li>
                                    <a href="{{ route('product.manage') }}">
                                        <i class="fas fa-user"></i>Add Product</a>
                                </li>
                                <li>
                                    <a href="{{ route('product.linkproduct') }}">
                                        <i class="fas fa-user"></i>Link Product</a>
                                </li>
                                <li>
                                    <a href="{{ route('product.addlinkproduct') }}">
                                        <i class="fas fa-user"></i> Add Link Product</a>
                                </li>
                                <li>
                                    <a href="{{ route('product.tecnicalspacs') }}">
                                        <i class="fas fa-user"></i>Tecnical Specs</a>
                                </li>
                                <li>
                                    <a href="{{ route('product.addtecnicalspecs') }}">
                                        <i class="fas fa-user"></i> Add Tecnical Specs</a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Media
                            </a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="{{ route('media') }}">
                                        <i class="fas fa-sign-in-alt"></i>Library</a>
                                </li>
                                <li>
                                    <a href="{{ route('media.managemedia') }}">
                                        <i class="fas fa-user"></i>Add Media File</a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-sub @yield('customer_select')">
                            <a href="{{ route('customer') }}">
                                <i class="fa-solid fa-fill-drip"></i>Customers</a>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Report
                            </a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="{{ route('admin.reportsView') }}">
                                        <i class="fas fa-sign-in-alt"></i>View</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.reports') }}">
                                        <i class="fas fa-user"></i>Add</a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fa-solid fa-gear"></i>Settings
                            </a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="{{ route('admin.settings') }}">
                                        <i class="fa-solid fa-address-card"></i>Profile View</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.change.password') }}">
                                        <i class="fa-solid fa-key"></i>Password</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header position-relative" onsubmit="return false;">
                                <input class="au-input au-input--xl" type="text" id="adminSearch"
                                    placeholder="Search for datas & reports..." autocomplete="off" />

                                <button class="au-btn--submit" type="button">
                                    <i class="zmdi zmdi-search"></i>
                                </button>

                                <!-- Search dropdown -->
                                <ul class="list-group position-absolute w-100 shadow" id="searchResult"
                                    style="top: 100%; z-index: 1000;"></ul>
                            </form>
                            <div class="header-button">
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">

                                        <div class="content">
                                            <a class="js-acc-btn" href="#">john doe</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#">john doe</a>
                                                    </h5>
                                                    <span class="email">johndoe@example.com</span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-account"></i>Account</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-money-box"></i>Billing</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="logout">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- END HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    @include('admin.include.notify')
                    @section('container')
                    @show
                </div>
            </div>
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="{{ asset('assets/js/vanilla-utils.js') }}"></script>
    <!-- Bootstrap JS-->
    <script src="{{ asset('assets/vendor/bootstrap-5.3.8.bundle.min.js') }}"></script>
    <!-- Vendor JS       -->
    <script src="{{ asset('assets/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chartjs/chart.umd.js-4.5.1.min.js') }}"></script>

    <!-- Main JS-->
    <script src="{{ asset('assets/js/bootstrap5-init.js') }}"></script>
    <script src="{{ asset('assets/js/main-vanilla.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle-12.0.3.min.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('assets/js/modern-plugins.js') }}"></script>
    {{-- Include js --}}
    <script src="{{ asset('assets/js/include.js') }}"></script>
    <script src="{{ asset('assets/js/ul-include.js') }}"></script>
    <script src="{{ asset('assets/js/mediaModal.js') }}"></script>
    {{-- include jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- FullCalendar v6.1.11 -->
    <script src="{{ asset('assets/vendor/fullcalendar-6.1.11/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/js/calendar.js') }}"></script>

</body>

</html>
<!-- end document-->
