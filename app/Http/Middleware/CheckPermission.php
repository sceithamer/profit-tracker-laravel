<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request and check if user has required permission.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission  The required permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        // Ensure user is authenticated
        if (!$user) {
            return redirect()->route('login')
                           ->with('error', 'You must be logged in to access this page.');
        }

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            return redirect()->back()
                           ->with('error', 'You do not have permission to perform this action.')
                           ->withInput();
        }

        return $next($request);
    }
}
