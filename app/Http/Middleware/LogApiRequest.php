<?php

namespace App\Http\Middleware;

use App\Models\ApiRequestLog;
use Closure;
use Illuminate\Http\Request;

class LogApiRequest
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $this->logRequest($request, $response, $startTime);

        return $response;
    }

    private function logRequest(Request $request, $response, float $startTime): void
    {
        $miniApp = $request->attributes->get('mini_app');
        $responseTimeMs = (int) round((microtime(true) - $startTime) * 1000);

        $tokenId = null;
        if ($request->user() && $request->user()->currentAccessToken()) {
            $tokenId = $request->user()->currentAccessToken()->id;
        }

        ApiRequestLog::create([
            'mini_app_id' => $miniApp ? $miniApp->id : null,
            'token_id' => $tokenId,
            'method' => $request->method(),
            'path' => $request->path(),
            'status_code' => $response->getStatusCode(),
            'ip_address' => $request->ip(),
            'response_time_ms' => $responseTimeMs,
            'created_at' => now(),
        ]);
    }
}
