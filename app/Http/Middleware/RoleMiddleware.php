<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Ensure user is logged in
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Ensure user has a role assigned
        if (!$user->role) {
            abort(403, 'Unauthorized – No role assigned');
        }

        // Check if user's role matches allowed roles
        if (!in_array($user->role->name, $roles)) {
            abort(403, 'Unauthorized – Insufficient permissions');
        }

        return $next($request);
    }
}
