@extends('layouts.admin')
@section('title', 'All Admin Users :: Startev Africa')
@section('content')
    <div ng-controller="adminNewOrderViewController">
        <div class="row">
            <div class="col-12">
                <div class="card animated fadeInRight" ng-if="model.showMain">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <h4 class="card-title">Manage Orders</h4>
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
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="order in model.orders">
                                        <td>
                                            @{{ order.batch_id }}
                                        </td>
                                        <td>
                                            @{{ order.items_total | number:0 }}
                                        </td>
                                        <td>
                                            <span class="p2 badge-pill badge-primary">@{{ model.count(order.orders_business) }}</span>
                                        </td>
                                        <td>@{{ order.buyer.name }}</td>
                                        <td>
                                            @{{ order.store.store_name }}
                                        </td>
                                        <td>
                                            <button type="button" ng-click="model.viewDetails(order)"
                                                    class="btn btn-sm waves-effect btn-info">View Items
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card animated fadeInLeft" ng-if="model.showDetails">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <h4 class="card-title">Viewing order for <strong>#@{{ model.currentOrder.batch_id }}</strong>
                        </h4>
                        <h6 class="card-subtitle">&nbsp;</h6>

                        <div class="">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>Batch ID:</td>
                                        <td><strong class="text-danger">@{{ model.currentOrder.batch_id }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Grand Total:</td>
                                        <td><strong>NGN @{{ model.currentOrder.grant_total | number:0 }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Fee:</td>
                                        <td><strong>NGN @{{ model.currentOrder.delivery_fee | number:0 }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Items total:</td>
                                        <td><strong>NGN @{{ model.currentOrder.items_total| number:0 }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Commission:</td>
                                        <td>
                                            <strong>NGN @{{ model.currentOrder.total_student_commission | number:0 }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Store</td>
                                        <td class="text-danger">
                                            <strong>@{{ model.currentOrder.store.store_name }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Buyer Information</td>
                                        <td>
                                            <table class="table">
                                                <tr>
                                                    <td>Name</td>
                                                    <td><strong>@{{ model.currentOrder.buyer.name }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td><strong>@{{ model.currentOrder.buyer.email }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td><strong>@{{ model.currentOrder.buyer.phone }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Delivery Address</td>
                                                    <td><strong>@{{ model.currentOrder.buyer.address }}</strong></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center" colspan="2"><strong>PRODUCTS</strong></td>
                                    </tr>
                                </table>

                                <table class="table" ng-repeat="item in model.currentOrder.orders_business">
                                    <tr>
                                        <td>Product Name:</td>
                                        <td>@{{ item.main_product.product_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Commission</td>
                                        <td>@{{ item.main_product.product_commission }}%</td>
                                    </tr>
                                    <tr>
                                        <td>Amount</td>
                                        <td>
                                            NGN @{{ item.amount | number:0 }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td><span class="badge badge-primary">@{{ item.status }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Venture</td>
                                        <td class="text-danger"><strong>@{{ item.venture.venture_name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Venture Address</td>
                                        <td class="text-danger"><strong>@{{ item.venture.venture_address }}</strong>
                                        </td>
                                    </tr>
                                    <tr ng-if="item.status !== 'confirm' && item.status !== 'delivered'">
                                        <td colspan="2">
                                            <button ng-click="model.dispatchOrder(item)" type="button"
                                                    class="btn btn-sm btn-success">Dispatch
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </table>
                            </div>

                            <button type="button" ng-click="model.close();" class="btn btn-sm btn-primary waves-effect">
                                Go Back
                            </button>
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
