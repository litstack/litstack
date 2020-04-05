<?php

namespace Fjord\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Fjord\Fjord\Models\FjordUser;
use Fjord\Auth\Controllers\ForgotPasswordController;

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

        return view('fjord::auth.login');
    }

    public function logout()
    {
        Auth::logout();

        return view('fjord::auth.login');
    }

    public function redirectPath()
    {
        $redirect = '/' . config('fjord.route_prefix') . '/' . config('fjord.default_route');
        return $redirect;
    }


    public function register(Request $request, ForgotPasswordController $sendResetLink)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:fjord_users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:fjord_users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = FjordUser::create([
            'name' => $request->name,
            'locale' => $request->locale ?? 'de',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        if ($request->sendResetLink) {
            $sendResetLink->execute($request);
        }

        return $user;
    }
}
