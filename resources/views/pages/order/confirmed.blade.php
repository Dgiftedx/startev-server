@extends('layouts.admin')
@section('title', 'All Admin Users :: Startev Africa')
@section('content')
    <div class="container-fluid" ng-controller="adminConfirmedOrderViewController">
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
                    <li class="breadcrumb-item active">Confirmed Orders</li>
                </ol>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <h4 class="card-title">Manage Confirmed Orders</h4>
                        <h6 class="card-subtitle text-info">Orders listed below have been confirmed and set to "ready for pickup" by the business owners. <br/>
                        The delivery button should only be clicked when delivery channel returns successful delivery notice</h6>

                        <div class="">
                            <div class="table-responsive">
                                <table datatable="ng" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Batch ID</th>
                                        <th>Order ID</th>
                                        <th>Buyer</th>
                                        <th>Store</th>
                                        <th>Venture</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="order in model.orders">
                                        <td>
                                            @{{ order.batch_id }}
                                        </td>
                                        <td>
                                           @{{ order.identifier }}
                                        </td>
                                        <td>
                                            @{{ order.buyer?order.buyer.name:'N/A' }}
                                        </td>
                                        <td>@{{ order.store.store_name }}</td>
                                        <td>
                                            @{{ order.venture.venture_name }}
                                        </td>
                                        <td>
                                            <button ng-if="order.status === 'confirmed'" button-spinner="model.finalizing" type="button" ng-click="model.conclude(order.id)" class="btn btn-sm waves-effect btn-info">Confirmed</button>
                                            <button ng-if="order.status === 'delivered'" type="button" class="btn btn-sm waves-effect btn-success">Confirmed</button>
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
    <script src="{{ asset('/core-assets/scripts/controllers/orders/adminConfirmedOrderViewController.js') }}"></script>
@stop
