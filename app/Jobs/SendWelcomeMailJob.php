<?php

namespace App\Jobs;

use App\Mail\WelcomeUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public string $fullName,
        public string $email,
        public string $password
    ) {}

    public function handle(): void
    {
        Mail::to($this->email)->send(new WelcomeUser($this->fullName, $this->email, $this->password));
    }
}
