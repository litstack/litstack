<?php

namespace AwStudio\Fjord\Auth\Controllers;

use AwStudio\Fjord\Actions\SendPasswordResetLink;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController
{
    use SendsPasswordResetEmails;

    protected function guard()
    {
        return Auth::guard('fjord');
    }

    protected function broker()
    {
        return Password::broker('fjord_users');
    }

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
