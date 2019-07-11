@extends('layouts.admin')
@section('title', 'Manage Platform Users (Businesses) :: Startev Africa')
@section('content')
    <div class="container-fluid" ng-controller="businessesViewController">
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
                    <li class="breadcrumb-item active">Platform Businesses</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body">
                        <h4 class="card-title">Manage Platform Businesses</h4>
                        <div class="float-right mb-5">
                            <button ng-click="model.triggerUserForm('add')" type="button" class="btn waves-effect waves-light btn-rounded btn-success">Add New Business Account</button>
                        </div>

                        <div class="">
                            <div class="table-responsive">
                                <table datatable="ng" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Avatar</th>
                                        <th>Business Name</th>
                                        <th>email</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="business in model.businesses">
                                        <td>
                                            <span class="round"><img src="@{{ business.avatar?business.avatar :'/core-assets/defaults/avatar.jpg' }}" alt="user" width="50"></span>
                                        </td>
                                        <td>
                                            @{{ business.name }}<br/>
                                        </td>
                                        <td>@{{ business.email }}</td>
                                        <td class="text-nowrap">
                                            <button type="button" ng-click="model.editBusiness(business)" class="btn btn-circle btn-info"><i class="fa fa-edit"></i></button>
                                            @role('super')
                                            <button ng-click="model.deleteBusinessAccount(business)" type="button" class="btn btn-circle btn-danger"><i class="fa fa-trash"></i></button>
                                            @endrole
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card hide" id="user-form">
                    <div class="card-body">
                        <div class="card-title">@{{ model.formTitle }}</div>

                        <div class="">
                            <div class="row">
                                <!-- Column -->
                                <div class="col-lg-3 col-xlg-3 col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <div class="m-t-30"> <img id="avatar_preview" src="/core-assets/defaults/avatar.jpg" class="img-circle" width="150">
                                                <h4 class="card-title m-t-10">@{{ model.userData.name }}</h4>
                                                <label for="avatar_upload" class="custom-file-upload waves-effect">
                                                    <i class="fa fa-camera"></i> Business Logo
                                                </label>
                                                <input id="avatar_upload" type="file"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-9 col-xlg-9 col-md-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <form class="form">
                                                <div class="form-group m-t-40 row">
                                                    <label for="full_name" class="col-3 col-form-label">Business Name</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.businessData.name" id="full_name" class="form-control" type="text" placeholder="Business Name">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="email" class="col-3 col-form-label">Email</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.businessData.email" id="email" class="form-control" type="email" placeholder="Email Address">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="phone" class="col-3 col-form-label">Phone</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.businessData.phone" id="phone" class="form-control" type="text" placeholder="Phone">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="description" class="col-3 col-form-label">Business Description</label>
                                                    <div class="col-9">
                                                        <textarea class="form-control" ng-model="model.businessData.description" rows="4" placeholder="Business Description"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="address" class="col-3 col-form-label">Location</label>
                                                    <div class="col-9">
                                                        <textarea ng-model="model.businessData.address" rows="4" class="form-control" placeholder="Location"></textarea>
                                                    </div>
                                                </div>

                                                @hasanyrole('super')
                                                <div class="form-group row">
                                                    <label class="col-3 col-form-label">Password</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.businessData.password" class="form-control" type="password" placeholder="password">
                                                    </div>
                                                </div>
                                                @endhasanyrole

                                                <button type="button" button-spinner="model.sendingUser" ng-click="model.saveBusinessData()" class="btn btn-success waves-effect waves-light">@{{ model.buttonText }}</button>
                                                <button ng-click="model.closeForm()" type="button" class="btn btn-warning waves-light waves-effect">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/platform/businessesViewController.js') }}"></script>
@stop