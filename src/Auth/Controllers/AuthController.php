<?php

namespace Ignite\Auth\Controllers;

use Ignite\Contracts\Auth\Authentication;
use Ignite\Support\Facades\Lit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Lit\Models\User;

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
     * @param  Request        $request
     * @param  Authentication $auth
     * @return redirect
     */
    public function authenticate(Request $request, Authentication $auth)
    {
        $request->validate($this->getAuthenticationRules(), __lit('validation'));

        $this->denyDefaultAdminInProduction($request);

        $remember = $request->remember == 'on' || $request->remember;

        $attempt = $auth->attempt(
            $request->only('email', 'password'), $remember, $request->all()
        );

        if (! $attempt) {
            abort(401, __lit('login.failed'));
        }

        return Session::pull('url.intended', $this->defaultUrl);
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
}
