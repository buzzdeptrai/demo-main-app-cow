<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMiniAppStatus
{
    public function handle(Request $request, Closure $next)
    {
        $miniApp = $request->attributes->get('mini_app');

        if (!$miniApp || !$miniApp->isActive()) {
            $status = $miniApp ? $miniApp->status : 'unknown';

            return response()->json([
                'success' => false,
                'message' => "Mini app is currently {$status}",
            ], 503);
        }

        return $next($request);
    }
}
