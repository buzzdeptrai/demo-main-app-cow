<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimit
{
    private RateLimiter $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next)
    {
        $miniApp = $request->attributes->get('mini_app');

        if (!$miniApp) {
            return $next($request);
        }

        $key = 'api-rate:' . $miniApp->id;
        $maxAttempts = $miniApp->api_rate_limit ?: 60;

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = $this->limiter->availableIn($key);

            return response()->json([
                'success' => false,
                'message' => 'Too many requests',
                'retry_after' => $retryAfter,
            ], Response::HTTP_TOO_MANY_REQUESTS)
                ->header('Retry-After', $retryAfter)
                ->header('X-RateLimit-Limit', $maxAttempts)
                ->header('X-RateLimit-Remaining', 0);
        }

        $this->limiter->hit($key, 60);

        $response = $next($request);

        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $this->limiter->remaining($key, $maxAttempts),
        ]);
    }
}
