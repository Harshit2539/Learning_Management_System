<?php

namespace App\Mail;

use App\Models\SaasOrganization;
use App\Models\SaasOrganizationSubscription;
use App\Models\SaasPlan;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizationWelcomeMail extends Mailable
{
    use SerializesModels;

    public SaasOrganization $org;
    public ?SaasPlan $plan;
    public ?SaasOrganizationSubscription $subscription;
    public string $ownerName;
    public string $ownerEmail;
    public string $plainPassword;

    public function __construct(
        SaasOrganization $org,
        ?SaasPlan $plan,
        ?SaasOrganizationSubscription $subscription,
        string $ownerName,
        string $ownerEmail,
        string $plainPassword
    ) {
        $this->org           = $org;
        $this->plan          = $plan;
        $this->subscription  = $subscription;
        $this->ownerName     = $ownerName;
        $this->ownerEmail    = $ownerEmail;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        $generalSettings = getGeneralSettings();

        $siteName  = $generalSettings['site_name']  ?? config('app.name');
        $fromEmail = !empty($generalSettings['site_email']) ? $generalSettings['site_email'] : config('mail.from.address');
        $fromName  = config('mail.from.name');

        return $this->subject('Welcome to ' . $siteName . ' — Your Organisation is Ready')
            ->from($fromEmail, $fromName)
            ->view('web.default.emails.organization_welcome', [
                'generalSettings' => $generalSettings,
            ]);
    }
}
