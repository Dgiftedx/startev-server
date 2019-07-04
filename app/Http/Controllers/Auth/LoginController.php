<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function login( Request $request )
    {
        $this->validateLogin($request);

        if (auth()->guard('admin')->attempt($this->credentials($request), $request->filled('remember'))) {

            $request->session()->regenerate();

            $this->clearLoginAttempts($request);

            return $this->authenticated($request, auth()->guard('admin')->user()) ?: redirect()->intended($this->redirectPath());
        }


        return $this->sendFailedLoginResponse($request);
    }


    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
