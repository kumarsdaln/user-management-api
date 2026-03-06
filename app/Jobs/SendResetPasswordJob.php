<?php

namespace App\Jobs;

use App\Mail\ResetPasswordEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordJob implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $token;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)
            ->send(new ResetPasswordEmail($this->user, $this->token));
    }
}
