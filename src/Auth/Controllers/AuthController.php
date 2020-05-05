<?php

namespace Fjord\Auth\Controllers;

use Illuminate\Http\Request;
use Fjord\Support\Facades\Fjord;
use Fjord\User\Models\FjordUser;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Fjord\Auth\Requests\FjordSessionLogoutRequest;
use Fjord\Auth\Controllers\ForgotPasswordController;

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
        $this->defaultUrl = Fjord::url(
            config('fjord.default_route')
        );
    }

    /**
     * Authenticate FjordUser.
     *
     * @param Request $request
     * @return redirect
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->remember == 'on' || $request->remember;

        if (Auth::guard('fjord')->attempt($credentials, $remember)) {
            return $this->defaultUrl;
        }

        // Try using username.
        if (config('fjord.login.username')) {
            $credentials['username'] = $credentials['email'];
            unset($credentials['email']);
            if (Auth::guard('fjord')->attempt($credentials, $remember)) {
                return $this->defaultUrl;
            }
        }

        return response()->json([
            'message' => __f('login.failed')
        ], 401);
        // TODO: Show login error message.
        //return Redirect::back()->withErrors(['message', 'Login failed.']);
    }

    /**
     * Show login.
     *
     * @return View|Redirect
     */
    public function login()
    {
        if (fjord_user()) {
            return redirect($this->defaultUrl);
        }

        return view('fjord::auth.login');
    }

    /**
     * Logout user.
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();

        return view('fjord::auth.login');
    }

    /**
     * Logout session.
     *
     * @param FjordSessionLogoutRequest $request
     * @return void
     */
    public function logoutSession(FjordSessionLogoutRequest $request)
    {
        $session = fjord_user()->sessions()->findOrFail($request->id);

        Session::getHandler()->destroy($session->session_id);

        if ($session->session_id == Session::getId()) {
            $this->logout();
        }

        $session->delete();
    }

    /**
     * Create new FjordUser.
     *
     * @param Request $request
     * @param ForgotPasswordController $sendResetLink
     * @return void
     */
    public function register(Request $request, ForgotPasswordController $sendResetLink)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:fjord_users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:fjord_users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = FjordUser::create([
            'name' => $request->name,
            'locale' => $request->locale ?? config('fjord.locale'),
            'email' => $request->email,
            'password' => Hash::make($request->password),
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
