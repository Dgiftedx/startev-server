@extends('layouts.admin')
@section('title', 'Manage Contents - Help Tips :: Startev Africa')
@section('content')
    <div class="container-fluid" ng-controller="helpTipsController">
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
                    <li class="breadcrumb-item">Content Management</li>
                    <li class="breadcrumb-item active">Help Tips</li>
                </ol>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card" ng-if="model.showMain">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <button ng-click="model.addNewTip()" type="button" class="btn btn-info btn-sm waves-effect waves-light float-right">Add New</button>
                        <h4 class="card-title">Help Tips</h4>
                        <div class="">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Tip Title</th>
                                        <th>Tip Target</th>
                                        <th>Tip Content</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="tip in model.helpTips">
                                        <td>
                                            @{{ tip.title }}
                                        </td>
                                        <td>
                                            @{{ tip.target }}
                                        </td>
                                        <td>
                                            @{{ tip.content }}
                                        </td>
                                        <td class="text-nowrap">
                                            <button type="button" ng-click="model.editTip(tip)" class="btn btn-sm btn-info waves-effect waves-light"><i class="mdi mdi-account-edit"></i> Edit </button>
                                            <button ng-click="model.deleteTip(tip)" type="button" class="btn btn-sm btn-danger waves-effect waves-light"><i class="mdi mdi-delete"></i> Delete  </button>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card" ng-if="model.showForm">
                    <div class="card-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-12">Help Tip Title</label>
                                <div class="col-md-12">
                                    <input type="text" ng-model="model.currentTip.title" title="" class="form-control form-control-line">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-12">Help Tip Target</label>
                                <div class="col-md-12">
                                    <select class="form-control" title="" ng-model="model.currentTip.target">
                                        <option ng-repeat="group in model.userGroups" value="@{{ group.slug }}">@{{ group.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-12">Help Tip Content</label>
                                <div class="col-md-12">
                                <textarea class="form-control" title="" rows="7" ng-model="model.currentTip.content"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <button ng-click="model.submitTip()" type="submit" class="btn btn-success m-t-20"> Submit</button>
                                <button ng-click="model.goBack()" class="btn btn-inverse m-t-20"><i class="fa fa-arrow-left"></i> Go Back</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/contents/helpTipsController.js') }}"></script>
@stop