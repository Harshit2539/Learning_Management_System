<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Mail\OrganizationWelcomeMail;
use App\Models\SaasPlan;
use App\Models\SaasOrganization;
use App\Models\SaasOrganizationSubscription;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PricingController extends Controller
{
    public function index()
    {
        $plans = SaasPlan::getActivePlans();
        return view('saas.pricing', compact('plans'));
    }

    public function showCheckout(Request $request, string $slug)
    {
        $plan  = SaasPlan::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $cycle = $request->query('cycle', 'monthly');
        return view('saas.checkout', compact('plan', 'cycle'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'plan_id'        => 'required|exists:saas_plans,id',
            'billing_cycle'  => 'required|in:monthly,yearly',
            'org_name'       => 'required|string|max:200',
            'subdomain'      => 'required|string|max:100|unique:saas_organizations,subdomain|regex:/^[a-z0-9\-]+$/',
            'owner_name'     => 'required|string|max:128',
            'owner_email'    => 'required|email|unique:users,email',
            'owner_password' => 'required|min:8|confirmed',
        ]);

        $plan  = SaasPlan::findOrFail($request->plan_id);
        $cycle = $request->billing_cycle;

        $role  = Role::where('name', 'admin')->first();
        $owner = User::create([
            'full_name'  => $request->owner_name,
            'email'      => $request->owner_email,
            'password'   => Hash::make($request->owner_password),
            'role_name'  => $role->name,
            'role_id'    => $role->id,
            'status'     => 'active',
            'created_at' => time(),
        ]);

        $org = SaasOrganization::create([
            'name'       => $request->org_name,
            'subdomain'  => strtolower($request->subdomain),
            'owner_id'   => $owner->id,
            'status'     => 'pending',
            'created_at' => time(),
        ]);

        $owner->update(['organization_id' => $org->id]);

        $months = $cycle === 'yearly' ? 12 : 1;
        $sub = SaasOrganizationSubscription::create([
            'organization_id' => $org->id,
            'plan_id'         => $plan->id,
            'billing_cycle'   => $cycle,
            'starts_at'       => time(),
            'expires_at'      => strtotime("+{$months} month"),
            'status'          => 'pending',
            'amount_paid'     => $plan->getPrice($cycle),
            'created_at'      => time(),
        ]);

        session([
            'saas_pending_subscription_id' => $sub->id,
            'saas_pending_org_id'          => $org->id,
        ]);

        return redirect()->route('saas.payment', ['subscription' => $sub->id]);
    }

    public function payment(int $subscription)
    {
        $sub = SaasOrganizationSubscription::with('plan', 'organization')->findOrFail($subscription);

        if ($sub->status !== 'pending') {
            return redirect()->route('saas.pricing');
        }

        return view('saas.payment', compact('sub'));
    }

    public function paymentSuccess(Request $request)
    {
        $subscriptionId = session('saas_pending_subscription_id');
        $orgId          = session('saas_pending_org_id');

        if (!$subscriptionId || !$orgId) {
            return redirect()->route('saas.pricing');
        }

        SaasOrganizationSubscription::where('id', $subscriptionId)->update([
            'status'            => 'active',
            'payment_reference' => $request->payment_reference ?? 'manual',
            'updated_at'        => time(),
        ]);

        SaasOrganization::where('id', $orgId)->update(['status' => 'active']);

        session()->forget(['saas_pending_subscription_id', 'saas_pending_org_id']);

        $org  = SaasOrganization::with('owner')->find($orgId);
        $sub  = SaasOrganizationSubscription::with('plan')->find($subscriptionId);

        // Send welcome email with plan details
        if ($org && $sub && $org->owner && $org->owner->email) {
            try {
                Mail::to($org->owner->email)->send(
                    new OrganizationWelcomeMail($org, $sub->plan, $sub, $org->owner->full_name, $org->owner->email, '(your chosen password)')
                );
            } catch (\Exception $e) {
                \Log::error('OrganizationWelcomeMail failed: ' . $e->getMessage());
            }
        }

        return view('saas.success', compact('org', 'sub'));
    }
}
