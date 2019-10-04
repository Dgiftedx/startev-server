@extends('layouts.admin')
@section('title', 'Delivery Payout :: Startev Africa')
@section('content')
    <div ng-controller="storeViewController">
        <div class="row">
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <div class="table-responsive">
                            <table datatable="ng" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-nowrap">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="payout in model.payouts">
                                    <td>
                                        <strong>@{{ payout.id }}</strong>
                                    </td>

                                    <td>
                                        <strong>NGN @{{ payout.total | number:0 }}</strong>
                                    </td>
                                    <td>
                                        <span ng-if="payout.status === 'pending'" class="badge badge-danger badge-pill">
                                            @{{ payout.status }}
                                        </span>
                                        <span ng-if="payout.status === 'processed'" class="badge badge-success badge-pill">
                                            @{{ payout.status }}
                                        </span>
                                    </td>
                                    <td>
                                        @{{ payout.created-at | number:0 }}
                                    </td>
                                    <td>
                                        <button type="button" ng-click="model.viewDetails(payout)" class="btn btn-sm btn-primary">View Details</button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Box -->
        <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Transaction Full Details for <strong class="text-primary">#@{{ model.currentTransaction.order.identifier }}</strong></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="@{{ model.currentTransaction.order.main_product.images[0] }}" height="120" class="img-responsive" alt=""/>
                            </div>
                            <div class="col-md-8">
                                <table class="table">
                                    <tr>
                                        <td>Total Amount:</td>
                                        <td><strong>NGN @{{ model.currentTransaction.total |number:0}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Batch ID:</td>
                                        <td>@{{ model.currentTransaction.order.batch_id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Order ID:</td>
                                        <td>@{{ model.currentTransaction.order.identifier }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Name:</td>
                                        <td>@{{ model.currentTransaction.order.main_product.product_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Order Store: </td>
                                        <td>@{{ model.currentTransaction.order.store.store_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product Venture: </td>
                                        <td>@{{ model.currentTransaction.order.venture.venture_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Commission Payout:</td>
                                        <td><strong>NGN @{{ model.currentTransaction.commission_payout |number:0 }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Status:</td>
                                        <td>
                                            <strong>
                                                <span class="p-2 badge badge-success badge-pill" ng-if="model.currentTransaction.status === 'paid'">@{{ model.currentTransaction.status }}</span>
                                                <span class="p-2 badge badge-warning badge-pill" ng-if="model.currentTransaction.status === 'unpaid'">@{{ model.currentTransaction.status }}</span>
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
    <script src="{{ asset('/core-assets/scripts/controllers/payouts/deliveryViewController.js') }}"></script>
@stop
