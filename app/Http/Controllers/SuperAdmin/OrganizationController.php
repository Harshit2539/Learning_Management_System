<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\OrganizationWelcomeMail;
use App\Models\SaasOrganization;
use App\Models\SaasOrganizationSubscription;
use App\Models\SaasPlan;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $query = SaasOrganization::with('owner', 'activeSubscription.plan');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('subdomain', 'like', '%' . $request->search . '%');
            });
        }

        $organizations = $query->latest('created_at')->paginate(20);

        return view('superadmin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        $plans = SaasPlan::where('is_active', true)->orderBy('sort_order')->get();
        return view('superadmin.organizations.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:200',
            'subdomain'  => 'required|string|max:100|unique:saas_organizations,subdomain|regex:/^[a-z0-9\-]+$/',
            'owner_name' => 'required|string|max:128',
            'owner_email'=> 'required|email|unique:users,email',
            'owner_password' => 'required|min:8',
            'plan_id'    => 'nullable|exists:saas_plans,id',
        ]);

        // Create owner user
        $role = Role::where('name', 'admin')->first();
        $plainPassword = $request->owner_password;
        $owner = User::create([
            'full_name'    => $request->owner_name,
            'email'        => $request->owner_email,
            'password'     => User::generatePassword($plainPassword),
            'role_name'    => $role->name,
            'role_id'      => $role->id,
            'status'       => 'active',
            'verified'     => true,
            'created_at'   => time(),
        ]);

        // Create organization
        $org = SaasOrganization::create([
            'name'          => $request->name,
            'subdomain'     => strtolower($request->subdomain),
            'owner_id'      => $owner->id,
            'status'        => $request->plan_id ? 'active' : 'trial',
            'trial_ends_at' => $request->plan_id ? null : (time() + (14 * 86400)),
            'created_at'    => time(),
        ]);

        // Link owner to org
        $owner->update(['organization_id' => $org->id]);

        // Attach subscription if plan selected
        $sub  = null;
        $plan = null;
        if ($request->filled('plan_id')) {
            $plan  = SaasPlan::find($request->plan_id);
            $cycle = $request->billing_cycle ?? 'monthly';
            $months = $cycle === 'yearly' ? 12 : 1;

            $sub = SaasOrganizationSubscription::create([
                'organization_id' => $org->id,
                'plan_id'         => $plan->id,
                'billing_cycle'   => $cycle,
                'starts_at'       => time(),
                'expires_at'      => strtotime("+{$months} month"),
                'status'          => 'active',
                'amount_paid'     => $plan->getPrice($cycle),
                'created_at'      => time(),
            ]);
        }

        // Send welcome email (with or without plan)
        try {
            Mail::to($owner->email)->send(
                new OrganizationWelcomeMail($org, $plan, $sub, $request->owner_name, $request->owner_email, $plainPassword)
            );
        } catch (\Exception $e) {
            \Log::error('OrganizationWelcomeMail failed: ' . $e->getMessage());
        }

        return redirect()->route('superadmin.organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    public function show(int $id)
    {
        $org = SaasOrganization::with('owner', 'subscriptions.plan')->findOrFail($id);
        return view('superadmin.organizations.show', compact('org'));
    }

    public function toggleStatus(int $id)
    {
        $org = SaasOrganization::findOrFail($id);
        $org->status = $org->status === 'active' ? 'suspended' : 'active';
        $org->save();

        return back()->with('success', 'Organization status updated.');
    }

    public function assignPlan(Request $request, int $id)
    {
        $request->validate([
            'plan_id'       => 'required|exists:saas_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $org  = SaasOrganization::findOrFail($id);
        $plan = SaasPlan::findOrFail($request->plan_id);
        $cycle = $request->billing_cycle;
        $months = $cycle === 'yearly' ? 12 : 1;

        // Cancel old active subscriptions
        SaasOrganizationSubscription::where('organization_id', $org->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        SaasOrganizationSubscription::create([
            'organization_id' => $org->id,
            'plan_id'         => $plan->id,
            'billing_cycle'   => $cycle,
            'starts_at'       => time(),
            'expires_at'      => strtotime("+{$months} month"),
            'status'          => 'active',
            'amount_paid'     => $plan->getPrice($cycle),
            'created_at'      => time(),
        ]);

        $org->update(['status' => 'active']);

        return back()->with('success', 'Plan assigned successfully.');
    }

    public function destroy(int $id)
    {
        $org = SaasOrganization::findOrFail($id);
        // Soft-suspend instead of hard delete to preserve billing records
        $org->update(['status' => 'suspended']);

        return redirect()->route('superadmin.organizations.index')
            ->with('success', 'Organization suspended.');
    }
}
