<?php

namespace App\Jobs;

use App\Mail\CourseAssigned;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCourseAssignedMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public string $fullName,
        public string $email,
        public string $courseTitle,
        public string $courseSlug
    ) {}

    public function handle(): void
    {
        Mail::to($this->email)->send(new CourseAssigned($this->fullName, $this->courseTitle, $this->courseSlug));
    }
}
