@extends('layouts.admin')
@section('title', 'Manage Feeds - Reports :: Startev Africa')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                Blocked users
            </h4>
        </div>
        <div class="card-body status" id="blockeduser_data">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Blocked users</th>
                        <th>Blocked by</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($blockedUsers) > 0)
                        @foreach($blockedUsers as $blockedUser)
                            <tr>
                                <td>{{$blockedUser->BlockedUsers->name}} <br/>
                                    <small>
                                        {{$blockedUser->BlockedUsers->phone}}
                                    </small>
                                </td>
                                <td>{{$blockedUser->BlockingUser->name}}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm">Blocked</button>
                                </td>
                                <td class="text-nowrap">
                                    <button type="button" class="btn btn-circle btn-info"><i class="fa fa-eye"></i></button>
                                    @role('super')
                                    <button  type="button" class="btn btn-circle btn-danger"><i class="fa fa-trash"></i></button>
                                    @endrole
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td style="height: 45px;text-align: center" colspan="4">No available record.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

@endsection
@section('footerScript')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
@endsection