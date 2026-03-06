<?php

namespace App\Services;

use App\Jobs\SendResetPasswordJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function login($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();
    }

    public function requestPasswordReset($email)
    {
        $user = User::where('email', $email)->firstOrFail();

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        SendResetPasswordJob::dispatch($user, $token)
            ->delay(now()->addSeconds(30));
    }

    public function confirmPasswordReset($email, $token, $password)
    {
        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$reset) {
            throw new \Exception('Invalid reset request');
        }

        if (!Hash::check($token, $reset->token)) {
            throw new \Exception('Invalid reset token');
        }

        if (Carbon::parse($reset->created_at)->addMinutes(15)->isPast()) {
            throw new \Exception('Reset token expired');
        }

        $user = User::where('email', $email)->firstOrFail();

        $user->update([
            'password' => Hash::make($password)
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        // logout all sessions
        $user->tokens()->delete();
    }
}
