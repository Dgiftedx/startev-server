@extends('layouts.error')
@section('content')

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper" class="error-page">
        <div class="error-box">
            <div class="error-body text-center">
                <h1 class="text-warning">404</h1>
                <h3 class="text-uppercase">Page Not Found !</h3>
                <p class="text-muted m-t-30 m-b-30">{{ _('YOU SEEM TO BE TRYING TO FIND HIS WAY HOME') }}</p>
                <a href="https://startev.africa" target="_self" class="btn btn-warning btn-rounded waves-effect waves-light m-b-40">{{ _('Back to home') }}</a> </div>
            <footer class="footer text-center">© {{ date('Y') }} {{ _('Startev Africa') }}.</footer>
        </div>
    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

@endsection