@extends('layouts.admin')
@section('title', 'Create Platform Vocal :: Startev Africa')
@section('content')
    <div class="container-fluid" ng-controller="createVocalViewController">
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
                    <li class="breadcrumb-item"><a href="{{ route('vocals view') }}">Vocal</a></li>
                    <li class="breadcrumb-item active">Create Profile</li>
                </ol>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Create New Vocal Profile</div>

                        <div class="">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form class="form">
                                                <div class="form-group m-t-40 row">
                                                    <label for="full_name" class="col-3 col-form-label required">Full Name</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.vocalProfileData.name" id="full_name" class="form-control" type="text" placeholder="Full Name">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row">
                                                    <label for="email" class="col-3 col-form-label">Email</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.vocalProfileData.email" id="email" class="form-control" type="email" placeholder="Email Address">
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="phone" class="col-3 col-form-label">Phone Number</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.vocalProfileData.phone" id="phone" class="form-control" type="text" placeholder="Phone Number">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="designation" class="col-3 col-form-label">Designation</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.vocalProfileData.designation" id="designation" class="form-control" type="text" placeholder="Designation">
                                                    </div>
                                                </div>

                                                
                                                <div class="form-group row">
                                                    <label for="institution" class="col-3 col-form-label">Institution/Organization/Company</label>
                                                    <div class="col-9">
                                                        <input ng-model="model.vocalProfileData.institution" id="institution" class="form-control" type="text" placeholder="Institution">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row">
                                                    <label for="address" class="col-3 col-form-label">Address</label>
                                                    <div class="col-9">
                                                        <textarea ng-model="model.vocalProfileData.address" class="form-control" placeholder="Residential Address"></textarea>
                                                    </div>
                                                </div>
                                                

                                                <button type="button" button-spinner="model.sendingVocal" ng-click="model.saveVocalData()" class="btn btn-success waves-effect waves-light">Create Profile</button>
                                                <button ng-click="model.clearForm()" type="button" class="btn btn-warning waves-light waves-effect">Clear Form</button>

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
    <script src="{{ asset('/core-assets/scripts/controllers/platform/createVocalViewController.js') }}"></script>
@stop