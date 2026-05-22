<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseAssigned extends Mailable
{
    use SerializesModels;

    public $studentName;
    public $courseTitle;
    public $courseSlug;

    public function __construct($studentName, $courseTitle, $courseSlug)
    {
        $this->studentName = $studentName;
        $this->courseTitle = $courseTitle;
        $this->courseSlug  = $courseSlug;
    }

    public function build()
    {
        $generalSettings = getGeneralSettings();

        return $this->subject('Course Assigned: ' . $this->courseTitle)
            ->from(
                !empty($generalSettings['site_email']) ? $generalSettings['site_email'] : env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            )
            ->view('web.default.emails.course_assigned');
    }
}
