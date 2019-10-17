@extends('layouts.authentication')
@section('title', 'Forgot Password :: Startev Africa Admin')
@section('content')


<div class="main-content- forget-password-area h-100vh">
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-sm-10 col-md-7 col-lg-5 mx-auto">
                <!-- Middle Box -->
                <div class="middle-box">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="font-22">Reset Password</h4>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <!-- Form -->
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group {{ $errors->has('email') ? 'has-danger' : '' }}">
                                    <label class="float-left" for="emailaddress">Email address</label>
                                    <input class="form-control {{ $errors->has('email') ? 'form-control-danger' : '' }}" name="email" type="email" value="{{ $email ?? old('email') }}" id="emailaddress" required="" autocomplete="email" autofocus>
                                    @if($errors->has('email'))
                                    <div class="form-control-feedback ml-2 text-danger" role="alert">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('password') ? 'has-danger' : '' }}">
                                    <label class="float-left" for="password">Password</label>
                                    <input class="form-control {{ $errors->has('password') ? 'form-control-danger' : '' }}" name="password" type="password" required="" id="password" autocomplete="new-password">
                                    @if($errors->has('password'))
                                    <div class="form-control-feedback ml-2 text-danger" role="alert">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="float-left" for="password_confirm">Confirm Password</label>
                                    <input class="form-control" name="password_confirmation" type="password" required="" id="password_confirm" autocomplete="new-password">
                                </div>

                                <div class="btn-area">
                                    <button type="submit" class="btn btn-rounded btn-outline-primary py-2 px-4 btn-block mt-15 text-white">Reset Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
