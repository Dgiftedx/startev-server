@extends('layouts.admin')
@section('title', 'Business Payouts :: Startev Africa')
@section('content')
    <div ng-controller="businessViewController">
        <div class="row">
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <div class="table-responsive">
                            <table datatable="ng" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Batch / Reference ID</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-nowrap">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="payout in model.payouts">
                                    <td>
                                        <strong>@{{ payout.batch_ref_id?payout.batch_ref_id:'Still processing payout...' }}</strong>
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
         <div class="modal fade" id="businessModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Payout Details for <strong class="text-primary">#@{{ model.currentPayout.batch_ref_id }}</strong></h4>
                    </div>
                    <div class="modal-body m-2">
                        <div class="row">
                            <div class="col-md-5 border border-warning">
                                <h1>Total Payout <br/> <strong class="text-success">NGN @{{ model.currentPayout.total |number:0 }}</strong></h1>
                                <hr>
                               <div class="text-center">
                                <h6>Store Owner</h6><br/>
                                <h5>@{{ model.currentPayout.business.name }}</h5>
                                <hr>
                                <h6>Store Owner Phone</h6><br/>
                                <h5>@{{ model.currentPayout.business.user.phone }}</h5>
                                <hr>
                                <h6>Store Owner Email</h6><br/>
                                <h5 style="width:50px">@{{ model.currentPayout.business.user.email }}</h5>
                               </div>
                            </div>
                            <div class="col-md-7">
                                <table class="table" ng-repeat="item in model.currentPayout.items">
                                    <tr>
                                        <td>Order ID:</td>
                                        <td>@{{ item.order.identifier }}</td>
                                    </tr>
                                    <tr>
                                        <td>Venture:</td>
                                        <td>@{{ item.venture.venture_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Venture Allocation:</td>
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
    <script src="{{ asset('/core-assets/scripts/controllers/payouts/businessViewController.js') }}"></script>
@stop
