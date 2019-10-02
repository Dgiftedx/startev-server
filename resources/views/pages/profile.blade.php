@extends('layouts.admin')
@section('title', 'My Profile :: Startev Africa')
@section('content')
    <div ng-controller="profileController">
        <div class="row">
            <!-- Column -->
            <div class="col-lg-4 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="m-t-30"> <img id="avatar_preview" src="@{{ model.profile.avatar?model.profile.avatar:'/core-assets/defaults/avatar.jpg' }}" class="img-circle" width="150">
                            <h4 class="card-title mt-3">@{{ model.profile.name }}</h4>
                            <label for="avatar_upload" class="custom-file-upload waves-effect">
                                <i class="fa fa-camera"></i> Change Avatar
                            </label>
                            <input id="avatar_upload" type="file"/>
                        </div>
                    </div>
                    <div>
                        <hr> </div>
                    <div class="card-body">
                        <small class="text-muted">Email address </small>
                        <h6>@{{ model.profile.email }}</h6>
                        <small class="text-muted">Phone</small>
                        <h6>@{{ model.profile.official_phone }}</h6>
                        <small class="text-muted">Address</small>
                        <h6>@{{ model.profile.address }}</h6>
                        <small class="text-muted">Social Profile</small>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-8 col-xlg-9 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal form-material">
                            <div class="form-group">
                                <label class="col-md-12">Full Name</label>
                                <div class="col-md-12">
                                    <input type="text" ng-model="model.profile.name" placeholder="Full Name" class="form-control form-control-line">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-12">Email</label>
                                <div class="col-md-12">
                                    <input type="email" ng-model="model.profile.email" placeholder="Email Address" class="form-control form-control-line" id="email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Official Phone No</label>
                                <div class="col-md-12">
                                    <input type="text" ng-model="model.profile.official_phone" placeholder="Phone Number" class="form-control form-control-line">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Country</label>
                                <div class="col-md-12">
                                    <input type="text" ng-model="model.profile.country" placeholder="Enter Your Country" class="form-control form-control-line">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">City</label>
                                <div class="col-md-12">
                                    <input type="text" ng-model="model.profile.city" placeholder="Enter Your City" class="form-control form-control-line">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address" class="col-md-12">Address</label>
                                <div class="col-md-12">
                                    <textarea ng-model="model.profile.address" rows="5" class="form-control form-control-line" id="address"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Change Password</label>
                                <div class="col-md-12">
                                    <input type="password" ng-model="model.profile.password" class="form-control form-control-line">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button ng-click="model.updateProfile()" button-spinner="model.sendingProfile" class="btn btn-outline-info waves-light waves-effect">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/profile/profileController.js') }}"></script>
@stop
