<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeUser extends Mailable
{
    use SerializesModels;

    public $studentName;
    public $loginIdentifier; // email or mobile
    public $plainPassword;   // only set for admin-created / bulk-imported users

    public function __construct($studentName, $loginIdentifier, $plainPassword = null)
    {
        $this->studentName      = $studentName;
        $this->loginIdentifier  = $loginIdentifier;
        $this->plainPassword    = $plainPassword;
    }

    public function build()
    {
        $generalSettings = getGeneralSettings();

        return $this->subject('Welcome to Bradford LMS')
            ->from(
                !empty($generalSettings['site_email']) ? $generalSettings['site_email'] : env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            )
            ->view('web.default.emails.welcome_user');
    }
}
