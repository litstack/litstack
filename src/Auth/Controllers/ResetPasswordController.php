<?php

namespace Fjord\Auth\Controllers;

use Fjord\Support\Facades\Fjord;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ResetPasswordController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
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

    /**
     * Default url for authenticated users.
     *
     * @var string
     */
    protected $defaultUrl;

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
        $this->redirectTo = Fjord::url('login');

        $this->defaultUrl = Fjord::url(
            config('fjord.default_route')
        );
    }

    /**
     * Get guard.
     *
     * @return Guard
     */
    protected function guard()
    {
        return Auth::guard('fjord');
    }

    /**
     * Get broker.
     *
     * @return Broker
     */
    public function broker()
    {
        return Password::broker('fjord_users');
    }

    /**
     * Show reset form.
     *
     * @param Request     $request
     * @param string|null $token
     *
     * @return View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('fjord::auth.passwords.reset')
            ->with([
                'token' => $token,
                'email' => $request->email,
            ]);
    }

    /**
     * Send reset response.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Redirect
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->defaultUrl)
            ->with('status', trans($response));
    }
}
