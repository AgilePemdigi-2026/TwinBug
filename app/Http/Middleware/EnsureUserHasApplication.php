<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasApplication
{
    /**
     * Redirect users who are not connected to an application yet.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->application_id === null) {
            return to_route('applications.onboarding');
        }

        return $next($request);
    }
}
