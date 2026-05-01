<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Supports: role:admin  OR  role:admin,retailer  OR  perm:can_manage_orders
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        foreach ($roles as $role) {
            // Permission check: perm:can_manage_orders
            if (str_starts_with($role, 'perm:')) {
                $permission = substr($role, 5);
                if ($user->hasPermission($permission)) {
                    return $next($request);
                }
                continue;
            }

            // Role check
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // Check if user has can_access_admin permission for admin routes
        if ($request->is('admin/*') && $user->hasPermission('can_access_admin')) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
