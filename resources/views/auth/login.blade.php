@extends('layouts.authentication')
@section('title', 'Login :: Startev Africa Admin')
@section('content')

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <div class="login-register" style="background-image:url(/core-assets/images/startev-pg3.jpg);">
            <div class="login-box card">
                <div class="card-body">
                    <div class="text-center bg-secondary mb-2">
                        <img src="{{ asset('/core-assets/logo/logo_small.png') }}" width="170"/>
                    </div>
                    <form class="form-horizontal form-material" id="loginform" method="POST" action="{{ route('login') }}">
                        @csrf
                        <h3 class="box-title text-center mb-3">Admin Login In</h3>
                        <div class="form-group {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <div class="col-xs-12">
                                <input class="form-control {{ $errors->has('email') ? 'form-control-danger' : '' }}" type="email"  name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
                                @if($errors->has('email'))
                                <div class="form-control-feedback" role="alert">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('password') ? 'has-danger' : '' }}">
                            <div class="col-xs-12">
                                <input class="form-control {{ $errors->has('password') ? 'form-control-danger' : '' }}" type="password" name="password" required autocomplete="current-password" placeholder="Password">
                                @if($errors->has('password'))
                                <div class="form-control-feedback" role="alert">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="checkbox checkbox-info float-left p-t-0">
                                    <input id="checkbox-signup" type="checkbox" class="filled-in chk-col-light-blue" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="checkbox-signup"> {{ _('Remember me') }} </label>
                                </div>
                                @if (Route::has('password.request'))
                                <a href="javascript:void(0)" id="to-recover" class="text-muted float-right"><i class="fa fa-lock m-r-5"></i> {{ _('Forgot pwd?') }}</a>
                                @endif
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-20">
                                <button class="btn btn-block btn-info waves-effect waves-light btn-rounded" type="submit">Log In</button>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" id="recoverform" method="POST" action="{{ route('password.email') }}">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>{{ _('Recover Password') }}</h3>
                                <p class="text-muted">{{ _('Enter your Email and instructions will be sent to you!') }} </p>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <div class="col-xs-12">
                                <input class="form-control {{ $errors->has('email') ? 'form-control-danger' : '' }}" type="email"  name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                                @if($errors->has('email'))
                                <div class="form-control-feedback" role="alert">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-rounded btn-block waves-effect waves-light mb-3" type="submit">{{ __('Send Password Reset Link') }}</button>
                                <a href="javascript:;" class="btn btn-warning btn-sm waves-effect" id="to-loginform">Go back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
