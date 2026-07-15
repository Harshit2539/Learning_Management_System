<?php

namespace App\Console\Commands;

use App\Mail\PlanExpiryReminderMail;
use App\Models\SaasOrganization;
use App\Models\SaasOrganizationSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckSubscriptionExpiry extends Command
{
    protected $signature   = 'saas:check-expiry';
    protected $description = 'Suspend expired organisations and send plan expiry reminder emails';

    public function handle()
    {
        $now = time();

        // ── 1. Suspend organisations whose active subscription has expired ──
        $expiredSubs = SaasOrganizationSubscription::where('status', 'active')
            ->where('expires_at', '<=', $now)
            ->with('organization.owner', 'plan')
            ->get();

        foreach ($expiredSubs as $sub) {
            $org = $sub->organization;

            if (!$org) {
                continue;
            }

            // Mark subscription as expired
            $sub->update(['status' => 'expired']);

            // Suspend the organisation
            $org->update(['status' => SaasOrganization::STATUS_SUSPENDED]);

            // Send expired reminder (daysLeft = 0)
            $this->sendReminder($org, $sub, 0);

            $this->info("Suspended org [{$org->name}] — subscription expired.");
        }

        // ── 2. Send daily reminders for orgs expiring within 2 days ──
        $twoDaysFromNow = $now + (2 * 86400);

        $upcomingSubs = SaasOrganizationSubscription::where('status', 'active')
            ->where('expires_at', '>', $now)
            ->where('expires_at', '<=', $twoDaysFromNow)
            ->with('organization.owner', 'plan')
            ->get();

        foreach ($upcomingSubs as $sub) {
            $org = $sub->organization;

            if (!$org) {
                continue;
            }

            $daysLeft = (int) ceil(($sub->expires_at - $now) / 86400);

            $this->sendReminder($org, $sub, $daysLeft);

            $this->info("Reminder sent to [{$org->name}] — {$daysLeft} day(s) left.");
        }

        // ── 3. Keep sending daily reminders to already-suspended orgs ──
        $suspendedOrgs = SaasOrganization::where('status', SaasOrganization::STATUS_SUSPENDED)
            ->with('owner')
            ->get();

        foreach ($suspendedOrgs as $org) {
            $lastExpiredSub = SaasOrganizationSubscription::where('organization_id', $org->id)
                ->where('status', 'expired')
                ->with('plan')
                ->latest('expires_at')
                ->first();

            if (!$lastExpiredSub) {
                continue;
            }

            $this->sendReminder($org, $lastExpiredSub, 0);

            $this->info("Renewal reminder sent to suspended org [{$org->name}].");
        }

        $this->info('saas:check-expiry completed.');
    }

    private function sendReminder(SaasOrganization $org, SaasOrganizationSubscription $sub, int $daysLeft): void
    {
        $owner = $org->owner;

        if (!$owner || empty($owner->email)) {
            return;
        }

        try {
            Mail::to($owner->email)->send(
                new PlanExpiryReminderMail($org, $sub->plan, $sub, $daysLeft)
            );
        } catch (\Exception $e) {
            $this->error("Mail failed for [{$org->name}]: " . $e->getMessage());
        }
    }
}
