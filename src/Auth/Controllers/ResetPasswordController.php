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
            'email'     => $request->email,
            'token'     => $token,
            'user_type' => config('lit.guard'),
        ]);
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
     * Set up Password broker.
     *
     * @return void
     */
    public function broker()
    {
        return Password::broker('lit_users');
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
}
