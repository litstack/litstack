<?php

namespace Fjord\Auth\Controllers;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController
{
    use SendsPasswordResetEmails;

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
    protected function broker()
    {
        return Password::broker('fjord_users');
    }

    /**
     * Send reset Link.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['success' => true], 200)
            : response()->json(['success' => false], 401);
    }
}
