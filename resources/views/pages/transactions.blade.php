@extends('layouts.admin')
@section('title', 'Transactions :: Startev Africa')
@section('content')
    <div ng-controller="transactionsViewController">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" ng-true-value="'unpaid'"
                                   ng-model="model.queryParams.unpaid" id="unpaid" ng-change="model.refineParams()">
                            <label class="custom-control-label" for="unpaid">filter by "Unpaid Settlements"</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" ng-true-value="'paid'"
                                   ng-model="model.queryParams.paid" id="paid" ng-change="model.refineParams()">
                            <label class="custom-control-label" for="paid">filter by "Paid Settlements"</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" ng-click="model.resetFilter()" class="btn btn-sm btn-fill-warning bg-chok">clear filter</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-sm btn-primary">export transactions in excel</button>
                    </div>

                </div>
            </div>
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <div class="table-responsive">
                            <table datatable="ng" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Sales</th>
                                    <th>PayStack Charge</th>
                                    <th>Startev</th>
                                    <th>Commission Payout</th>
                                    <th>Delivery</th>
                                    <th>Business Payout</th>
                                    <th>Escrowed</th>
                                    <th>Status</th>
                                    <th class="text-nowrap">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="transaction in model.transactions">
                                    <td>
                                        <strong>NGN @{{ transaction.total | number:0 }}</strong>
                                    </td>
                                    <td>
                                        <strong>NGN @{{ transaction.paystack_charge | number:0 }}</strong>
                                    </td>
                                    <td><strong>NGN @{{ transaction.startev_charge | number:0 }}</strong></td>
                                    <td><strong>NGN @{{ transaction.commission_payout | number:0 }}</strong></td>
                                    <td><strong>NGN @{{ transaction.delivery | number:0 }}</strong></td>
                                    <td><strong>NGN @{{ transaction.business_payout | number:0 }}</strong></td>
                                    <td><strong>NGN @{{ transaction.escrowed | number:0 }}</strong></td>
                                    <td>
                                        <span ng-if="transaction.status === 'unpaid'" class="badge badge-danger badge-pill">
                                            @{{ transaction.status }}
                                        </span>
                                        <span ng-if="transaction.status === 'paid'" class="badge badge-success badge-pill">
                                            @{{ transaction.status }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" ng-click="model.viewDetails(transaction)" class="btn btn-sm btn-primary">View Details</button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card hide" id="user-form">

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
                        <button type="button" class="btn btn-primary">Print</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/transactionsViewController.js') }}"></script>
@stop
