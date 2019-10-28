<?php

namespace AwStudio\Fjord\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;;
use AwStudio\Fjord\Facades\Fjord;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected function guard()
    {
      return Auth::guard('fjord');
    }

    public function login()
    {
        if (auth()->user()) {
            return redirect()->route('fjord.dashboard');
        }

        return view('fjord::login');
    }

    public function postLogin(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        if ($this->guard()->attempt($credentials, $request->has('remember'))) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout()
    {
        auth()->logout();

        return view('fjord::login');
    }

    public function redirectPath()
    {
        $redirect = '/' . config('fjord.route_prefix') . '/' . config('fjord.default_route');
        return $redirect;
    }

}
