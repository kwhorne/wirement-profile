<?php

namespace Wirement\Profile\TwoFactor\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForceTwoFactorSetup
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        // Skip if user already has 2FA enabled
        if ($user->hasEnabledTwoFactorAuthentication()) {
            return $next($request);
        }

        // Skip if this is the 2FA setup route
        if ($request->routeIs('filament.*.pages.edit-profile') || 
            $request->routeIs('two-factor.*')) {
            return $next($request);
        }

        // Skip for API routes and AJAX requests
        if ($request->expectsJson() || $request->is('api/*')) {
            return $next($request);
        }

        // Redirect to profile page to set up 2FA
        return redirect()->route('filament.admin.pages.edit-profile')
            ->with('warning', 'You must set up two-factor authentication to continue.');
    }
}
