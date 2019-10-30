@extends('layouts.admin')
@section('title', 'All Admin Users :: Startev Africa')
@section('content')

    <div class="row" ng-controller="adminDispatchedOrderViewController">
        <div class="col-12">
            <div class="card" id="main-list">
                <div class="card-body" coderty-loading="model.cargando_main">
                    <h4 class="card-title">Manage Dispatched Orders</h4>
                    <h6 class="card-subtitle text-info mb-2">Orders listed below have been Dispatched. <br/>
                        The delivery button should only be clicked when delivery channel returns successful delivery notice</h6>
                    <div class="table-responsive">
                        <table datatable="ng" class="table table-bordered">
                            <thead>
                            <tr>
{{--                                <th>Batch ID</th>--}}
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Phone</th>
                                <th>Buyer</th>
                                <th>Store</th>
                                <th>Venture</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="order in model.orders">
{{--                                <td>--}}
{{--                                    @{{ order.batch_id }}--}}
{{--                                </td>--}}
                                <td>
                                    @{{ order.identifier }}
                                </td>
                                <td>NGN @{{ order.amount |number:0 }}</td>
                                <td>
                                    @{{ order.buyer.phone?order.buyer.phone:'N/A' }}
                                </td>
                                <td>
                                    @{{ order.buyer?order.buyer.name:'N/A' }}
                                </td>
                                <td>@{{ order.store.store_name }}</td>
                                <td>
                                    @{{ order.venture.venture_name }}
                                </td>
                                <td>
                                    <span ng-if="order.status === 'processing'">@{{ order.status }}</span>
                                    <span ng-if="order.status === 'confirmed'" class="badge badge-primary">Ready for pickup</span>
                                    <span ng-if="order.status === 'shipped'" class="btn badge badge-primary">Shipped (Awaiting Delivery)</span>
                                </td>
                                <td>
                                    <button ng-if="order.status === 'shipped'" button-spinner="model.finalizing" type="button" ng-click="model.conclude(order.id)" class="btn btn-sm waves-effect btn-info">Conclude Order</button>
{{--                                    <button ng-if="order.status === 'delivered'" type="button" class="btn btn-sm waves-effect btn-success">Confirmed</button>--}}
                                    <button type="button" ng-click="model.view(order)" class="btn btn-sm btn-success">View</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Box -->
        <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Transaction Full Details for <strong class="text-primary">#@{{ model.currentTransaction.identifier }}</strong></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="@{{ model.currentTransaction.main_product.images[0] }}" height="120" class="img-responsive" alt=""/>
                            </div>
                            <div class="col-md-8">
                                <table class="table">
                                    <tr>
                                        <td>Total Amount:</td>
                                        <td><strong>NGN @{{ model.currentTransaction.settlement.total |number:0}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Batch ID:</td>
                                        <td>@{{ model.currentTransaction.batch_id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Buyer Name: </td>
                                        <td>@{{ model.currentTransaction.buyer.name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Buyer Email: </td>
                                        <td>@{{ model.currentTransaction.buyer.email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Buyer Phone: </td>
                                        <td>@{{ model.currentTransaction.buyer.phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Order ID:</td>
                                        <td>@{{ model.currentTransaction.identifier }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Name:</td>
                                        <td>@{{ model.currentTransaction.main_product.product_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pickup Point</td>
                                        <td>@{{ model.currentTransaction.venture.venture_address }}</td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Address</td>
                                        <td>@{{ model.currentTransaction.delivery_address }}</td>
                                    </tr>
                                    <tr>
                                        <td>Order Store: </td>
                                        <td>@{{ model.currentTransaction.store.store_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Venture: </td>
                                        <td>@{{ model.currentTransaction.venture.venture_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Commission Payout:</td>
                                        <td><strong>NGN @{{ model.currentTransaction.settlement.commission_payout |number:0 }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Status:</td>
                                        <td>
                                            <strong>
                                                <span class="p-2 badge badge-warning badge-pill" ng-if="model.currentTransaction.status !== 'delivered'">@{{ model.currentTransaction.status }}</span>
                                                <span class="p-2 badge badge-success badge-pill" ng-if="model.currentTransaction.status === 'delivered'">@{{ model.currentTransaction.status }}</span>
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
{{--                        <button type="button" class="btn btn-primary">Print</button>--}}
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/orders/adminDispatchedOrderViewController.js') }}"></script>
@stop
