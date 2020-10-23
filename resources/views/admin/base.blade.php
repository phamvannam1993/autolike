<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin - @section('title') Trang Chủ @show </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ url('css') }}/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ url('css') }}/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ url('css') }}/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ url('css') }}/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{ url('css') }}/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ url('css') }}/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{ url('css') }}/daterangepicker.css" rel="stylesheet">

    <!-- Toastr -->
    <link href="{{ url('css') }}/toastr.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ url('css') }}/custom.min.css" rel="stylesheet">

    <!-- Custom Personal Style -->
    <link href="{{ url('css') }}/main.css" rel="stylesheet">

    <!-- Date picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132961921-1"></script>

    @yield('css')
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="http://admin.gamegame.me" class="site_title"><i class="fa fa-paw"></i> <span>Logging</span></a>
                </div>

                <div class="clearfix"></div>

                <div class="profile clearfix">
                    <div class="profile_pic">
                        <img src="{{url('images')}}/circled-user-male-skin-type-1-2.png" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Xin Chào ,</span>
                        <h2>Admin</h2>
                    </div>
                </div>
                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section active">
                        <h3>Chức năng</h3>
                        <ul class="nav side-menu">
                            <li>
                                <a href="{{route('admin.logging', ['name' => 'Lotus'])}}">Lotus </a>
                            </li>
                            <li>
                                <a href="{{route('admin.logging', ['name' => 'Jasmine'])}}">Jasmine </a>
                            </li>
                            <li>
                                <a href="{{route('admin.logging', ['name' => 'LDragon'])}}">LDragon</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="{{url('images')}}/circled-user-male-skin-type-1-2.png">
                                Phạm Văn Nam
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="{{ route('admin.logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->



    @section('main')


        @show


        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<script src="https://log.autofarmer.xyz/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="https://log.autofarmer.xyz/js/bootstrap.min.js"></script>
@yield('js')
@stack('pageScripts')
</body>
</html>
