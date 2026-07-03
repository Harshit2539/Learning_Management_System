<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('superadmin.login');
        }

        $user = auth()->user();

        if ($user->role_name !== 'super_admin') {
            abort(403, 'Access denied.');
        }

        view()->share('superAdminUser', $user);

        return $next($request);
    }
}
