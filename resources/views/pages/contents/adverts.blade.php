@extends('layouts.admin')
@section('title', 'Manage Contents - Help Tips :: Startev Africa')
@section('content')
    <div ng-controller="advertsController">

        <div class="row">
            <div class="col-12">
                <div class="card" ng-if="model.showMain">
                    <div class="card-body" coderty-loading="model.cargando_main">
                        <button ng-click="model.newBanner()" type="button" class="btn btn-info btn-sm waves-effect float-right">Add New</button>
                        <h4 class="card-title">Adverts</h4>
                        <div class="">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Link</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="advert in model.adverts">
                                        <td>
                                            <img src="/storage@{{ advert.path }}" alt="" style="max-height: 60px;"/>
                                        </td>
                                        <td>
                                            @{{ advert.link }}
                                        </td>
                                        <td>
                                            <button ng-if="advert.status === 'inactive'" class="btn btn-sm btn-success waves-effect waves-light" type="button" ng-click="model.status(advert, 'active')">set active</button>
                                            <button ng-if="advert.status === 'active'" class="btn btn-sm btn-warning waves-effect waves-light" type="button" ng-click="model.status(advert, 'inactive')">inactive</button>
                                            <button ng-click="model.delete(advert)" type="button" class="btn btn-sm btn-danger waves-effect waves-light"><i class="mdi mdi-delete"></i> Delete</button>
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
                                <label class="col-md-12">Advert Link</label>
                                <div class="col-md-12">
                                    <input type="text" ng-model="model.bannerData.link" title="" class="form-control form-control-line">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Upload advert banner below</label>
                                <input type="file"
                                       id="banner"
                                       data-allowed-file-extensions="png jpg jpeg"
                                       data-show-loader="true"
                                       class="dropify">
                            </div>


                            <div class="form-group">
                                <button ng-click="model.saveBanner()" type="submit" class="btn btn-success m-t-20"> Upload</button>
                                <button ng-click="model.close()" class="btn btn-inverse m-t-20"><i class="fa fa-arrow-left"></i> Go Back</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/contents/advertsController.js') }}"></script>
@stop
