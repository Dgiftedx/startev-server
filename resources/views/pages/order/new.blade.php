@extends('layouts.admin')
@section('title', 'All Admin Users :: Startev Africa')
@section('content')
    <div class="container-fluid" ng-controller="adminNewOrderViewController">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">Startev Admin</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item">Orders</li>
                    <li class="breadcrumb-item active">New Orders</li>
                </ol>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <h4 class="card-title">Manage New Orders</h4>
                        <h6 class="card-subtitle">&nbsp;</h6>

                        <div class="">
                            <div class="table-responsive">
                                <table datatable="ng" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Batch ID</th>
                                        <th>Total Amount</th>
                                        <th>Items</th>
                                        <th>Buyer</th>
                                        <th>Store</th>
                                        <th>Venture</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="order in model.orders">
                                        <td>
                                            @{{ order.batch_id }}
                                        </td>
                                        <td>
                                           @{{ order.amount }}
                                        </td>
                                        <td>
                                            @{{ order.items }}
                                        </td>
                                        <td>@{{ order.buyer.name }}</td>
                                        <td>
                                            @{{ order.store.name }}
                                        </td>
                                        <td>
                                            @{{ order.venture.venture_name }}
                                        </td>
                                        <td class="text-nowrap">
                                            <button type="button" ng-click="model.notify(order)" class="btn btn-circle btn-info">Notify Business</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/orders/adminNewOrderViewController.js') }}"></script>
@stop