@extends('layouts.admin')
@section('title', 'Manage Contents - Add New Feed :: Startev Africa')
@section('content')

    <div class="card" ng-controller="siteDataViewController">
        <div class="card-header">
            <h4 class="card-title">
                Feed Setting
                <a href="{{ route('feeds') }}" class="btn btn-inverse float-right"><i class="fa fa-arrow-left"></i> Go Back</a>
            </h4>
        </div>
        <div class="card-body status">

            <div class="custom-control custom-switch">
                <input type="checkbox" id="hidden_status"  ng-click="model.changeFeedSetting()"  class="custom-control-input" ng-model="model.feedSetting" ng-checked="model.feedSettingtrue">
                <label class="custom-control-label" for="hidden_status">Toggle this to
                    <span ng-if="model.feedSetting == 1"> manualy publish feeds.</span>
                    <span ng-if="model.feedSetting == 0">auto publish feeds.</span>
                </label>
            </div>

        </div>
    </div>


@endsection
@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/settings/siteDataViewController.js') }}"></script>
@stop