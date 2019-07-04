<!DOCTYPE html>
<html lang="en" ng-app="MainApp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Startev Africa">
    <!-- Favicon icon -->
    <link rel="icon" sizes="16x16" href="{{ asset('/favicon.ico') }}">
    <title>@yield('title')</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('/core-assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/core-assets/plugins/perfect-scrollbar/dist/css/perfect-scrollbar.min.css') }}" rel="stylesheet">
    <!-- page css -->
    <link href="{{ asset('/core-assets/plugins/c3-master/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/core-assets/css/pages/dashboard3.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('/core-assets/css/style.css') }}" rel="stylesheet">

    <!-- You can change the theme colors from here -->
    <link href="{{ asset('/core-assets/css/colors/default-dark.css') }}" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class=" fix-header fix-sidebar card-no-border">
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">{{ _('Startev Africa') }}</p>
    </div>
</div>

<div id="main-wrapper">

    <!-- header -->
    @include('sections.admin.header')

    @include('sections.admin.sidebar')

    <div class="page-wrapper">
        @yield('content')
    </div>
</div>

<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{ asset('/core-assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap popper Core JavaScript -->
<script src="{{ asset('/core-assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('/core-assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ asset('/core-assets/js/perfect-scrollbar.jquery.min.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('/core-assets/js/waves.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ asset('/core-assets/js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('/core-assets/js/custom.min.js') }}"></script>
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!--sparkline JavaScript -->
<script src="{{ asset('/core-assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!--morris JavaScript -->
<script src="{{ asset('/core-assets/plugins/chartist-js/dist/chartist.min.js') }}"></script>
<script src="{{ asset('/core-assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}"></script>
<!--c3 JavaScript -->
<script src="{{ asset('/core-assets/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('/core-assets/plugins/c3-master/c3.min.js') }}"></script>
<!-- Chart JS -->
<script src="{{ asset('/core-assets/js/dashboard3.js') }}"></script>

<script src="{{ asset('/core-assets/lib/printThis.js') }}"></script>
<script src="{{ asset('/core-assets/lib/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/core-assets/lib/chart/Chart.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/dataTables.bootstrap4.min.js') }}"></script>

<!-- ======================== Angular Files : Not made compulsory in the system ========================= -->
<script src="{{ asset('/core-assets/lib/angular/angular.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/angular/angular-animate.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/angular/angular-sanitize.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/lodash/dist/lodash.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/chart/highChart.js') }}"></script>
<script src="{{ asset('/core-assets/lib/ADM-dateTimePicker.js') }}"></script>
<script src="{{ asset('/core-assets/lib/angular-button-spinner.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/moment.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/angular-tag.js') }}"></script>
<script src="{{ asset('/core-assets/lib/angular-moment.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/angular-datatables.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/lodash/dist/lodash.core.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/angular-bootstrap-toggle.js') }}"></script>
<script src="{{ asset('/core-assets/lib/ng-lodash/build/ng-lodash.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/coderty-loader.js') }}"></script>
<script src="{{ asset('/core-assets/lib/select.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/selectize.js') }}"></script>
<script src="{{ asset('/core-assets/lib/ng-ckeditor.min.js') }}"></script>
<script src="{{ asset('/core-assets/lib/sweetalert.min.js') }}"></script>


<!-- global AngularJS App to tap to -->
<script src="{{ asset('/core-assets/scripts/main.js') }}"></script>
<script src="{{ asset('/core-assets/scripts/services/adminService.js') }}"></script>
@yield('footerScript')
</body>
</html>