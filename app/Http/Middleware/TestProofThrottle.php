<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;

class TestProofThrottle extends ThrottleRequests
{
    /**
     * Disable Throttling in testing environment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        if (env('APP_ENV') === 'testing') {
            return $next($request);
        }

        return parent::handle($request, $next, $maxAttempts = 60, $decayMinutes = 1);
    }

}
