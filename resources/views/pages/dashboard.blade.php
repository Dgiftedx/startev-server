@extends('layouts.admin')
@section('title', 'Dashboard :: Startev Africa')
@section('content')
    <div class="container-fluid" ng-controller="dashboardController">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">Startev Admin</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div>
        </div>

        <!-- content -->
        <div class="row">
            <div class="col-lg-3">
                <div class="card bg-info">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><img src="{{ asset('/core-assets/images/icon/staff-w.png') }}" alt="Income" /></div>
                            <div class="align-self-center">
                                <h6 class="text-white m-t-10 m-b-0">Total Students</h6>
                                <h2 class="m-t-0 text-white">@{{ model.stats.students | number:0 }}</h2></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card bg-success">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><img src="{{ asset('/core-assets/images/icon/staff-w.png') }}" alt="Income" /></div>
                            <div class="align-self-center">
                                <h6 class="text-white m-t-10 m-b-0">Total Mentors</h6>
                                <h2 class="m-t-0 text-white">@{{ model.stats.mentors | number:0 }}</h2></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><img src="{{ asset('/core-assets/images/icon/assets-w.png') }}" alt="Income" /></div>
                            <div class="align-self-center">
                                <h6 class="text-white m-t-10 m-b-0">Total Graduates</h6>
                                <h2 class="m-t-0 text-white">@{{ model.stats.graduates | number:0 }}</h2></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><img src="{{ asset('/core-assets/images/icon/staff-w.png') }}" alt="Income" /></div>
                            <div class="align-self-center">
                                <h6 class="text-white m-t-10 m-b-0">Total Businesses</h6>
                                <h2 class="m-t-0 text-white">@{{ model.stats.businesses | number:0 }}</h2></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h4 class="card-title"><span class="lstick"></span>Users registration statistics</h4>
                        </div>

                        <!-- comprehensive statistics -->
                        <div class="holder" style="width: 100%;">
                            <st-bar-chart id="chart" ng-if="model.statisticsChart"
                                          title="Registration Statistics"
                                          child="model.statisticschildData"
                                          data="model.statisticsData">
                            </st-bar-chart>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/dashboard/dashboardController.js') }}"></script>
@stop