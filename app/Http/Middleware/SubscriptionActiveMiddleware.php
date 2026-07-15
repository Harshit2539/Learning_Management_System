<?php

namespace App\Http\Middleware;

use App\Models\SaasOrganization;
use Closure;
use Illuminate\Http\Request;

class SubscriptionActiveMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || empty($user->organization_id)) {
            return $next($request);
        }

        $org = SaasOrganization::find($user->organization_id);

        if (!$org) {
            return $next($request);
        }

        // Allow trial access
        if ($org->isOnTrial()) {
            return $next($request);
        }

        // Check active subscription
        $activeSub = $org->activeSubscription()->first();

        if (!$activeSub) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Your subscription has expired. Please renew to continue.',
                ], 403);
            }

            return redirect()->route('saas.pricing')->with(
                'subscription_expired',
                'Your subscription has expired. Please select a plan to continue.'
            );
        }

        return $next($request);
    }
}
