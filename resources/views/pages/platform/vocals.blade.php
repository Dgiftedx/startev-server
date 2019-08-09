@extends('layouts.admin')
@section('title', 'View Platform Focals :: Startev Africa')
@section('content')
    <div class="container-fluid" ng-controller="vocalsViewController">
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
                    <li class="breadcrumb-item active">platform Focals</li>
                </ol>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card" id="main-list">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <h4 class="card-title">Manage Platform Focals</h4>
                        <div class="float-right mb-5">
                            <a href="{{ route('create vocal profile') }}" class="btn waves-effect waves-light btn-rounded btn-success">Add New Vocal</a>
                        </div>

                        <div class="">
                            <div class="table-responsive">
                                <table datatable="ng" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Referral Link</th>
                                        <th>Total Referrals</th>
                                        <th>Referrals Today</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="vocal in model.vocals">
                                        <td>
                                            @{{ vocal.name }}
                                        </td>
                                        <td>
                                            <a href="https://app.startev.africa/register?ref_code=@{{ vocal.ref_code }}" target="_blank">@{{ vocal.ref_code }}</a>
                                        </td>
                                        <td>@{{ model.count(vocal.registered_users) }}</td>
                                        <td>
                                            @{{ vocal.refToday }}
                                        </td>
                                        <td class="text-nowrap">
{{--                                            <button type="button" ng-click="model.editStudent(user)" class="btn btn-circle btn-info"><i class="fa fa-edit"></i></button>--}}
                                            <button ng-click="model.deleteVocal(vocal)" type="button" class="btn btn-circle btn-danger"><i class="fa fa-trash"></i></button>
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
                                                        <input ng-model="model.studentData.name" id="full_name" class="form-control" type="text" placeholder="Full Name">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="email" class="col-3 col-form-label">Email</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.studentData.email" id="email" class="form-control" type="email" placeholder="Email Address">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="institution" class="col-3 col-form-label">Institution</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.studentData.institution" id="institution" class="form-control" type="text" placeholder="Institution">
                                                    </div>
                                                </div>

                                                @hasanyrole('super')
                                                <div class="form-group row">
                                                    <label class="col-3 col-form-label">Password</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.studentData.password" class="form-control" type="password" placeholder="password">
                                                    </div>
                                                </div>
                                                @endhasanyrole
                                                <div class="form-group row">
                                                    <label for="address" class="col-3 col-form-label">Address</label>
                                                    <div class="col-9">
                                                        <textarea ng-model="model.studentData.address" class="form-control" placeholder="Residential Address"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="roles" class="col-3 col-form-label">Select user career field</label>
                                                    <div class="col-9">
                                                        <ui-select ng-model="model.studentData.careerPath" width="100%" title="Choose career path">
                                                            <ui-select-match placeholder="Select student career field">@{{ $select.selected.name }}</ui-select-match>
                                                            <ui-select-choices repeat="field in model.fields | filter: $select.search">
                                                                <div ng-bind-html="field.name| highlight: $select.search"></div>
                                                            </ui-select-choices>
                                                        </ui-select>
                                                    </div>
                                                </div>

                                                <button type="button" button-spinner="model.sendingUser" ng-click="model.saveStudentData()" class="btn btn-success waves-effect waves-light">@{{ model.buttonText }}</button>
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
    <script src="{{ asset('/core-assets/scripts/controllers/platform/vocalsViewController.js') }}"></script>
@stop