<?php

namespace Wirement\Profile\TwoFactor\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorChallenge
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

        if (! $user->hasEnabledTwoFactorAuthentication()) {
            return $next($request);
        }

        if ($request->session()->get('two_factor_confirmed_at') > now()->subMinutes(config('wirement-profile.two_factor.challenge_timeout', 10800))) {
            return $next($request);
        }

        return redirect()->route('two-factor.challenge');
    }
}
