@extends('layouts.authentication')
@section('title', 'Login :: Startev Africa Admin')
@section('content')

<div class="main-content- h-100vh">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-sm-10 col-lg-8">
                <!-- Middle Box -->
                <div class="middle-box">
                    <div class="card mb-0">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="xs-d-none">
                                        <img src="{{ asset('/base-assets/img/bg-img/6.png') }}" alt="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Logo -->
                                    <div class="card-body-login bg-dark mb-30">
                                        <img src="{{ asset('/core-assets/logo/logo.png') }}" alt="">
                                    </div>

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group {{ $errors->has('email') ? 'has-danger' : '' }}">
                                            <label class="float-left" for="emailaddress">Email address</label>
                                            <input class="form-control {{ $errors->has('email') ? 'form-control-danger' : '' }}" name="email" type="email" id="emailaddress" autocomplete="off" required="" placeholder="Enter your email">
                                            @if($errors->has('email'))
                                            <div class="form-control-feedback ml-2 text-danger" role="alert">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>


                                        <div class="form-group {{ $errors->has('password') ? 'has-danger' : '' }}">
                                            <a href="{{ route('password.request') }}" class="text-dark float-right"><span class="font-12 text-primary">Forgot your password?</span></a>
                                            <label class="float-left" for="password">Password</label>
                                            <input class="form-control {{ $errors->has('password') ? 'form-control-danger' : '' }}" name="password" type="password" required="" autocomplete="new-password" id="password" placeholder="Enter your password">
                                            @if($errors->has('password'))
                                            <div class="form-control-feedback ml-2 text-danger" role="alert">{{ $errors->first('password') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="custom-control custom-checkbox pl-0">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="remember" id="customCheck1" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="customCheck1"><span class="font-16">{{ _('Remember me') }}</span></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0 text-center">
                                            <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
