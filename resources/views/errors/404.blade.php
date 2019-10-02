@extends('layouts.error')
@section('title',"404")
@section('content')

    <!-- Error Page Area -->
    <div class="error-page-area">
        <!-- Error Content -->
        <div class="error-content text-center py-5 px-4">
            <!-- Error Thumb -->
            <div class="error-thumb">
                <img src="{{ asset('/base-assets/img/bg-img/1.png') }}" alt="">
            </div>
            <h2>Opps! This page Could Not Be Found!</h2>
            <p>Sorry bit the page you are looking for does not exist, have been removed or name changed</p>
            <a class="btn btn-rounded btn-primary mt-30" href="{{ env('APP_URL') }}">Back To Home</a>
        </div>
    </div>
@endsection