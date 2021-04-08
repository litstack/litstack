<?php

namespace Ignite\Auth\Controllers;

use Ignite\Support\Facades\Lit;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController
{
    /**
     * Default url for authenticated users.
     *
     * @var string
     */
    protected $defaultUrl;

    /**
     * Create new ResetPasswordController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->defaultUrl = Lit::url(
            config('lit.default_route')
        );
    }

    /**
     * Display the password forgot view.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotForm(Request $request)
    {
        return view('litstack::auth.passwords.forgot');
    }

    /**
     * Handle an incoming forgot password request.
     *
     * @see https://laravel.com/docs/8.x/passwords#password-reset-link-handling-the-form-submission
     *
     * @param  \Illuminate\Http\Request          $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns',
        ]);

        $status = $this->broker()->sendResetLink(
            $request->only('email'),
            function (CanResetPassword $user, string $token) {
                if (method_exists($user, 'sendLitstackPasswordResetNotification')) {
                    $user->sendLitstackPasswordResetNotification($token);
                } else {
                    $user->sendPasswordResetNotification($token);
                }
            });

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __lit($status)])
            : back()->withErrors(['email' => __lit($status)]);
    }

    /**
     * Display the password reset view.
     *
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('litstack::auth.passwords.reset')->with([
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @see https://laravel.com/docs/8.x/passwords#password-reset-link-handling-the-form-submission
     *
     * @param  \Illuminate\Http\Request          $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $params = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $status = $this->broker()->reset($params, function (Model $user) use ($request) {
            $user->forceFill([
                'password'       => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        if ($status == Password::INVALID_TOKEN) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['token' => __lit($status)]);
        }

        return $status == Password::PASSWORD_RESET
            ? redirect($this->defaultUrl)
                ->with('status', __lit($status))
            : back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __lit($status)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Auth\Passwords\PasswordBroker
     */
    public function broker()
    {
        return Password::broker($this->getGuardUserProvider());
    }

    /**
     * Gets the user provider based on the configuration for litstack
     * authentication guard.
     *
     * @return string
     */
    protected function getGuardUserProvider(): string
    {
        $guard = config('lit.guard');

        return config("auth.guards.{$guard}.provider");
    }
}
