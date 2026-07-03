<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasOrganization;
use App\Models\SaasOrganizationSubscription;
use App\Models\SaasPlan;
use App\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_organizations'  => SaasOrganization::count(),
            'active_organizations' => SaasOrganization::where('status', 'active')->count(),
            'trial_organizations'  => SaasOrganization::where('status', 'trial')->count(),
            'suspended'            => SaasOrganization::where('status', 'suspended')->count(),
            'active_subscriptions' => SaasOrganizationSubscription::where('status', 'active')
                                        ->where('expires_at', '>', time())->count(),
            'total_revenue'        => SaasOrganizationSubscription::where('status', 'active')->sum('amount_paid'),
            'total_plans'          => SaasPlan::where('is_active', true)->count(),
        ];

        $recentOrganizations = SaasOrganization::with('owner', 'activeSubscription.plan')
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentOrganizations'));
    }

    public function previewSite()
    {
        // Log out the super admin from the main web guard temporarily,
        // redirect to homepage as a guest, then they can log back in as a normal user.
        auth()->guard('web')->logout();
        request()->session()->forget('_token');
        request()->session()->regenerate();

        return redirect(url('/'));
    }
}
