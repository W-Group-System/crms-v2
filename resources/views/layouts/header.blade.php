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
        <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
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
        <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/theme.bootstrap_4.min.css')}}">
        <link rel="icon" href="{{asset('images/wgroup.png')}}" type="image/x-icon">

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
        th
        {
            background-color: white !important;
        }
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('images/loading.gif') }}") 50% 50% no-repeat white;
            opacity: .8;
            background-size: 120px 120px;
        }
        
        /* html, body {
            overflow: hidden;
        } */

        /* width */
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .table-responsive::-webkit-scrollbar {
            width: 5px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    @yield('css')
    @php
        $role = auth()->user()->role_id;
        $department = auth()->user()->department_id;
    @endphp
    <body>
        <div id="loader" style="display:none;" class="loader"></div>

        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <h3 class="logo-crms">CRMS 2.0</h3>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="{{ asset('/images/user.png')}}" alt="profile"/>
                                <!-- @if($hasReturnedTransactions ?? false)
                                    <span class="position-absolute top-0 start-100 translate-middle bg-danger border border-light rounded-circle" 
                                        style="right: 0px; padding: 0.3rem"></span>
                                @endif -->
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a href="{{ route('my_account') }}" class="dropdown-item">
                                    <i class="ti ti-user text-primary"></i>
                                    My Account
                                </a>
                                <!-- @if(Auth::check() && optional(Auth::user()->role)->type == 'IS' || optional(Auth::user()->role)->type == 'LS')
                                <a href="{{ route('returned_transaction') }}" class="dropdown-item">
                                    
                                    <i class="ti ti-share-alt text-primary"></i>
                                    @if($hasReturnedTransactions ?? false)
                                        <span class="position-absolute top-0 start-100 translate-middle bg-danger border border-light rounded-circle" 
                                            style="left: 10px; padding: 0.3rem"></span>
                                    @endif
                                    Returned Transactions
                                </a>
                                @endif -->
                                <a href="{{ route('change_password') }}" class="dropdown-item">
                                    <i class="ti ti-unlock text-primary"></i>
                                    Change Password
                                </a>
                                <!-- <a class="dropdown-item">
                                    <i class="ti-power-off text-primary"></i>
                                    Logout
                                </a> -->
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="logout(); show();">
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
                    <div class="app-logo text-center">
                        <img src="{{asset('images/wgroup.png')}}" alt="" height="120" width="170">
                    </div>
                    <hr class="m-0">
                    <ul class="nav mt-2">
                        <li class="nav-item">
                            @if(Auth::check() && optional(Auth::user()->role)->type == 'RND' || optional(Auth::user()->role)->type == 'ITD')
                                <a class="nav-link" href="{{ route('dashboard.rnd') }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            @elseif(Auth::check() && optional(Auth::user()->role)->type == 'LS' || optional(Auth::user()->role)->type == 'IS')
                                <a class="nav-link" href="{{ route('dashboard.index') }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            @elseif(Auth::check() && optional(Auth::user()->role)->type == 'QCD-WHI' || optional(Auth::user()->role)->type == 'QCD-PBI' || optional(Auth::user()->role)->type == 'QCD-MRDC' || optional(Auth::user()->role)->type == 'QCD-CCC')
                                <a class="nav-link" href="{{ route('dashboard.qcd') }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            @elseif(Auth::check() && optional(Auth::user()->role)->type == 'PRD')
                                <a class="nav-link" href="{{ route('dashboard.prd') }}">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            @endif
                        </li>
                        @if((viewModule('Supplier Information', $department, $role) == "yes") || (viewModule('Supplier Product Evaluation', $department, $role) == "yes") || (viewModule('Shipment Sample Evaluation', $department, $role) == "yes"))
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#table_supplier" aria-expanded="false" aria-controls="table_supplier" onclick="toggleSupplier(event)">
                                <i class="icon-globe menu-icon"></i>
                                <span class="menu-title">Supplier Transaction</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="table_supplier">
                                <ul class="nav flex-column sub-menu">
                                    <!-- <li class="nav-item"><a class="nav-link" href="">Request Form</a></li>  -->
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/supplier') }}">Supplier Information</a></li> 
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/supplier_product') }}">Supplier Product Evaluation</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/shipment_sample') }}">Shipment Sample Evaluation</a></li> 
                                </ul>
                            </div> 
                        </li> 
                        @endif
                        @if((viewModule('Current Products', $department, $role) == "yes") || (viewModule('New Products', $department, $role) == "yes") || (viewModule('Draft Products', $department, $role) == "yes") || (viewModule('Archived Products', $department, $role) == "yes") || (viewModule('Current Base Price', $department, $role) == "yes") || (viewModule('New Base Price', $department, $role) == "yes") || (viewModule('Product Application', $department, $role) == "yes") || (viewModule('Application Sub Categories', $department, $role) == "yes") || (viewModule('Raw Materials', $department, $role) == "yes"))
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#table_product" aria-expanded="false" aria-controls="table_product" onclick="toggleTablesProduct(event)">
                                <i class="icon-layout menu-icon"></i>
                                <span class="menu-title">Product Management</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="table_product">
                                <ul class="nav flex-column sub-menu">
                                    @if((viewModule('Current Products', $department, $role) == "yes") || (viewModule('New Products', $department, $role) == "yes") || (viewModule('Draft Products', $department, $role) == "yes") || (viewModule('Archived Products', $department, $role) == "yes"))
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_products" aria-expanded="false" aria-controls="nav_products" onclick="toggleProducts(event)">
                                            <span class="menu-title">Products</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    @endif
                                    <!-- <li class="nav-item"><a class="nav-link" href="">Certificate of Analysis</a></li> -->
                                    @if((viewModule('Current Base Price', $department, $role) == "yes") || (viewModule('New Base Price', $department, $role) == "yes"))
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#table_pricing2" aria-expanded="false" aria-controls="table_pricing2" onclick="toggleSetupPricing(event)">
                                            <span class="menu-title">Pricing</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if((viewModule('Product Application', $department, $role) == "yes") || (viewModule('Application Sub Categories', $department, $role) == "yes") || (viewModule('Raw Materials', $department, $role) == "yes"))
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#table_product2" aria-expanded="false" aria-controls="table_product2" onclick="toggleSetupProduct(event)">
                                            <span class="menu-title">Setup</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="collapse" id="nav_products">
                                <ul class="nav flex-column sub-menu">
                                    @if(viewModule('Current Products', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/current_products') }}">Current Products</a></li>
                                    @endif
                                    @if(viewModule('New Products', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/new_products') }}">New Products</a></li>
                                    @endif
                                    @if(viewModule('Draft Products', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/draft_products') }}">Draft Products</a></li>
                                    @endif
                                    @if(viewModule('Archived Products', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/archived_products') }}">Archived Products</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="collapse" id="table_pricing2">
                                <ul class="nav flex-column sub-menu">
                                    @if(viewModule('Current Base Price', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/base_price') }}">Current Base Price</a></li>
                                    @endif
                                    @if(viewModule('New Base Price', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/new_base_price') }}">New Base Price</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="collapse" id="table_product2">
                                <ul class="nav flex-column sub-menu">
                                    @if(viewModule('Product Application', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/product_applications') }}">Product Applications</a></li>
                                    @endif
                                    @if(viewModule('Application Sub Categories', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/product_subcategories') }}">Application Sub Categories</a></li>
                                    @endif
                                    @if(viewModule('Raw Materials', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/raw_material') }}">Raw Materials</a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        @if(viewModule('Products', $department, $role) == "yes")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/products') }}">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Products</span>
                            </a>
                        </li>
                        @endif
                        @if((viewModule('Current Clients', $department, $role) == "yes") || (viewModule('Prospects Client', $department, $role) == "yes") || (viewModule('Archived Client', $department, $role) == "yes"))
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                                <i class="icon-bar-graph menu-icon"></i>
                                <span class="menu-title">Client Information</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="charts">
                                <ul class="nav flex-column sub-menu">
                                    @if(viewModule('Current Clients', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/client') }}">Current</a></li>
                                    @endif
                                    @if(viewModule('Prospects Client', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/client_prospect') }}">Prospects</a></li>
                                    @endif
                                    @if(viewModule('Archived Client', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/client_archived') }}">Archived</a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        @if((viewModule('Customer Requirement', $department, $role) == "yes") || (viewModule('Request for Product Evaluation', $department, $role) == "yes") || (viewModule('Sample Request Form', $department, $role) == "yes") || (viewModule('Price Monitoring', $department, $role) == "yes") || (viewModule('Customer Service SRF', $department, $role) == "yes"))
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#tables" aria-expanded="false" aria-controls="tables" onclick="toggleTables(event)">
                                <i class="icon-grid-2 menu-icon"></i>
                                <span class="menu-title">Client Transaction</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="tables">
                                <ul class="nav flex-column sub-menu">
                                    @if(viewModule('Customer Requirement', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/customer_requirement?open=10') }}">Customer Requirement</a></li> 
                                    @endif
                                    @if(viewModule('Request for Product Evaluation', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/request_product_evaluation?open=10') }}">Request for Product Evaluation</a></li>
                                    @endif
                                    @if(viewModule('Accounting Targeting', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/account_targeting') }}">Account Targeting</a></li> 
                                    @endif
                                    @if(viewModule('Sample Request Form', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/sample_request?open=10') }}">Sample Request Form</a></li>
                                    @endif
                                    @if(viewModule('Customer Service SRF', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/sample_request_cs_local') }}">Sample Request Local</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/sample_request_cs_international') }}">Sample Request International</a></li>
                                    @endif
                                    {{-- @if(viewModule('Price Monitoring', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/price_monitoring') }}">Price Monitoring</a></li>
                                    @endif --}}
                                    @if(viewModule('Price Monitoring', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/price_monitoring_ls?open=10') }}">Price Monitoring</a></li>
                                    @endif
                                    
                                    @if((viewModule('Categorization', $department, $role) == "yes") || (viewModule('Project Name', $department, $role) == "yes") || (viewModule('Nature of Request', $department, $role) == "yes") || (viewModule('CRR Priority', $department, $role) == "yes"))
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#tables2" aria-expanded="false" aria-controls="tables2" onclick="toggleSetup(event)">
                                            <span class="menu-title">Setup</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        <!-- Separate collapse for setup submenu -->
                        <div class="collapse" id="tables2">
                            <ul class="nav flex-column sub-menu">
                                @if(viewModule('Categorization', $department, $role) == "yes")
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/categorization') }}">Categorization</a></li> 
                                @endif
                                @if(viewModule('Project Name', $department, $role) == "yes")
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/project_name') }}">Project Name</a></li>
                                @endif
                                @if(viewModule('Nature of Request', $department, $role) == "yes")
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/nature_request') }}">Nature of Request</a></li>
                                @endif
                                @if(viewModule('CRR Priority', $department, $role) == "yes")
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/crr_priority') }}">CRR Priority</a></li>
                                @endif
                            </ul>
                        </div>
                        @endif
                        
                        @if((viewModule('Customer Complaints', $department, $role) == "yes") || (viewModule('Customer Feedback', $department, $role) == "yes"))
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#table_service" aria-expanded="false" aria-controls="table_service" onclick="toggleTablesService(event)">
                                <i class="icon-grid-2 menu-icon"></i>
                                <span class="menu-title">Service Management</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="table_service">
                                <ul class="nav flex-column sub-menu">
                                    {{-- @if(viewModule('Customer Complaints', $department, $role) == "yes")
                                    <li class="nav-item"> <a class="nav-link" href="{{ url('/customer_complaint') }}">Customer Complaints</a></li>
                                    @endif
                                    @if(viewModule('Customer Feedback', $department, $role) == "yes")
                                    <li class="nav-item"> <a class="nav-link" href="{{ url('/customer_feedback') }}">Customer Feedbacks</a></li>
                                    @endif --}}
                                    <li class="nav-item"> <a class="nav-link" href="{{ url('/cs_list') }}">Customer Satisfaction</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="{{ url('/cc_list?open=10') }}">Customer Complaint</a></li>
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
                                @if(viewModule('Issue Category', $department, $role) == "yes")
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/issue_category') }}">Issue Category</a></li>
                                @endif
                                @if(viewModule('Concerned Department', $department, $role) == "yes")
                                <li class="nav-item"><a class="nav-link setup-item" href="{{ url('concern_department') }}">Concerned Department</a></li>
                                @endif
                            </ul>
                        </div>
                        @endif
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
                        @if(viewModule('Activities', $department, $role) == "yes")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/activities?open=10') }}">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Activities</span>
                            </a>
                        </li>
                        @endif

                        @if(optional(auth()->user()->role)->type == "ACCTG" || auth()->user()->role->type == 'ITD')
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#module" aria-expanded="false" aria-controls="module" onclick="toggleModule(event)">
                                <i class="icon-layout menu-icon"></i>
                                <span class="menu-title">Module Setup</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="module">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_payment_currency" aria-expanded="false" aria-controls="nav_payment_currency" onclick="togglePaymentCurrency(event)">
                                            <span class="menu-title">Payment Currency</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    @if(viewModule('Price Request Fixed Cost', $department, $role) == "yes" || viewModule('Price Request GAE', $department, $role) == "yes")
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_accounting" aria-expanded="false" aria-controls="nav_accounting" onclick="toggleSetupAccounting(event)">
                                            <span class="menu-title">Accounting</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="collapse" id="nav_payment_currency">
                                <ul class="nav flex-column sub-menu">
                                    {{-- @if(viewModule('Concerned Department', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/price_currency') }}">Price Currencies</a></li>
                                    @endif --}}
                                    @if(viewModule('Currency Exchange Rates', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('currency_exchange') }}">Currency Exchange Rates</a></li>
                                    @endif
                                    {{-- @if(viewModule('Concerned Department', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('payment_terms') }}">Payment Terms</a></li>
                                    @endif --}}
                                </ul>
                            </div>
                            <div class="collapse" id="nav_accounting">
                                <ul class="nav flex-column sub-menu">
                                    @if(viewModule('Price Request Fixed Cost', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/fixed_cost') }}">Price Request Fixed Cost</a></li>
                                    @endif
                                    @if(viewModule('Price Request GAE', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/request_gae') }}">Price Request GAE</a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif

                        {{-- @if(auth()->user()->department_id == 1)
                        @endif --}}
                        @if(viewModule('Price Currencies', $department, $role) == "yes" || viewModule('Price Currencies', $department, $role) == "yes" || viewModule('Price Request Fixed Cost', $department, $role) == "yes" || viewModule('Price Request GAE', $department, $role) == "yes")
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-target="#module" aria-expanded="false" aria-controls="module" onclick="toggleModule(event)">
                                <i class="icon-layout menu-icon"></i>
                                <span class="menu-title">Module Setup</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="module">
                                <ul class="nav flex-column sub-menu">
                                    @if(auth()->user()->role->type == null)
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
                                    @endif
                                    @if(viewModule('Price Currencies', $department, $role) == "yes" || viewModule('Price Currencies', $department, $role) == "yes")
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_payment_currency" aria-expanded="false" aria-controls="nav_payment_currency" onclick="togglePaymentCurrency(event)">
                                            <span class="menu-title">Payment Currency</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if(viewModule('Price Request Fixed Cost', $department, $role) == "yes" || viewModule('Price Request GAE', $department, $role) == "yes")
                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:void(0);" data-target="#nav_accounting" aria-expanded="false" aria-controls="nav_accounting" onclick="toggleSetupAccounting(event)">
                                            <span class="menu-title">Accounting</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                    @endif
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
                                    @if(viewModule('Price Currencies', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/price_currency') }}">Price Currencies</a></li>
                                    @endif
                                    @if(viewModule('Price Currencies', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('currency_exchange') }}">Currency Exchange Rates</a></li>
                                    @endif
                                    @if(viewModule('Payment Terms', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('payment_terms') }}">Payment Terms</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="collapse" id="nav_accounting">
                                <ul class="nav flex-column sub-menu">
                                    @if(viewModule('Price Request Fixed Cost', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/fixed_cost') }}">Price Request Fixed Cost</a></li>
                                    @endif
                                    @if(viewModule('Price Request GAE', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link setup-item" href="{{ url('/request_gae') }}">Price Request GAE</a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        @if(auth()->user()->department_id == 1)
                        <li class="nav-item"> 
                            <a class="nav-link" data-toggle="collapse" href="#setup" aria-expanded="false" aria-controls="setup">
                                <i class="icon-cog menu-icon"></i>
                                <span class="menu-title">Setup</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="setup">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/user') }}">User Accounts</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/role') }}">Roles</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/company') }}">Company</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ url('/department') }}">Department</a></li>
                                </ul>
                            </div>
                        </li>
                        @endif
                        @if(viewModule('Price Request Summary', $department, $role) == "yes" || viewModule('Transaction Activity Summary', $department, $role) == "yes")
                        <li class="nav-item"> 
                            <a class="nav-link" data-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="reports">
                                <i class="icon-book menu-icon"></i>
                                <span class="menu-title">Reports</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="reports">
                                <ul class="nav flex-column sub-menu">
                                    @if(viewModule('Price Request Summary', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url ('/price_request') }}">Price Request Summary</a></li>
                                    @endif
                                    @if(viewModule('Transaction Activity Summary', $department, $role) == "yes")
                                    <li class="nav-item"><a class="nav-link" href="{{ url ('/transaction_activity') }}">Transaction/Activity Summary</a></li>
                                    @endif
                                    <li class="nav-item"><a class="nav-link" href="{{ url ('/sample_dispatch') }}">Sample Dispatch Summary</a></li>
                                </ul>
                            </div>
                        </li>
                        @endif
                        @if(auth()->user()->role->type == 'ITD')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/audits') }}">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Logs</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper">
                    @yield('content')
                    </div>
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
        <script>
            function show() {
                document.getElementById("loader").style.display = "block";
            }
            function hide()
            {
                document.getElementById("loader").style.display = "none";
            }
            function logout() {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            }
        </script>
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
        <script src="{{asset('js/sweetalert2.min.js')}}"></script>
        <script src="{{asset('js/jquery.tablesorter.min.js')}}"></script>
    </body>
</html>