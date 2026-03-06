<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordConfirmRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->email,
            $request->password
        );

        if (!$result) {
            return $this->error('Invalid credentials', 401);
        }

        return $this->success('Login successful', [
            'user' => $result['user'],
            'token' => $result['token']
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->success('Logout successful');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $this->authService->requestPasswordReset($request->email);

        return $this->success(
            'Password reset email queued successfully',
            [
                'expires_in' => 15 * 60
            ]
        );
    }

    public function confirmResetPassword(ResetPasswordConfirmRequest $request)
    {
        $this->authService->confirmPasswordReset(
            $request->email,
            $request->token,
            $request->password
        );

        return $this->success('Password reset successfully');
    }
}
