<?php

namespace App\Http\Middleware;

use App\Models\SaasOrganization;
use Closure;
use Illuminate\Http\Request;

class OrganizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $org = $this->resolveOrganization($request);

        if (empty($org)) {
            abort(404, 'Organization not found.');
        }

        if ($org->isSuspended()) {
            abort(403, 'This organization has been suspended. Contact support.');
        }

        app()->instance('currentOrganization', $org);
        view()->share('currentOrganization', $org);

        return $next($request);
    }

    private function resolveOrganization(Request $request): ?SaasOrganization
    {
        // Primary: resolve from logged-in user's organization_id
        if (auth()->check() && auth()->user()->organization_id) {
            return SaasOrganization::find(auth()->user()->organization_id);
        }

        // Fallback: resolve from URL segment /org/{subdomain}/...
        $subdomain = $request->route('org_subdomain');
        if ($subdomain) {
            return SaasOrganization::where('subdomain', $subdomain)->first();
        }

        return null;
    }
}
