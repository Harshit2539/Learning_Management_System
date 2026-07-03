<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SubscriptionActiveMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!app()->has('currentOrganization')) {
            return $next($request);
        }

        $org = app('currentOrganization');

        if (!$org->hasActiveAccess()) {
            // AJAX / API request
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Your subscription has expired. Please renew to continue.',
                ], 403);
            }

            // Redirect to billing/pricing page
            return redirect()->route('saas.pricing')->with(
                'subscription_expired',
                'Your subscription has expired. Please select a plan to continue.'
            );
        }

        return $next($request);
    }
}
