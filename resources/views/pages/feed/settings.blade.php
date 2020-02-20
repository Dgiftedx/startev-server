@extends('layouts.admin')
@section('title', 'Manage Contents - Add New Feed :: Startev Africa')
@section('content')

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                Feed Setting
                <a href="{{ route('feeds') }}" class="btn btn-inverse float-right"><i class="fa fa-arrow-left"></i> Go Back</a>
            </h4>
        </div>
        <div class="card-body status" id="table_data">

            @include('pages.feed.settings_data')

        </div>
    </div>


@endsection
@section('footerScript')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.status', function (event) {
                event.preventDefault(); //Stop the page from refreshing
                var newstatus = '';
                var status = $('#hidden_status').val();
                if(status == 1){
                    $('#hidden_status').val(0);
                    newstatus =0;
                }else{
                    $('#hidden_status').val(1);
                    newstatus = 1;
                }

                update_data(newstatus);
            });

            function update_data(newstatus){
                console.log(newstatus);
                $.ajax({
                    type:'get',
//                    url:"/manage-contents/feeds/setting?default="+feeddefault,
                    url:"/manage-contents/feeds/updateFeedSetting?default="+newstatus,
                    success:function(data){
                        console.log(data);

                        $('#table_data').html('');
                        $('#table_data').html(data);
                    }
                })
            }
        });

    </script>
@endsection
