<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts a route/group to one or more roles, e.g.:
 *
 *   Route::middleware('role:doctor,super_admin')->group(...)
 *
 * Registered as the 'role' alias in bootstrap/app.php. Works
 * alongside (not instead of) Spatie's own can()/Gate checks used in
 * Policies for finer-grained per-record authorization.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
