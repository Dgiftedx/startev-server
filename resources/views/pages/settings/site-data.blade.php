@extends('layouts.admin')
@section('title', 'Side Data Settings :: Startev Africa')
@section('content')
    <div ng-controller="siteDataViewController">
        <div class="row">
            <div class="col-12 mb-3" coderty-loading="model.cargando_main">
                <div class="card">
                    <div class="card-title">
                        <table class="table">
                            <tr>
                                <td><strong>Total Banks:</strong> </td>
                                <td><span class="badge badge-warning pl-4 pt-2 pb-2 pr-4">@{{ model.banksCount }}</span></td>
                                <td>
                                    <button ng-click="model.reloadBanks()" button-spinner="model.reloading" type="button" class="btn btn-sm btn-warning">Reload Banks</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
@section('footerScript')
    <script src="{{ asset('/core-assets/scripts/controllers/settings/siteDataViewController.js') }}"></script>
@stop
