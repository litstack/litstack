<?php

namespace Fjord\Auth\Controllers;

use Illuminate\Http\Request;
use Fjord\Support\Facades\Fjord;
use Fjord\User\Models\FjordUser;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        if (Auth::guard('fjord')->attempt($credentials)) {
            return redirect($this->defaultUrl);
        }

        // TODO: Show login error message.
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
