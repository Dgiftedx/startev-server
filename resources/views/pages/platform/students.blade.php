@extends('layouts.admin')
@section('title', 'Manage Platform Users (Students) :: Startev Africa')
@section('content')
    <div class="container-fluid" ng-controller="studentsViewController">
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
                    <li class="breadcrumb-item active">Platform Students</li>
                </ol>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body">
                        <h4 class="card-title">Manage Platform Students</h4>
                        <div class="float-right mb-5">
                            <button ng-click="model.triggerUserForm('add')" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-success">Add New Student Account</button>
                        </div>

                        <div class="">
                            <div class="table-responsive">
                                <table datatable="ng" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Avatar</th>
                                        <th>Full Name</th>
                                        <th>email</th>
                                        <th>Phone</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="user in model.users">
                                        <td>
                                            <span class="round"><img src="@{{ user.avatar?user.avatar :'/core-assets/defaults/avatar.jpg' }}" alt="user" width="50"></span>
                                        </td>
                                        <td>
                                            @{{ user.name }}<br/>
                                            <small class="text-muted">username: @{{ user.username }}</small>
                                        </td>
                                        <td>@{{ user.email }}</td>
                                        <td>
                                            @{{ user.phone }}
                                        </td>
                                        <td class="text-nowrap">
                                            <button type="button" ng-click="model.editStudentAccount(user)" class="btn btn-circle btn-info"><i class="fa fa-edit"></i></button>
                                            @role('super')
                                            <button ng-click="model.deleteStudentAccount(user)" type="button" class="btn btn-circle btn-danger"><i class="fa fa-trash"></i></button>
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
                                                    <i class="fa fa-camera"></i> Change Avatar
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
                                                    <label for="full_name" class="col-3 col-form-label">Full Name</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.userData.name" id="full_name" class="form-control" type="text" placeholder="Full Name">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="username" class="col-3 col-form-label">Username</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.userData.username" id="username" class="form-control" type="text" placeholder="Username">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="email" class="col-3 col-form-label">Email</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.userData.email" id="email" class="form-control" type="email" placeholder="Email Address">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="official_phone" class="col-3 col-form-label">Official Phone</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.userData.official_phone" id="official_phone" class="form-control" type="tel" placeholder="Official Phone">
                                                    </div>
                                                </div>

                                                @hasanyrole('super')
                                                <div class="form-group row">
                                                    <label class="col-3 col-form-label">Password</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.userData.password" class="form-control" type="password" placeholder="password">
                                                    </div>
                                                </div>
                                                @endhasanyrole
                                                <div class="form-group row">
                                                    <label for="address" class="col-3 col-form-label">Address</label>
                                                    <div class="col-9">
                                                        <textarea ng-model="model.userData.address" class="form-control" placeholder="Residential Address"></textarea>
                                                    </div>
                                                </div>

                                                @role('super')
                                                <div class="form-group row">
                                                    <label for="roles" class="col-3 col-form-label">Select user roles</label>
                                                    <div class="col-9">
                                                        <ui-select ng-model="model.userData.roles" width="100%" title="Choose user roles">
                                                            <ui-select-match placeholder="Select user roles">@{{ $select.selected.name }}</ui-select-match>
                                                            <ui-select-choices repeat="role in model.roles | filter: $select.search">
                                                                <div ng-bind-html="role.name| highlight: $select.search"></div>
                                                            </ui-select-choices>
                                                        </ui-select>
                                                    </div>
                                                </div>
                                                @endrole

                                                <button type="button" button-spinner="model.sendingUser" ng-click="model.saveUserData()" class="btn btn-success waves-effect waves-light">@{{ model.buttonText }}</button>
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
    <script src="{{ asset('/core-assets/scripts/controllers/platform/studentsViewController.js') }}"></script>
@stop