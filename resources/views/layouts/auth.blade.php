 <!doctype html>
<html lang="en">
<head>

        <meta charset="utf-8" />
        <title>Dashboard | VIMS 3.2</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="VIMS 3.1" name="description" />
        <meta content="TD-III" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/auth/images/vimslogo2.png') }}">

        <!-- jquery.vectormap css -->
        {{-- <link href="{{ asset('assets/auth/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" /> --}}
        <link href= "{{ asset('assets/auth/css/jquery-ui.css') }}" rel="stylesheet" type="text/css" />

        <!-- DataTables -->
        <link href="{{ asset('assets/auth/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Select2 -->
        <link href="{{ asset('assets/auth/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">

        <!-- Toaster -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/auth/libs/toastr/build/toastr.min.css') }}">
        <link href="{{ asset('assets/auth/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

        {{-- <link href="{{ asset('assets/auth/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet') }}" type="text/css" /> --}}

        <!-- Responsive datatable examples -->
        {{-- <link href="{{ asset('assets/auth/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" /> --}}

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/auth/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href= "{{ asset('assets/auth/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href= "{{ asset('assets/auth/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        <link href= "{{ asset('assets/auth/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="{{ asset('assets/auth/libs/leaflet/leaflet.css')}}" />

        <!-- Costume Css-->
        <link href= "{{ asset('assets/auth/css/costume.css') }}"  rel="stylesheet" type="text/css" />
        <link href= "{{ asset('assets/auth/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        <script src="{{ asset('assets/auth/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/auth/libs/jquery/jquery.min.js') }}"></script>

        {{-- <script src="{{ asset('assets/auth/js/costume.js') }}"></script> --}}

</head>

    {{-- <body data-topbar="dark"> --}}
        <body data-topbar="dark" data-layout="horizontal">
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    <i class="ri-loader-line spin-icon"></i>
                </div>
            </div>
        </div>

        <!-- Begin page -->
        <div id="layout-wrapper">
            <header id="page-topbar">

                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">

                            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src= "{{ asset('assets/auth/images/vimslogo2.png') }}" alt="logo-sm" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/auth/images/vimslogo2.png') }}" alt="logo-dark" height="20">
                                </span>
                            </a>

                            <a href="{{ route('dashboard') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/auth/images/vimslogo2.png') }}" alt="logo-sm-light" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/auth/images/vimslogo2.png') }}" alt="logo-light" height="20">
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex">

                        <div class="dropdown d-none d-lg-inline-block ms-1">
                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                                <i class="ri-fullscreen-line"></i>
                            </button>
                        </div>

                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                                <img class="rounded-circle header-profile-user" src="{{ asset('assets/auth/images/users/avatar-1.jpg') }}" alt="Header Avatar">

                                <span class="d-none d-xl-inline-block ms-1 userHeader"></span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                @if (Auth::user()->role == 'admin')
                                    <a class="dropdown-item" href="{{ route('admin.profile', ['id' => Auth::user()->id]) }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                                @elseif (Auth::user()->role == 'encoder')
                                    <a class="dropdown-item" href="{{ route('encoder.profile', ['id' => Auth::user()->id]) }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                                @elseif (Auth::user()->role == 'supervisor')
                                    <a class="dropdown-item" href="{{ route('supervisor.profile', ['id' => Auth::user()->id]) }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                                @endif

                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();" class="text-danger">
                                        <i class="ri-shut-down-line align-middle me-1 text-danger"></i> {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- ========== Topbar Start ========== -->


                @include('layouts.sidebar')
            <!-- Left Sidebar End -->
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <div class="main-content">
                @yield('content')
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Scripts -->
            @include('layouts.script')
    </body>

</html>
