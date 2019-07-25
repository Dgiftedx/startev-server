<html style="border: solid 2px;  border-color:#BB2038;">
<head >
    <link href="{{ asset('/pdf-assets/main.css') }}" rel="stylesheet">
</head>
@php($img=public_path('/core-assets/logo/logo_.png'))
<body >
<header style="background-color: #1E6D96;height: 130px;">
    <div style="background-color: #ffffff;height: 150px;width: 200px;margin-left: 265px;margin-top: -10px" >
        <img style="margin-left: 27px" src="{{ $img }}" width="160"/>
    </div>
</header>
<br/>
<div style="background-color: #1E6D96;height: 30px;text-align: center;">
    <h3 style="width: 300px;height: 30px; background-color: #ffffff;text-align: center;color: #BB2038;margin-top: 6px;margin-left: 210px">{{ _('Startev Africa') }}</h3>
</div>
<br>
<div class="row">
    <div style="text-align: center" class="col-md-12">
        <h4 style="text-align: center"><span style="color: #BB2038">Broadcast Schedule Report </span></h4>
    </div>
</div>
<br/>
<div>
    <div style="text-align-all: center;margin-left: 120px;margin-bottom: 50px">
    </div>
</div>
<br/>

@foreach( $schedules as $schedule)
    <table>
        <tr>
            <th>Batch No.</th>
            <th>{{ $schedule->identifier }}</th>
            <th>Schedule Date</th>
            <th>{{ \Carbon\Carbon::parse($schedule->date)->toDateTimeString() }}</th>
            <th>Total Participants</th>
            <th>{{ count($schedule->users) }}</th>
            <th>Status</th>
            <th>{{ $schedule->status }}</th>
        </tr>
    </table>
    <div style="margin-top: 2em;"></div>
    <div class="" style="text-align: center !important;">
        <p style="margin-bottom: 30px;">Participants</p>
    </div>
    <table>
        @foreach( $schedule->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
            </tr>
        @endforeach
    </table>

    <div style="margin-bottom: 4em; margin-top: 4em;"> </div>
@endforeach
</body>
</html>