<?php

namespace App\Mail;

use App\Models\SaasOrganization;
use App\Models\SaasPlan;
use App\Models\SaasOrganizationSubscription;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanExpiryReminderMail extends Mailable
{
    use SerializesModels;

    public SaasOrganization $org;
    public SaasPlan $plan;
    public SaasOrganizationSubscription $subscription;
    public int $daysLeft;

    public function __construct(
        SaasOrganization $org,
        SaasPlan $plan,
        SaasOrganizationSubscription $subscription,
        int $daysLeft
    ) {
        $this->org          = $org;
        $this->plan         = $plan;
        $this->subscription = $subscription;
        $this->daysLeft     = $daysLeft;
    }

    public function build()
    {
        $generalSettings = getGeneralSettings();

        $subject = $this->daysLeft > 0
            ? "Action Required: Your plan expires in {$this->daysLeft} day(s)"
            : "Your plan has expired — Renew now to restore access";

        return $this->subject($subject)
            ->from(
                !empty($generalSettings['site_email']) ? $generalSettings['site_email'] : env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            )
            ->view('web.default.emails.plan_expiry_reminder');
    }
}
