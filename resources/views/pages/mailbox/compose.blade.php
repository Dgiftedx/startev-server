@extends('layouts.admin')
@section('title', 'Mail Manager - Compose :: Startev Africa')
@section('content')
    <div ng-controller="composeController">
        <div class="row">
            <!-- Column -->
            <div class="col-xlg-2 col-lg-3 col-md-4">
                <div class="card-body border border-danger inbox-panel">
                    <div class="form-group">
                        <label class="control-label">Check Target Group</label>
                        <div class="m-b-10">
                            <label class="custom-control custom-radio">
                                <input id="radio1"
                                       ng-model="model.targetGroup"
                                       name="radio"
                                       type="radio"
                                       ng-value="'newUser'"
                                       ng-change="model.onCheckTargetGroup()"
                                       class="custom-control-input">
                                <span class="custom-control-label">New Users</span>
                            </label>
                            <label class="custom-control custom-radio">
                                <input id="radio2"
                                       ng-model="model.targetGroup"
                                       name="radio"
                                       type="radio"
                                       ng-value="'platformUsers'"
                                       ng-change="model.onCheckTargetGroup()"
                                       class="custom-control-input">
                                <span class="custom-control-label">Platform Users</span>
                            </label>
                            <label class="custom-control custom-radio">
                                <input id="radio3"
                                       ng-model="model.targetGroup"
                                       name="radio"
                                       type="radio"
                                       ng-value="'admins'"
                                       ng-change="model.onCheckTargetGroup()"
                                       class="custom-control-input">
                                <span class="custom-control-label">All Admins</span>
                            </label>
                        </div>
                        <div id="new-users" class="animated hide">
                            <label class="control-label mt-5">Select Date Range</label>
                            <div class="mt-4">
                                <div class="form-group">
                                    <label>From:</label>
                                    <adm-dtp
                                            options="{gregorianStartDay: 1, calType: 'gregorian', multiple: false, format: 'YYYY/MM/DD'}"
                                            on-datechange="model.updateDate()"
                                            ng-model="model.searchData.from">
                                    </adm-dtp>
                                </div>
                                <div class="form-group">
                                    <label>To:</label>
                                    <adm-dtp
                                            ng-disabled="model.lock"
                                            options="{gregorianStartDay: 1, calType: 'gregorian', multiple: false, format: 'YYYY/MM/DD'}"
                                            on-datechange="model.updateDate()"
                                            ng-model="model.searchData.to">
                                    </adm-dtp>
                                </div>
                            </div>
                        </div>

                        <div id="platform-users" class="animated mb-4 hide">
                            <label class="control-label mt-5">Select User Group</label>
                            <select class="form-control" title="" ng-model="model.searchData.user_group" ng-disabled="model.lock">
                            <option ng-repeat="group in model.userGroups" value="@{{ group.slug }}">@{{ group.name }}</option>
                            </select>
                        </div>
                        <button ng-click="model.lockSelection()" type="button" class="btn btn-danger m-t-20 btn-block waves-effect waves-light">Compose</button>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-xlg-10 col-lg-9 col-md-8 bg-light-part b-l">
                <div class="card-body" coderty-loading="model.cargando_compose_main" ng-if="model.showComposeContainer">
                    <h3 class="card-title">Compose New Message</h3>
                    <div class="form-group">
                        <input class="form-control" ng-model="model.searchData.subject" placeholder="Subject:">
                    </div>
                    <div class="form-group">
                        <ng-ckeditor ng-model="model.searchData.message"
                                     skin="moono-lisa"
                                     remove-buttons="Image"
                                     remove-plugins="iframe,flash,smiley"
                                     msn-count="Number of typed characters:">
                        </ng-ckeditor>
                    </div>
                    {{--<h4><i class="ti-link"></i> Attachment</h4>--}}
                    {{--<form action="#" class="dropzone">--}}
                        {{--<div class="fallback">--}}
                            {{--<input name="file" type="file" multiple />--}}
                        {{--</div>--}}
                    {{--</form>--}}
                    <button ng-click="model.sendMail()" type="submit" class="btn btn-success m-t-20"><i class="zmdi zmdi-save"></i> Send</button>
                    <button ng-click="model.clearForm()" class="btn btn-inverse m-t-20"><i class="fa fa-times"></i> Discard</button>
                </div>
            </div>
            <!-- Column -->
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/mailbox/composeController.js') }}"></script>
@stop
