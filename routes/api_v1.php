<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\MiniAppController;
use App\Http\Controllers\Api\V1\MediaController;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
| Prefix: /api/v1
| Middleware: api
*/

// -- Public routes --
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// -- Authenticated routes --
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    // Admin routes (requires admin/manager role)
    Route::middleware('role:admin|manager')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('mini-apps', MiniAppController::class);

        // Mini App token management
        Route::post('mini-apps/{miniApp}/tokens', [MiniAppController::class, 'createToken']);
        Route::delete('mini-apps/{miniApp}/tokens/{tokenId}', [MiniAppController::class, 'revokeToken']);

        // Media management
        Route::post('mini-apps/{miniApp}/media', [MediaController::class, 'upload']);
        Route::delete('media/{mediaId}', [MediaController::class, 'destroy']);
    });

    // Per mini-app routes (token-authenticated, scoped per app)
    Route::prefix('app/{appSlug}')
        ->middleware(['identify.miniapp', 'miniapp.active', 'log.api'])
        ->group(function () {
            Route::get('/', [App\Http\Controllers\Api\V1\App\BaseAppController::class, 'index']);
            Route::get('/settings', [App\Http\Controllers\Api\V1\App\BaseAppController::class, 'settings']);
            Route::get('/media', [App\Http\Controllers\Api\V1\App\BaseAppController::class, 'media']);
        });
});
