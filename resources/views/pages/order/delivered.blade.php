@extends('layouts.admin')
@section('title', 'All Admin Users :: Startev Africa')
@section('content')
    <div class="container-fluid" ng-controller="adminDeliveredOrderViewController">
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
                    <li class="breadcrumb-item active">Delivered Orders</li>
                </ol>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <h4 class="card-title">Manage Delivered Orders</h4>
                        <h6 class="card-subtitle">&nbsp;</h6>

                        <div class="">
                            <div class="table-responsive">
                                <table datatable="ng" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Order Date</th>
                                        <th>Batch ID</th>
                                        <th>Item</th>
                                        <th>Item Price</th>
                                        <th>Item QTY</th>
                                        <th>Total Amount</th>
                                        <th>Buyer</th>
                                        <th>Store</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="order in model.orders">
                                        <td>
                                            @{{ order.created_at }}
                                        </td>
                                        <td>
                                            @{{ order.batch_id }}
                                        </td>
                                        <td>
                                            @{{ order.main_product.product_name }}
                                        </td>
                                        <td>
                                           NGN @{{ order.main_product.product_price | number:0 }}
                                        </td>
                                        <td>
                                            @{{ order.quantity | number:0 }}
                                        </td>
                                        <td>
                                           NGN @{{ order.amount | number:0 }}
                                        </td>
                                        <td>@{{ order.buyer.name }}</td>
                                        <td>
                                            @{{ order.store.store_name }}
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
    <script src="{{ asset('/core-assets/scripts/controllers/orders/adminDeliveredOrderViewController.js') }}"></script>
@stop
