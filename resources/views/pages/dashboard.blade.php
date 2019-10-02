@extends('layouts.admin')
@section('title', 'Dashboard :: Startev Africa')
@section('content')
    <div ng-controller="dashboardController">
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <!-- Single Widget -->
                        <div class="single-widget-area d-flex align-items-center justify-content-between">
                            <div class="profit-icon">
                                <i class="zmdi zmdi-favorite-outline"></i>
                            </div>

                            <div class="total-profit">
                                <h6>New Orders</h6>
                                <div class="font-30 font-weight-bold">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <!-- Single Widget -->
                        <div class="single-widget-area d-flex align-items-center justify-content-between">
                            <div class="profit-icon">
                                <i class="zmdi zmdi-shopping-cart"></i>
                            </div>

                            <div class="total-profit">
                                <h6>Today's Sales</h6>
                                <div class="font-30 font-weight-bold">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <!-- Single Widget -->
                        <div class="single-widget-area d-flex align-items-center justify-content-between">
                            <div class="profit-icon">
                                <i class="zmdi zmdi-storage"></i>
                            </div>

                            <div class="total-profit">
                                <h6>Pending Settlements</h6>
                                <div class="font-30 font-weight-bold">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <!-- Single Widget -->
                        <div class="single-widget-area d-flex align-items-center justify-content-between">
                            <div class="profit-icon">
                                <i class="zmdi zmdi-trending-flat"></i>
                            </div>

                            <div class="total-profit">
                                <h6>Unpaid Escrows</h6>
                                <div class="font-30 font-weight-bold">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content -->
        <div class="row">
            <div class="col-lg-3">
                <div class="card bg-info">
                    <div class="card-body">
                        <div class="single-widget-area d-flex align-items-center justify-content-between">
                            <div class="profit-icon bg-dark">
                                <i class="zmdi zmdi-accounts-alt"></i>
                            </div>

                            <div class="total-profit">
                                <h6>Total Students</h6>
                                <div class="font-30 text-white font-weight-bold">@{{ model.stats.students | number:0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card bg-success">
                    <div class="card-body">
                        <div class="single-widget-area d-flex align-items-center justify-content-between">
                            <div class="profit-icon bg-dark">
                                <i class="zmdi zmdi-accounts-alt"></i>
                            </div>

                            <div class="total-profit">
                                <h6>Total Mentors</h6>
                                <div class="font-30 text-white font-weight-bold">@{{ model.stats.mentors | number:0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="single-widget-area d-flex align-items-center justify-content-between">
                            <div class="profit-icon bg-dark">
                                <i class="zmdi zmdi-accounts-alt"></i>
                            </div>

                            <div class="total-profit">
                                <h6>Total Graduates</h6>
                                <div class="font-30 text-white font-weight-bold">@{{ model.stats.graduates | number:0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="single-widget-area d-flex align-items-center justify-content-between">
                            <div class="profit-icon bg-dark">
                                <i class="zmdi zmdi-accounts-alt"></i>
                            </div>

                            <div class="total-profit">
                                <h6>Total Businesses</h6>
                                <div class="font-30 text-white font-weight-bold">@{{ model.stats.businesses | number:0 }}</div>
                            </div>
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
