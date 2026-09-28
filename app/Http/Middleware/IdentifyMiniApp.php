<?php

namespace App\Http\Middleware;

use App\Models\MiniApp;
use Closure;
use Illuminate\Http\Request;

class IdentifyMiniApp
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('appSlug');

        if (!$slug) {
            return response()->json([
                'success' => false,
                'message' => 'Mini app not specified',
            ], 400);
        }

        $miniApp = MiniApp::where('slug', $slug)->first();

        if (!$miniApp) {
            return response()->json([
                'success' => false,
                'message' => 'Mini app not found',
            ], 404);
        }

        // Attach mini app to request for downstream use
        $request->merge(['mini_app' => $miniApp]);
        $request->attributes->set('mini_app', $miniApp);

        return $next($request);
    }
}
