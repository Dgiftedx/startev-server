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
                                <h2 class="m-t-0 text-white">953,000</h2></div>
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
                                <h2 class="m-t-0 text-white">236,000</h2></div>
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
                                <h2 class="m-t-0 text-white">987,563</h2></div>
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
                                <h2 class="m-t-0 text-white">987,563</h2></div>
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
                            <ul class="list-inline m-b-0 ml-auto">
                                <li>
                                    <h6 class="text-muted text-success"><i class="fa fa-circle font-10 m-r-10 "></i>Site A view</h6> </li>
                                <li>
                                    <h6 class="text-muted text-info"><i class="fa fa-circle font-10 m-r-10"></i>Site B view</h6> </li>
                            </ul>
                        </div>
                        <div class="text-center m-t-30">
                            <div class="btn-group " role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-sm btn-secondary">PAGEVIEWS</button>
                                <button type="button" class="btn btn-sm btn-secondary">REFERRALS</button>
                            </div>
                        </div>
                        <div class="website-visitor p-relative m-t-30" style="height:355px; width:100%;"><div class="chartist-tooltip"></div><svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-line" style="width: 100%; height: 100%;"><g class="ct-grids"><line x1="30" x2="30" y1="15" y2="319.98797607421875" class="ct-grid ct-horizontal"></line><line x1="128.14903913225447" x2="128.14903913225447" y1="15" y2="319.98797607421875" class="ct-grid ct-horizontal"></line><line x1="226.29807826450892" x2="226.29807826450892" y1="15" y2="319.98797607421875" class="ct-grid ct-horizontal"></line><line x1="324.44711739676336" x2="324.44711739676336" y1="15" y2="319.98797607421875" class="ct-grid ct-horizontal"></line><line x1="422.59615652901783" x2="422.59615652901783" y1="15" y2="319.98797607421875" class="ct-grid ct-horizontal"></line><line x1="520.7451956612723" x2="520.7451956612723" y1="15" y2="319.98797607421875" class="ct-grid ct-horizontal"></line><line x1="618.8942347935267" x2="618.8942347935267" y1="15" y2="319.98797607421875" class="ct-grid ct-horizontal"></line><line x1="717.0432739257812" x2="717.0432739257812" y1="15" y2="319.98797607421875" class="ct-grid ct-horizontal"></line><line y1="319.98797607421875" y2="319.98797607421875" x1="30" x2="717.0432739257812" class="ct-grid ct-vertical"></line><line y1="269.1566467285156" y2="269.1566467285156" x1="30" x2="717.0432739257812" class="ct-grid ct-vertical"></line><line y1="218.3253173828125" y2="218.3253173828125" x1="30" x2="717.0432739257812" class="ct-grid ct-vertical"></line><line y1="167.49398803710938" y2="167.49398803710938" x1="30" x2="717.0432739257812" class="ct-grid ct-vertical"></line><line y1="116.66265869140625" y2="116.66265869140625" x1="30" x2="717.0432739257812" class="ct-grid ct-vertical"></line><line y1="65.83132934570312" y2="65.83132934570312" x1="30" x2="717.0432739257812" class="ct-grid ct-vertical"></line><line y1="15" y2="15" x1="30" x2="717.0432739257812" class="ct-grid ct-vertical"></line></g><g><g class="ct-series ct-series-a"><path d="M30,319.988L30,319.988C62.716,303.044,95.433,274.805,128.149,269.157C160.865,263.509,193.582,263.509,226.298,258.99C259.014,254.472,291.731,250.786,324.447,238.658C357.163,226.53,389.88,65.831,422.596,65.831C455.313,65.831,488.029,222.113,520.745,228.492C553.462,234.87,586.178,238.658,618.894,238.658C651.611,238.658,684.327,130.218,717.043,75.998L717.043,319.988Z" class="ct-area" x1="NaN"></path><path d="M30,319.988C62.716,303.044,95.433,274.805,128.149,269.157C160.865,263.509,193.582,263.509,226.298,258.99C259.014,254.472,291.731,250.786,324.447,238.658C357.163,226.53,389.88,65.831,422.596,65.831C455.313,65.831,488.029,222.113,520.745,228.492C553.462,234.87,586.178,238.658,618.894,238.658C651.611,238.658,684.327,130.218,717.043,75.998" class="ct-line"></path><line x1="30" y1="319.98797607421875" x2="30.01" y2="319.98797607421875" class="ct-point" ct:value="0"></line><line x1="128.14903913225447" y1="269.1566467285156" x2="128.15903913225446" y2="269.1566467285156" class="ct-point" ct:value="5"></line><line x1="226.29807826450892" y1="258.990380859375" x2="226.3080782645089" y2="258.990380859375" class="ct-point" ct:value="6"></line><line x1="324.44711739676336" y1="238.65784912109376" x2="324.45711739676335" y2="238.65784912109376" class="ct-point" ct:value="8"></line><line x1="422.59615652901783" y1="65.83132934570312" x2="422.6061565290178" y2="65.83132934570312" class="ct-point" ct:value="25"></line><line x1="520.7451956612723" y1="228.49158325195313" x2="520.7551956612723" y2="228.49158325195313" class="ct-point" ct:value="9"></line><line x1="618.8942347935267" y1="238.65784912109376" x2="618.9042347935267" y2="238.65784912109376" class="ct-point" ct:value="8"></line><line x1="717.0432739257812" y1="75.99759521484376" x2="717.0532739257812" y2="75.99759521484376" class="ct-point" ct:value="24"></line></g><g class="ct-series ct-series-b"><path d="M30,319.988L30,319.988C62.716,309.822,95.433,289.489,128.149,289.489C160.865,289.489,193.582,309.822,226.298,309.822C259.014,309.822,291.731,305.465,324.447,299.655C357.163,293.846,389.88,238.658,422.596,238.658C455.313,238.658,488.029,309.822,520.745,309.822C553.462,309.822,586.178,269.157,618.894,269.157C651.611,269.157,684.327,296.267,717.043,309.822L717.043,319.988Z" class="ct-area" x1="NaN"></path><path d="M30,319.988C62.716,309.822,95.433,289.489,128.149,289.489C160.865,289.489,193.582,309.822,226.298,309.822C259.014,309.822,291.731,305.465,324.447,299.655C357.163,293.846,389.88,238.658,422.596,238.658C455.313,238.658,488.029,309.822,520.745,309.822C553.462,309.822,586.178,269.157,618.894,269.157C651.611,269.157,684.327,296.267,717.043,309.822" class="ct-line"></path><line x1="30" y1="319.98797607421875" x2="30.01" y2="319.98797607421875" class="ct-point" ct:value="0"></line><line x1="128.14903913225447" y1="289.4891784667969" x2="128.15903913225446" y2="289.4891784667969" class="ct-point" ct:value="3"></line><line x1="226.29807826450892" y1="309.82171020507815" x2="226.3080782645089" y2="309.82171020507815" class="ct-point" ct:value="1"></line><line x1="324.44711739676336" y1="299.6554443359375" x2="324.45711739676335" y2="299.6554443359375" class="ct-point" ct:value="2"></line><line x1="422.59615652901783" y1="238.65784912109376" x2="422.6061565290178" y2="238.65784912109376" class="ct-point" ct:value="8"></line><line x1="520.7451956612723" y1="309.82171020507815" x2="520.7551956612723" y2="309.82171020507815" class="ct-point" ct:value="1"></line><line x1="618.8942347935267" y1="269.1566467285156" x2="618.9042347935267" y2="269.1566467285156" class="ct-point" ct:value="5"></line><line x1="717.0432739257812" y1="309.82171020507815" x2="717.0532739257812" y2="309.82171020507815" class="ct-point" ct:value="1"></line></g></g><g class="ct-labels"><foreignObject style="overflow: visible;" x="30" y="324.98797607421875" width="98.14903913225446" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 98px; height: 20px;">1</span></foreignObject><foreignObject style="overflow: visible;" x="128.14903913225447" y="324.98797607421875" width="98.14903913225446" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 98px; height: 20px;">2</span></foreignObject><foreignObject style="overflow: visible;" x="226.29807826450892" y="324.98797607421875" width="98.14903913225444" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 98px; height: 20px;">3</span></foreignObject><foreignObject style="overflow: visible;" x="324.44711739676336" y="324.98797607421875" width="98.14903913225447" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 98px; height: 20px;">4</span></foreignObject><foreignObject style="overflow: visible;" x="422.59615652901783" y="324.98797607421875" width="98.14903913225447" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 98px; height: 20px;">5</span></foreignObject><foreignObject style="overflow: visible;" x="520.7451956612723" y="324.98797607421875" width="98.14903913225442" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 98px; height: 20px;">6</span></foreignObject><foreignObject style="overflow: visible;" x="618.8942347935267" y="324.98797607421875" width="98.14903913225453" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 98px; height: 20px;">7</span></foreignObject><foreignObject style="overflow: visible;" x="717.0432739257812" y="324.98797607421875" width="30" height="20"><span class="ct-label ct-horizontal ct-end" xmlns="http://www.w3.org/2000/xmlns/" style="width: 30px; height: 20px;">8</span></foreignObject><foreignObject style="overflow: visible;" y="269.1566467285156" x="10" height="50.831329345703125" width="10"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 51px; width: 10px;">0k</span></foreignObject><foreignObject style="overflow: visible;" y="218.3253173828125" x="10" height="50.831329345703125" width="10"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 51px; width: 10px;">5k</span></foreignObject><foreignObject style="overflow: visible;" y="167.49398803710938" x="10" height="50.831329345703125" width="10"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 51px; width: 10px;">10k</span></foreignObject><foreignObject style="overflow: visible;" y="116.66265869140625" x="10" height="50.831329345703125" width="10"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 51px; width: 10px;">15k</span></foreignObject><foreignObject style="overflow: visible;" y="65.83132934570312" x="10" height="50.831329345703125" width="10"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 51px; width: 10px;">20k</span></foreignObject><foreignObject style="overflow: visible;" y="15" x="10" height="50.831329345703125" width="10"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 51px; width: 10px;">25k</span></foreignObject><foreignObject style="overflow: visible;" y="-15" x="10" height="30" width="10"><span class="ct-label ct-vertical ct-start" xmlns="http://www.w3.org/2000/xmlns/" style="height: 30px; width: 10px;">30k</span></foreignObject></g><defs><linearGradient id="gradient" x1="0" y1="1" x2="0" y2="0"><stop offset="0" stop-color="rgba(255, 255, 255, 1)"></stop><stop offset="1" stop-color="rgba(38, 198, 218, 1)"></stop></linearGradient></defs></svg></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/dashboard/dashboardController.js') }}"></script>
@stop