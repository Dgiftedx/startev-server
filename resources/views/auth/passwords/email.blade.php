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
                            <h4 class="font-22">Reset Your Password</h4>
                            <p class="text-muted">{{ _('Enter your email address and instructions will be sent to your email!') }} </p>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <!-- Form -->
                            <form class="m-t" method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="form-group {{ $errors->has('email') ? 'has-danger' : '' }}">
                                    <label class="float-left" for="emailaddress">Email address</label>
                                    <input class="form-control {{ $errors->has('email') ? 'form-control-danger' : '' }}" name="email" type="email" id="emailaddress" required="" placeholder="Enter your email">
                                    @if($errors->has('email'))
                                    <div class="form-control-feedback ml-2 text-danger" role="alert">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                                <div class="btn-area">
                                    <button type="submit" class="btn btn-rounded btn-outline-primary py-2 px-4 btn-block mt-15 text-white">Send new password</button>
                                    <a href="{{ url('/login') }}" class="btn btn-rounded btn-primary py-2 px-4 btn-block mt-15 text-white">Go Back</a>
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
