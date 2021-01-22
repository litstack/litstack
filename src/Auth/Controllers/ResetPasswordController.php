<?php

namespace Ignite\Auth\Controllers;

use Ignite\Support\Facades\Lit;
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
     * @param  \Illuminate\Http\Request          $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function request(Request $request)
    {
        $request->validate($this->rules());

        $status = $this->broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password'       => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect($this->defaultUrl)->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
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
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    /**
     * Gets the user provider based on the configuration for litstack
     * authentication guard.
     *
     * @return string
     */
    protected function getGuardUserProvider(): string
    {
        $litGuard = config('lit.guard');

        return config('auth.guards')[$litGuard]['provider'];
    }
}
