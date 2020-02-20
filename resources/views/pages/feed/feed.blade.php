@extends('layouts.admin')
@section('title', 'Manage Contents - Add New Feed :: Startev Africa')
@section('content')
    <style>
        .avatar {
            vertical-align: middle;
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
    </style>
    <div class="card">
        <div class="card-header" style="vertical-align: middle;">
            <img src="{{ $feed->user->avatar }}" alt="User avatar" class="avatar">
            <span class="h1" style="vertical-align: middle;">{{ $feed->user->name }}</span>
            <a href="{{ route('feeds') }}" class="btn btn-inverse float-right"><i class="fa fa-arrow-left"></i> Go Back</a>
        </div>
        <div class="card-body row justify-content-center">
            @if($feed->image != null)
            <img src="{{ $feed->image }}" alt="img">
            @endif
            @if($feed->images != null)
            <div id="carouselExampleControls" class="carousel slide col-md-6" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block" src="{{ $feed->images[0] }}" alt="slide">
                    </div>
                    @foreach($feed->images as $image)
                    <div class="carousel-item">
                        <img class="d-block" src="{{ $image }}" alt="slide">
                    </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next text-black" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            @endif
            {!! $feed->content !!}
        </div>
        <div class="card-footer">
            @if($feed->status != 1 && $feed->status != 0 )
                <a href="#"
                   class="btn btn-sm btn-warning waves-effect waves-light text-white review float-right"
                   id="{{ $feed->id }}"
                >Review</a>
            @endif
            @if($feed->status == 0)
                <a href="#"
                   class="btn btn-sm btn-warning waves-effect waves-light text-white publish float-right"
                   id="{{ $feed->id }}"
                >Publish</a>
            @endif
            @if($feed->status == 1 || $feed->status == 0)
                <a href="#"
                   class="btn btn-sm btn-danger waves-effect waves-light text-white trash float-right"
                   id="{{ $feed->id }}"
                >Trash</a>
            @endif
        </div>
    </div>

@endsection

@section('footerScript')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(){

            $(document).on('click', '.trash', function () {
                var id = $(this).attr('id');
                if(confirm("Are you sure you want to trash this data?")){
                    $.ajax({
                        type: "get",
                        url: "/manage-contents/trash_data?id="+id,
                        success:function (data) {
                            location.reload(true);
                        }
                    })
                }else{
                    return false;
                }
            });

            $(document).on('click', '.review', function () {
                var id = $(this).attr('id');
                if(confirm("Are you sure you want to review this data?")){
                    $.ajax({
                        type: "get",
                        url: "/manage-contents/review_data?id="+id,
                        success:function (data) {
                            location.reload(true);
                        }
                    })
                }else{
                    return false;
                }
            });

            $(document).on('click', '.publish', function () {
                var id = $(this).attr('id');
                if(confirm("Are you sure you want to publish this data?")){
                    $.ajax({
                        type: "get",
                        url: "/manage-contents/publish_data?id="+id,
                        success:function (data) {
                            location.reload(true);
                        }
                    })
                }else{
                    return false;
                }
            });

        });
    </script>
@endsection
