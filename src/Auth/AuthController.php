<?php

namespace AwStudio\Fjord\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('fjord')->attempt($credentials)) {
            return redirect($this->redirectPath());
        }
    }

    public function login()
    {
        if (fjord_user()) {
            return redirect()->route('fjord.dashboard');
        }

        return view('fjord::login');
    }

    public function logout()
    {
        Auth::logout();

        return view('fjord::login');
    }

    public function redirectPath()
    {
        $redirect = '/' . config('fjord.route_prefix') . '/' . config('fjord.default_route');
        return $redirect;
    }
}
