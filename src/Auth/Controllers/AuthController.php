<?php

namespace Ignite\Auth\Controllers;

use Ignite\Auth\Actions\AuthenticationAction;
use Ignite\Auth\Requests\LitSessionLogoutRequest;
use Ignite\Support\Facades\Lit;
use Ignite\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Default url for authenticated users.
     *
     * @var string
     */
    protected $defaultUrl;

    /**
     * Create new AuthController instance.
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
     * Authenticate User.
     *
     * @param Request $request
     *
     * @return redirect
     */
    public function authenticate(Request $request, AuthenticationAction $authentication)
    {
        $request->validate($this->getAuthenticationRules(), __lit('validation'));

        $this->denyDefaultAdminInProduction($request);

        $remember = $request->remember == 'on' || $request->remember;

        $attempt = $authentication->execute($request->only('email', 'password'), $remember);

        if (! $attempt) {
            abort(401, __lit('login.failed'));
        }

        return $this->loginSucceeded();
    }

    /**
     * Deny default admin in production.
     *
     * @return void
     */
    protected function denyDefaultAdminInProduction(Request $request)
    {
        if (! production()) {
            return;
        }

        if ($request->password != 'secret') {
            return;
        }

        if ($request->email != 'email' && $request->email != 'admin') {
            return;
        }

        abort(401, __lit('login.failed'));
    }

    /**
     * Get authentication validation rules.
     *
     * @return array
     */
    public function getAuthenticationRules()
    {
        $rules = [
            'email'    => 'required',
            'password' => 'required',
        ];

        if (config('lit.login.username')) {
            return $rules;
        }

        $rules['email'] .= '|email:rfc,dns';

        return $rules;
    }

    /**
     * Gets executed when the login succeeded.
     *
     * @return string
     */
    public function loginSucceeded()
    {
        return $this->defaultUrl;
    }

    /**
     * Show login.
     *
     * @return View|Redirect
     */
    public function login()
    {
        if (lit_user()) {
            return redirect($this->defaultUrl);
        }

        return view('litstack::auth.login');
    }

    /**
     * Logout user.
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();

        return view('litstack::auth.login');
    }

    /**
     * Logout session.
     *
     * @param LitSessionLogoutRequest $request
     *
     * @return void
     */
    public function logoutSession(LitSessionLogoutRequest $request)
    {
        $session = lit_user()->sessions()->findOrFail($request->id);

        Session::getHandler()->destroy($session->session_id);

        if ($session->session_id == Session::getId()) {
            $this->logout();
        }

        $session->delete();
    }

    /**
     * Create new User.
     *
     * @param Request                  $request
     * @param ForgotPasswordController $sendResetLink
     *
     * @return void
     */
    public function register(Request $request, ForgotPasswordController $sendResetLink)
    {
        $rules = [
            'username'   => ['string', 'max:255', 'unique:lit_users'],
            'first_name' => ['string', 'max:255'],
            'last_name'  => ['string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:lit_users'],
            'password'   => ['required', 'string', 'min:8'],
        ];

        $request->validate($rules);

        $user = User::create([
            'username'   => $request->username,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'locale'     => $request->locale ?? config('lit.locale'),
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);

        // Assign default role.
        $user->assignRole('user');

        // Send reset link.
        if ($request->sendResetLink) {
            $sendResetLink->execute($request);
        }

        return $user;
    }
}
