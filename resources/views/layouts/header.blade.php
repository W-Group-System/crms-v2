<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@yield('title', 'Customer Relationship Management System')</title>

        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
        
        <link href="{{ asset('css/feather.css') }}" rel="stylesheet">
        <link href="{{ asset('css/themify-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('css/vendor.bundle.base.css') }}" rel="stylesheet">
        <link href="{{ asset('css/materialdesignicons.min.css') }}" rel="stylesheet">
        
        <link href="{{ asset('css/dataTables.bootstrap4.css') }}" rel="stylesheet">
        <link href="{{ asset('css/select.dataTables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/select2-bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/buttons.bootstrap4.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <style>
        .pagination
        {
            margin-top: 10px;
            float: right;
        }
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button 
        {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] 
        {
            -moz-appearance: textfield;
        }
    </style>
    @yield('css')
    <body>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="{{ asset('/images/crms2.png')}}" class="mr-2" alt="logo"/></a>
                    <a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{ asset('/images/crms2.png')}}" alt="logo"/></a>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="{{ asset('/images/user.png')}}" alt="profile"/>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a href="{{ route('change_password') }}" class="dropdown-item">
                                    <i class="ti-settings text-primary"></i>
                                    Change Password
                                </a>
                                <!-- <a class="dropdown-item">
                                    <i class="ti-power-off text-primary"></i>
                                    Logout
                                </a> -->
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ti-power-off text-primary"></i>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="icon-menu"></span>
                    </button>
                </div>
            </nav>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial -->
                <!-- partial:partials/_sidebar.html -->
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">
                                <i class="icon-grid menu-icon"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#table_product" aria-expanded="false" aria-controls="table_product" onclick="toggleTablesProduct(event)">
                                <i class="icon-layout menu-icon"></i>
                                <span class="menu-title">Product Management</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="table_product">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_products" aria-expanded="false" aria-controls="nav_products" onclick="toggleProducts(event)">
                                            <span class="menu-title">Products</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    <!-- <li class="nav-item"><a class="nav-link" href="">Certificate of Analysis</a></li> -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#table_pricing2" aria-expanded="false" aria-controls="table_pricing2" onclick="toggleSetupPricing(event)">
                                            <span class="menu-title">Pricing</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#table_product2" aria-expanded="false" aria-controls="table_product2" onclick="toggleSetupProduct(event)">
                                            <span class="menu-title">Setup</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="collapse" id="nav_products">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/current_products') }}">Current Products</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/new_products') }}">New Products</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/draft_products') }}">Draft Products</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/archived_products') }}">Archived Products</a></li>
                                </ul>
                            </div>
                            <div class="collapse" id="table_pricing2">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/base_price') }}">Current Base Price</a></li>
                                    <!-- <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/project_name') }}">New Base Price</a></li> -->
                                </ul>
                            </div>
                            <div class="collapse" id="table_product2">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/product_applications') }}">Product Applications</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/product_subcategories') }}">Product Subcategories</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/raw_material') }}">Raw Materials</a></li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                                <i class="icon-columns menu-icon"></i>
                                <span class="menu-title">Product Information</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="form-elements">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/current_products') }}">Products</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                                <i class="icon-bar-graph menu-icon"></i>
                                <span class="menu-title">Client Information</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="charts">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/client') }}">Current</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/client_prospect') }}">Prospects</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/client_archived') }}">Archived</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#tables" aria-expanded="false" aria-controls="tables" onclick="toggleTables(event)">
                                <i class="icon-grid-2 menu-icon"></i>
                                <span class="menu-title">Client Transaction</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="tables">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/customer_requirement') }}">Customer Requirement</a></li> 
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/product_evaluation') }}">Request for Product Evaluation</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/sample_request') }}">Sample Request Form</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/price_monitoring') }}">Price Monitoring</a></li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#tables2" aria-expanded="false" aria-controls="tables2" onclick="toggleSetup(event)">
                                            <span class="menu-title">Setup</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- Separate collapse for setup submenu -->
                        <div class="collapse" id="tables2">
                            <ul class="nav flex-column sub-menu">
                                <!-- <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/setup_item1') }}">Categorization</a></li> -->
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/project_name') }}">Project Name</a></li>
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/nature_request') }}">Nature of Request</a></li>
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/crr_priority') }}">CRR Priority</a></li>
                            </ul>
                        </div>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#table_service" aria-expanded="false" aria-controls="table_service" onclick="toggleTablesService(event)">
                                <i class="icon-grid-2 menu-icon"></i>
                                <span class="menu-title">Service Management</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="table_service">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="{{ url('/customer_complaint') }}">Customer Complaints</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="{{ url('/customer_feedback') }}">Customer Feedbacks</a></li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#table_service2" aria-expanded="false" aria-controls="table_service2" onclick="toggleSetupService(event)">
                                            <span class="menu-title">Setup</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- Separate collapse for setup submenu -->
                        <div class="collapse" id="table_service2">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/issue_category') }}">Issue Category</a></li>
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('concern_department') }}">Concerned Department</a></li>
                            </ul>
                        </div>
                        <!-- <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                                <i class="icon-contract menu-icon"></i>
                                <span class="menu-title">Service Management</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="icons">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="{{ url('/customer_complaint') }}">Customer Complaints</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="">Customer Feedbacks</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="">Setup</a></li>
                                </ul>
                            </div>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/activities') }}">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Activities</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#module" aria-expanded="false" aria-controls="module" onclick="toggleModule(event)">
                                <i class="icon-layout menu-icon"></i>
                                <span class="menu-title">Module Setup</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="module">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link" href="">R&D</a></li>
                                    <li class="nav-item"><a class="nav-link" href="">Accounting Users</a></li>
                                    <li class="nav-item"><a class="nav-link" href="">Production Users</a></li>
                                    <li class="nav-item"><a class="nav-link" href="">Sales</a></li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_location" aria-expanded="false" aria-controls="nav_location" onclick="toggleLocation(event)">
                                            <span class="menu-title">Location</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_business" aria-expanded="false" aria-controls="nav_business" onclick="toggleBusiness(event)">
                                            <span class="menu-title">Business</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_payment_currency" aria-expanded="false" aria-controls="nav_payment_currency" onclick="togglePaymentCurrency(event)">
                                            <span class="menu-title">Payment Currency</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_accounting" aria-expanded="false" aria-controls="nav_accounting" onclick="toggleSetupAccounting(event)">
                                            <span class="menu-title">Accounting</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="collapse" id="nav_location">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/region') }}">Region</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/country') }}">Country</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/area') }}">Area</a></li>
                                </ul>
                            </div>
                            <div class="collapse" id="nav_business">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/business_type') }}">Business Type</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('industry') }}">Industry</a></li>
                                </ul>
                            </div>
                            <div class="collapse" id="nav_payment_currency">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/price_currency') }}">Price Currencies</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('currency_exchange') }}">Currency Exchange Rates</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('payment_terms') }}">Payment Terms</a></li>
                                </ul>
                            </div>
                            <div class="collapse" id="nav_accounting">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/fixed_cost') }}">Price Request Fixed Cost</a></li>
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/request_gae') }}">Price Request GAE</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" data-toggle="collapse" href="#setup" aria-expanded="false" aria-controls="setup">
                                <i class="icon-cog menu-icon"></i>
                                <span class="menu-title">Setup</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="setup">
                                <ul class="nav flex-column sub-menu">
                                    <!-- <li class="nav-item"><a class="nav-link" href="{{ url('/user') }}">User Accounts</a></li> -->
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/role') }}">Roles</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/company') }}">Company</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/department') }}">Department</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" data-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="reports">
                                <i class="icon-cog menu-icon"></i>
                                <span class="menu-title">Reports</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="reports">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link" href="#">Price Request Summary</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#">Transaction/Activity Summary</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper">
                    @yield('content')
                        <!-- <div class="row">
                            <div class="col-md-12 grid-margin">
                                <div class="row">
                                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                        <h3 class="font-weight-bold">Welcome {{auth()->user()->name}}</h3>
                                        <h6 class="font-weight-normal mb-0">All systems are running smoothly!</h6>
                                    </div>
                                    <div class="col-12 col-xl-4">
                                    <div class="justify-content-end d-flex">
                                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                        <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                        <a class="dropdown-item" href="#">January - March</a>
                                        <a class="dropdown-item" href="#">March - June</a>
                                        <a class="dropdown-item" href="#">June - August</a>
                                        <a class="dropdown-item" href="#">August - November</a>
                                        </div>
                                    </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    <!-- <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                        <div class="card tale-bg">
                            <div class="card-people mt-auto">
                            <img src="images/dashboard/people.svg" alt="people">
                            <div class="weather-info">
                                <div class="d-flex">
                                <div>
                                    <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>31<sup>C</sup></h2>
                                </div>
                                <div class="ml-2">
                                    <h4 class="location font-weight-normal">Bangalore</h4>
                                    <h6 class="font-weight-normal">India</h6>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6 grid-margin transparent">
                        <div class="row">
                            <div class="col-md-6 mb-4 stretch-card transparent">
                            <div class="card card-tale">
                                <div class="card-body">
                                <p class="mb-4">Today’s Bookings</p>
                                <p class="fs-30 mb-2">4006</p>
                                <p>10.00% (30 days)</p>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6 mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                <p class="mb-4">Total Bookings</p>
                                <p class="fs-30 mb-2">61344</p>
                                <p>22.00% (30 days)</p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                            <div class="card card-light-blue">
                                <div class="card-body">
                                <p class="mb-4">Number of Meetings</p>
                                <p class="fs-30 mb-2">34040</p>
                                <p>2.00% (30 days)</p>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6 stretch-card transparent">
                            <div class="card card-light-danger">
                                <div class="card-body">
                                <p class="mb-4">Number of Clients</p>
                                <p class="fs-30 mb-2">47033</p>
                                <p>0.22% (30 days)</p>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                            <p class="card-title">Order Details</p>
                            <p class="font-weight-500">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
                            <div class="d-flex flex-wrap mb-5">
                                <div class="mr-5 mt-3">
                                <p class="text-muted">Order value</p>
                                <h3 class="text-primary fs-30 font-weight-medium">12.3k</h3>
                                </div>
                                <div class="mr-5 mt-3">
                                <p class="text-muted">Orders</p>
                                <h3 class="text-primary fs-30 font-weight-medium">14k</h3>
                                </div>
                                <div class="mr-5 mt-3">
                                <p class="text-muted">Users</p>
                                <h3 class="text-primary fs-30 font-weight-medium">71.56%</h3>
                                </div>
                                <div class="mt-3">
                                <p class="text-muted">Downloads</p>
                                <h3 class="text-primary fs-30 font-weight-medium">34040</h3>
                                </div> 
                            </div>
                            <canvas id="order-chart"></canvas>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                            <div class="d-flex justify-content-between">
                            <p class="card-title">Sales Report</p>
                            <a href="#" class="text-info">View all</a>
                            </div>
                            <p class="font-weight-500">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
                            <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
                            <canvas id="sales-chart"></canvas>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                        <div class="card position-relative">
                            <div class="card-body">
                            <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2" data-ride="carousel">
                                <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="row">
                                    <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                        <div class="ml-xl-4 mt-3">
                                        <p class="card-title">Detailed Reports</p>
                                        <h1 class="text-primary">$34040</h1>
                                        <h3 class="font-weight-500 mb-xl-4 text-primary">North America</h3>
                                        <p class="mb-2 mb-xl-0">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
                                        </div>  
                                        </div>
                                    <div class="col-md-12 col-xl-9">
                                        <div class="row">
                                        <div class="col-md-6 border-right">
                                            <div class="table-responsive mb-3 mb-md-0 mt-3">
                                            <table class="table table-borderless report-table">
                                                <tr>
                                                <td class="text-muted">Illinois</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">713</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">Washington</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">583</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">Mississippi</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">924</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">California</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">664</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">Maryland</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">560</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">Alaska</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">793</h5></td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <canvas id="north-america-chart"></canvas>
                                            <div id="north-america-legend"></div>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="row">
                                    <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                        <div class="ml-xl-4 mt-3">
                                        <p class="card-title">Detailed Reports</p>
                                        <h1 class="text-primary">$34040</h1>
                                        <h3 class="font-weight-500 mb-xl-4 text-primary">North America</h3>
                                        <p class="mb-2 mb-xl-0">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
                                        </div>  
                                        </div>
                                    <div class="col-md-12 col-xl-9">
                                        <div class="row">
                                        <div class="col-md-6 border-right">
                                            <div class="table-responsive mb-3 mb-md-0 mt-3">
                                            <table class="table table-borderless report-table">
                                                <tr>
                                                <td class="text-muted">Illinois</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">713</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">Washington</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">583</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">Mississippi</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">924</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">California</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">664</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">Maryland</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">560</h5></td>
                                                </tr>
                                                <tr>
                                                <td class="text-muted">Alaska</td>
                                                <td class="w-100 px-0">
                                                    <div class="progress progress-md mx-4">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td><h5 class="font-weight-bold mb-0">793</h5></td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <canvas id="south-america-chart"></canvas>
                                            <div id="south-america-legend"></div>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                <a class="carousel-control-prev" href="#detailedReports" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#detailedReports" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                                </a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                            <p class="card-title mb-0">Top Products</p>
                            <div class="table-responsive">
                                <table class="table table-striped table-borderless">
                                <thead>
                                    <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    </tr>  
                                </thead>
                                <tbody>
                                    <tr>
                                    <td>Search Engine Marketing</td>
                                    <td class="font-weight-bold">$362</td>
                                    <td>21 Sep 2018</td>
                                    <td class="font-weight-medium"><div class="badge badge-success">Completed</div></td>
                                    </tr>
                                    <tr>
                                    <td>Search Engine Optimization</td>
                                    <td class="font-weight-bold">$116</td>
                                    <td>13 Jun 2018</td>
                                    <td class="font-weight-medium"><div class="badge badge-success">Completed</div></td>
                                    </tr>
                                    <tr>
                                    <td>Display Advertising</td>
                                    <td class="font-weight-bold">$551</td>
                                    <td>28 Sep 2018</td>
                                    <td class="font-weight-medium"><div class="badge badge-warning">Pending</div></td>
                                    </tr>
                                    <tr>
                                    <td>Pay Per Click Advertising</td>
                                    <td class="font-weight-bold">$523</td>
                                    <td>30 Jun 2018</td>
                                    <td class="font-weight-medium"><div class="badge badge-warning">Pending</div></td>
                                    </tr>
                                    <tr>
                                    <td>E-Mail Marketing</td>
                                    <td class="font-weight-bold">$781</td>
                                    <td>01 Nov 2018</td>
                                    <td class="font-weight-medium"><div class="badge badge-danger">Cancelled</div></td>
                                    </tr>
                                    <tr>
                                    <td>Referral Marketing</td>
                                    <td class="font-weight-bold">$283</td>
                                    <td>20 Mar 2018</td>
                                    <td class="font-weight-medium"><div class="badge badge-warning">Pending</div></td>
                                    </tr>
                                    <tr>
                                    <td>Social media marketing</td>
                                    <td class="font-weight-bold">$897</td>
                                    <td>26 Oct 2018</td>
                                    <td class="font-weight-medium"><div class="badge badge-success">Completed</div></td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-5 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">To Do Lists</h4>
                                                <div class="list-wrapper pt-2">
                                                    <ul class="d-flex flex-column-reverse todo-list todo-list-custom">
                                                        <li>
                                                            <div class="form-check form-check-flat">
                                                                <label class="form-check-label">
                                                                    <input class="checkbox" type="checkbox">
                                                                    Meeting with Urban Team
                                                                </label>
                                                            </div>
                                                            <i class="remove ti-close"></i>
                                                        </li>
                                                        <li class="completed">
                                                            <div class="form-check form-check-flat">
                                                                <label class="form-check-label">
                                                                    <input class="checkbox" type="checkbox" checked>
                                                                    Duplicate a project for new customer
                                                                </label>
                                                            </div>
                                                            <i class="remove ti-close"></i>
                                                        </li>
                                                        <li>
                                                            <div class="form-check form-check-flat">
                                                                <label class="form-check-label">
                                                                    <input class="checkbox" type="checkbox">
                                                                    Project meeting with CEO
                                                                </label>
                                                            </div>
                                                            <i class="remove ti-close"></i>
                                                        </li>
                                                        <li class="completed">
                                                            <div class="form-check form-check-flat">
                                                                <label class="form-check-label">
                                                                    <input class="checkbox" type="checkbox" checked>
                                                                    Follow up of team zilla
                                                                </label>
                                                            </div>
                                                            <i class="remove ti-close"></i>
                                                        </li>
                                                        <li>
                                                            <div class="form-check form-check-flat">
                                                                <label class="form-check-label">
                                                                    <input class="checkbox" type="checkbox">
                                                                    Level up for Antony
                                                                </label>
                                                            </div>
                                                            <i class="remove ti-close"></i>
                                                        </li>
                                                    </ul>
                            </div>
                            <div class="add-items d-flex mb-0 mt-2">
                                                    <input type="text" class="form-control todo-list-input"  placeholder="Add new task">
                                                    <button class="add btn btn-icon text-primary todo-list-add-btn bg-transparent"><i class="icon-circle-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 stretch-card grid-margin">
                        <div class="card">
                            <div class="card-body">
                            <p class="card-title mb-0">Projects</p>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                <thead>
                                    <tr>
                                    <th class="pl-0  pb-2 border-bottom">Places</th>
                                    <th class="border-bottom pb-2">Orders</th>
                                    <th class="border-bottom pb-2">Users</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td class="pl-0">Kentucky</td>
                                    <td><p class="mb-0"><span class="font-weight-bold mr-2">65</span>(2.15%)</p></td>
                                    <td class="text-muted">65</td>
                                    </tr>
                                    <tr>
                                    <td class="pl-0">Ohio</td>
                                    <td><p class="mb-0"><span class="font-weight-bold mr-2">54</span>(3.25%)</p></td>
                                    <td class="text-muted">51</td>
                                    </tr>
                                    <tr>
                                    <td class="pl-0">Nevada</td>
                                    <td><p class="mb-0"><span class="font-weight-bold mr-2">22</span>(2.22%)</p></td>
                                    <td class="text-muted">32</td>
                                    </tr>
                                    <tr>
                                    <td class="pl-0">North Carolina</td>
                                    <td><p class="mb-0"><span class="font-weight-bold mr-2">46</span>(3.27%)</p></td>
                                    <td class="text-muted">15</td>
                                    </tr>
                                    <tr>
                                    <td class="pl-0">Montana</td>
                                    <td><p class="mb-0"><span class="font-weight-bold mr-2">17</span>(1.25%)</p></td>
                                    <td class="text-muted">25</td>
                                    </tr>
                                    <tr>
                                    <td class="pl-0">Nevada</td>
                                    <td><p class="mb-0"><span class="font-weight-bold mr-2">52</span>(3.11%)</p></td>
                                    <td class="text-muted">71</td>
                                    </tr>
                                    <tr>
                                    <td class="pl-0 pb-0">Louisiana</td>
                                    <td class="pb-0"><p class="mb-0"><span class="font-weight-bold mr-2">25</span>(1.32%)</p></td>
                                    <td class="pb-0">14</td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                <p class="card-title">Charts</p>
                                <div class="charts-data">
                                    <div class="mt-3">
                                    <p class="mb-0">Data 1</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="progress progress-md flex-grow-1 mr-4">
                                        <div class="progress-bar bg-inf0" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="mb-0">5k</p>
                                    </div>
                                    </div>
                                    <div class="mt-3">
                                    <p class="mb-0">Data 2</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="progress progress-md flex-grow-1 mr-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="mb-0">1k</p>
                                    </div>
                                    </div>
                                    <div class="mt-3">
                                    <p class="mb-0">Data 3</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="progress progress-md flex-grow-1 mr-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 48%" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="mb-0">992</p>
                                    </div>
                                    </div>
                                    <div class="mt-3">
                                    <p class="mb-0">Data 4</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="progress progress-md flex-grow-1 mr-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="mb-0">687</p>
                                    </div>
                                    </div>
                                </div>  
                                </div>
                            </div>
                            </div>
                            <div class="col-md-12 stretch-card grid-margin grid-margin-md-0">
                            <div class="card data-icon-card-primary">
                                <div class="card-body">
                                <p class="card-title text-white">Number of Meetings</p>                      
                                <div class="row">
                                    <div class="col-8 text-white">
                                    <h3>34040</h3>
                                    <p class="text-white font-weight-500 mb-0">The total number of sessions within the date range.It is calculated as the sum . </p>
                                    </div>
                                    <div class="col-4 background-icon">
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                        <div class="card">
                            <div class="card-body">
                            <p class="card-title">Notifications</p>
                            <ul class="icon-data-list">
                                <li>
                                <div class="d-flex">
                                    <img src="images/faces/face1.jpg" alt="user">
                                    <div>
                                    <p class="text-info mb-1">Isabella Becker</p>
                                    <p class="mb-0">Sales dashboard have been created</p>
                                    <small>9:30 am</small>
                                    </div>
                                </div>
                                </li>
                                <li>
                                <div class="d-flex">
                                    <img src="images/faces/face2.jpg" alt="user">
                                    <div>
                                    <p class="text-info mb-1">Adam Warren</p>
                                    <p class="mb-0">You have done a great job #TW111</p>
                                    <small>10:30 am</small>
                                    </div>
                                </div>
                                </li>
                                <li>
                                <div class="d-flex">
                                <img src="images/faces/face3.jpg" alt="user">
                                <div>
                                <p class="text-info mb-1">Leonard Thornton</p>
                                <p class="mb-0">Sales dashboard have been created</p>
                                <small>11:30 am</small>
                                </div>
                                </div>
                                </li>
                                <li>
                                <div class="d-flex">
                                    <img src="images/faces/face4.jpg" alt="user">
                                    <div>
                                    <p class="text-info mb-1">George Morrison</p>
                                    <p class="mb-0">Sales dashboard have been created</p>
                                    <small>8:50 am</small>
                                    </div>
                                </div>
                                </li>
                                <li>
                                <div class="d-flex">
                                    <img src="images/faces/face5.jpg" alt="user">
                                    <div>
                                    <p class="text-info mb-1">Ryan Cortez</p>
                                    <p class="mb-0">Herbs are fun and easy to grow.</p>
                                    <small>9:00 am</small>
                                    </div>
                                </div>
                                </li>
                            </ul>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                            <p class="card-title">Advanced Table</p>
                            <div class="row">
                                <div class="col-12">
                                <div class="table-responsive">
                                    <table  class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                        <th>Quote#</th>
                                        <th>Product</th>
                                        <th>Business type</th>
                                        <th>Policy holder</th>
                                        <th>Premium</th>
                                        <th>Status</th>
                                        <th>Updated at</th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                </table>
                                </div>
                                </div>
                            </div>
                            </div>
                            </div>

                            
                        </div>
                        </div>
                    </div>
                    <footer class="footer">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.  Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
                            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
                        </div>
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a href="https://www.themewagon.com/" target="_blank">Themewagon</a></span> 
                        </div>
                    </footer>  -->
                    <!-- partial -->
                </div>
            <!-- main-panel ends -->
            </div>   
            <!-- page-body-wrapper ends -->
        </div>

        <style> 
            @font-face {
                font-family: "Material Design Icons";
                src: url("{{ asset('fonts/materialdesignicons-webfont.eot') }}");
                src: url("{{ asset('fonts/materialdesignicons-webfont.eot') }}") format("embedded-opentype"),
                    url("{{ asset('fonts/materialdesignicons-webfont.woff2') }}") format("woff2"),
                    url("{{ asset('fonts/materialdesignicons-webfont.woff') }}") format("woff"),
                    url("{{ asset('fonts/materialdesignicons-webfont.ttf') }}") format("truetype");
                font-weight: normal;
                font-style: normal;
            }
            @font-face {
                font-family: "feather";
                src:  url("{{ asset('fonts/feather-webfont.eot') }}");
                src:  url("{{ asset('fonts/feather-webfont.eot') }}") format("embedded-opentype"),
                        url("{{ asset('fonts/feather-webfont.woff') }}") format("woff"),
                        url("{{ asset('fonts/feather-webfont.ttf') }}") format("truetype"),
                        url("{{ asset('fonts/feather-webfont.svg') }}") format("svg");
                font-weight: normal;
                font-style: normal;
            }
            @font-face {
                font-family: 'themify';
                src: 	url("{{ asset('fonts/themify.eot') }}");
                src:	url("{{ asset('fonts/themify.eot') }}") format('embedded-opentype'),
                        url("{{ asset('fonts/themify.woff') }}") format('woff'),
                        url("{{ asset('fonts/themify.ttf') }}") format('truetype'),
                        url("{{ asset('fonts/themify.svg') }}") format('svg');
                font-weight: normal;
                font-style: normal;
            }
        </style>
        @include('sweetalert::alert')
        <script src="{{ asset('js/vendor.bundle.base.js') }}"></script>
        <script src="{{ asset('js/Chart.min.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('js/dataTables.select.min.js') }}"></script>
        <script src="{{ asset('js/select2.min.js') }}"></script>
        <script src="{{ asset('js/select2.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        <script src="{{asset('js/sweetalert2.min.js')}}"></script>

        <script src="{{ asset('js/off-canvas.js') }}"></script>
        <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
        <script src="{{ asset('js/template.js') }}"></script>
        <script src="{{ asset('js/settings.js') }}"></script>
        <script src="{{ asset('js/todolist.js') }}"></script>

        <script src="{{ asset('js/dashboard.js') }}"></script>
        <script src="{{ asset('js/Chart.roundedBarCharts.js') }}"></script>
    </body>
</html>