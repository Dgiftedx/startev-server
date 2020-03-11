@extends('layouts.admin')
@section('title', 'Manage Feeds - Reports :: Startev Africa')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                Feeds Reports
            </h4>
        </div>
        <div class="card-body status" id="table_data">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Report</th>
                        <th>Reported by</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--{{$reports}}--}}
                    @foreach($reports as $report)

                    <tr>
                        <td>
                            {{$report->reports}}
                        </td>
                        <td>
                            {{$report->reportUser->name}}
                        </td>
                        <td>
                            @if ($report->status == 0)
                                <button type="button" class="btn btn-danger btn-sm">Not resolved</button>
                            @else
                                <button type="button" class="btn btn-info">Not resolved</button>
                        @endif
                        <td class="text-nowrap">
                            <button type="button" class="btn btn-circle btn-info"><i class="fa fa-eye"></i></button>
                            @role('super')
                            <button  type="button" class="btn btn-circle btn-danger"><i class="fa fa-trash"></i></button>
                            @endrole
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
@section('footerScript')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
@endsection