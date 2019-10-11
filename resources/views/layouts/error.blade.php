<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Startev Africa Admin">
    <meta name="author" content="Startev Africa">
    <!-- Favicon icon -->
    <link rel="icon" sizes="16x16" href="{{ asset('/favicon.ico') }}">
    <title>@yield('title')</title>
    <!-- Bootstrap Core CSS -->

    <link href="{{ asset('/base-assets/style.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="dark-color-overlay">

 <!-- Preloader -->
 <div id="preloader"></div>

@yield('content')

<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->

    <!-- Must needed plugins to the run this Template -->
    <script src="{{ asset('/base-assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/base-assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('/base-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/base-assets/js/bundle.js') }}"></script>

    <!-- Active JS -->
    <script src="{{ asset('/base-assets/js/default-assets/active.js') }}"></script>

</body>
</html>
