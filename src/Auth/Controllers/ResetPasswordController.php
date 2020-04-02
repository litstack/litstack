<?php

namespace AwStudio\Fjord\Auth\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResetPasswordController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected function guard()
    {
        return Auth::guard('fjord');
    }

    public function broker()
    {
        return Password::broker('fjord_users');
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create new ResetPasswordController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = config('fjord.route_prefix') . '/login';
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('fjord::auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())
            ->with('status', trans($response));
    }

    public function redirectPath()
    {
        $redirect = '/' . config('fjord.route_prefix') . '/' . config('fjord.default_route');
        return $redirect;
    }
}
