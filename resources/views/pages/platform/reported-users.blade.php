@extends('layouts.admin')
@section('title', 'Manage Feeds - Reports :: Startev Africa')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                Reported users
            </h4>
        </div>
        <div class="card-body status" id="reporteduser_data">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Reported users</th>
                        <th>Reported by</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                    </thead>
                    <tbody class="reportuser">
                    @if (count($reportedUsers) > 0)
                        @foreach($reportedUsers as $reportedUser)
                            <tr>
                                <td>{{$reportedUser->ReportedUser->name}}</td>
                                <td>{{$reportedUser->ReportingUser->name}}</td>
                                <td>
                                    @if ($reportedUser->status == 0)
                                        <button type="button" class="btn btn-danger btn-sm">Not resolved</button>
                                        @else
                                        <button type="button" class="btn btn-danger btn-sm">resolved</button>
                                        @endif
                                </td>
                                <td class="text-nowrap">
                                    <button type="button" class="btn btn-circle btn-info"><i class="fa fa-eye"></i></button>
                                    @role('super')
                                    <button id="deleteReportUser" type="button" class="btn btn-circle btn-danger"><i class="fa fa-trash"></i></button>
                                    @endrole
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
@endsection
@section('footerScript')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('.reportuser').on('click', '#deleteReportUser', function(event){
//                        console.log('checked');
//                        console.log(event);
                    } );
                });
            </script>
@endsection