@extends('layouts.admin')
@section('title', 'Manage Platform Users (Mentors) :: Startev Africa')
@section('content')
    <div ng-controller="verificationRequestsController">

        <div class="row">
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body">
                        <h4 class="card-title">Verification Requests</h4>
                        <div class="">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Account Name</th>
                                        <th>status</th>
                                        <th>Document</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="request in model.requests">
                                        <td>
                                            <span class="round"><img src="@{{ request.user.avatar?request.user.avatar :'/core-assets/defaults/avatar.jpg' }}" alt="user" width="50"></span><br/>
                                        </td>
                                        <td>
                                            @{{ request.user.name }}
                                        </td>
                                        <td>
                                            <span ng-if="request.status === 'accepted'" class="badge badge-pill badge-success">@{{ request.status }}</span>
                                            <span ng-if="request.status !== 'accepted'" class="badge badge-pill badge-warning">@{{ request.status }}</span>
                                        </td>
                                        <td><button ng-click="model.downloadFile(request.document)" class="btn btn-sm btn-primary">Download</button></td>
                                        <td class="text-nowrap">
                                           <div class="" ng-if="request.status === 'pending'">
                                               <button type="button" ng-click="model.verify(request)" class="btn btn-sm btn-info waves-effect waves-light"> Verify </button>
                                               <button ng-click="model.reject(request)" type="button" class="btn btn-sm btn-danger waves-effect waves-light"> Reject  </button>
                                           </div>
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
    <script src="{{ asset('/core-assets/scripts/controllers/verificationRequestsController.js') }}"></script>
@stop
