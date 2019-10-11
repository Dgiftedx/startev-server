@extends('layouts.admin')
@section('title', 'Store Payouts :: Startev Africa')
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
                                    <th>Store</th>
                                    <th>Batch / Reference ID</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-nowrap">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="payout in model.payouts">
                                    <td>
                                        <strong>@{{ payout.store.store_name }}</strong>
                                    </td>
                                    <td>
                                        <strong>@{{ payout.reference_id?payout.reference_id:'Still procesing payout...' }}</strong>
                                    </td>
                                    <td>
                                        <strong ng-if="payout.total">NGN @{{ payout.total | number:0 }}</strong>
                                        <strong ng-if="!payout.total">Not yet compiled</strong>
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
        <div class="modal fade" id="storeModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Payout Details for <strong class="text-primary">#@{{ model.currentPayout.reference_id }}</strong></h4>
                    </div>
                    <div class="modal-body m-2">
                        <div class="row">
                            <div class="col-md-5 border border-warning">
                               <h1>Total Payout <br/>
                                   <strong ng-if="model.currentPayout.total" class="text-success">NGN @{{ model.currentPayout.total |number:0 }}</strong>
                                   <strong ng-if="!model.currentPayout.total" class="text-success">Not known</strong>
                               </h1>
                               <hr>
                               <div class="text-center">
                                <h6>Store Owner</h6><br/>
                                <h4>@{{ model.currentPayout.store.user.name }}</h4>
                                <hr>
                                <h6>Store Owner Phone</h6><br/>
                                <h4>@{{ model.currentPayout.store.user.phone }}</h4>
                                <hr>
                                <h6>Store Owner Email</h6><br/>
                                <h4>@{{ model.currentPayout.store.user.email }}</h4>
                               </div>
                            </div>
                            <div class="col-md-7">
                                <table class="table" ng-repeat="item in model.currentPayout.items">

                                    <tr>
                                        <td>Order ID:</td>
                                        <td>@{{ item.order.identifier }}</td>
                                    </tr>
                                    <tr>
                                        <td>Payout Allocation:</td>
                                        <td><strong>NGN @{{ item.payout |number:0}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="bg-danger">&nbsp;</td>
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
    <script src="{{ asset('/core-assets/scripts/controllers/payouts/storeViewController.js') }}"></script>
@stop
