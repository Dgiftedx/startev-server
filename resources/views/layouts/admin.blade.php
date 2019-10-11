<!DOCTYPE html>
<html lang="en" ng-app="MainApp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Startev Africa Admin">
    <meta name="author" content="Startev Africa">
    <!-- Favicon icon -->
    <link rel="icon" sizes="16x16" href="{{ asset('/favicon.ico') }}">
    <title>@yield('title')</title>
    <!-- These plugins only need for the run this page -->
    <link rel="stylesheet" href="{{ asset('/base-assets/css/default-assets/datatables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('/base-assets/css/default-assets/responsive.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('/base-assets/css/default-assets/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('/base-assets/css/default-assets/select.bootstrap4.css') }}">

    <link href="{{ asset('/base-assets/lib/select.css') }}" rel="stylesheet">
    <link href="{{ asset('/base-assets/lib/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('/base-assets/lib/toast.css') }}" rel="stylesheet">
    <link href="{{ asset('/base-assets/lib/coderty-loader.css') }}" rel="stylesheet">
    <link href="{{ asset('/base-assets/lib/ADM-dateTimePicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/base-assets/lib/selectize.default.css') }}" rel="stylesheet">
    <link href="{{ asset('/base-assets/lib/dropify/css/dropify.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/base-assets/style.css') }}" rel="stylesheet">

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!-- Preloader -->
<div id="preloader"></div>

<div class="ecaps-page-wrapper">
    @include('sections.admin.sidebar')


     <!-- Page Content -->
     <div class="ecaps-page-content">

        <!-- Header -->
        @include('sections.admin.header')

{{--        @include('sections.admin.breadcrumb')--}}

        <!-- Main Content Area -->
        <div class="main-content">
            <div class="container-fluid">
            @yield('content')
            </div>
        </div>
     </div>
</div>

<!-- Must needed plugins to the run this Template -->
<script src="{{ asset('/base-assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('/base-assets/js/popper.min.js') }}"></script>
<script src="{{ asset('/base-assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/base-assets/js/bundle.js') }}"></script>

<!-- Active JS -->
<script src="{{ asset('/base-assets/js/default-assets/fullscreen.js') }}"></script>
<script src="{{ asset('/base-assets/js/default-assets/active.js') }}"></script>

<!-- These plugins only need for the run this page -->
<script src="{{ asset('/base-assets/js/default-assets/jquery.datatables.min.js') }}"></script>
<script src="{{ asset('/base-assets/js/default-assets/datatables.bootstrap4.js') }}"></script>
<script src="{{ asset('/base-assets/js/default-assets/datatable-responsive.min.js') }}"></script>
<script src="{{ asset('/base-assets/js/default-assets/responsive.bootstrap4.min.js') }}"></script>
{{-- <script src="{{ asset('/base-assets/js/default-assets/demo.datatable-init.js') }}"></script> --}}

<!-- Chart JS -->
<script src="{{ asset('/base-assets/js/default-assets/toastr.js') }}"></script>
<script src="{{ asset('/base-assets/lib/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/printThis.js') }}"></script>
<script src="{{ asset('/base-assets/lib/toast.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/base-assets/lib/chart/Chart.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/dataTables.bootstrap4.min.js') }}"></script>

<!-- ======================== Angular Files : Not made compulsory in the system ========================= -->
<script src="{{ asset('/base-assets/lib/angular/angular.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/angular/angular-animate.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/angular/angular-sanitize.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/lodash/dist/lodash.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/chart/highChart.js') }}"></script>
<script src="{{ asset('/base-assets/lib/ADM-dateTimePicker.js') }}"></script>
<script src="{{ asset('/base-assets/lib/angular-button-spinner.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/moment.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/angular-tag.js') }}"></script>
<script src="{{ asset('/base-assets/lib/angular-moment.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/angular-datatables.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/lodash/dist/lodash.core.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/angular-bootstrap-toggle.js') }}"></script>
<script src="{{ asset('/base-assets/lib/ng-lodash/build/ng-lodash.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/coderty-loader.js') }}"></script>
<script src="{{ asset('/base-assets/lib/select.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/selectize.js') }}"></script>
<script src="{{ asset('/base-assets/lib/ng-ckeditor.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/sweetalert.min.js') }}"></script>
<script src="{{ asset('/base-assets/lib/dropify/js/dropify.min.js') }}"></script>


<!-- global AngularJS App to tap to -->
<script src="{{ asset('/core-assets/scripts/main.js') }}"></script>
<script src="{{ asset('/core-assets/scripts/services/adminService.js') }}"></script>
@yield('footerScript')
</body>
</html>
