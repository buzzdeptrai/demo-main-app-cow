<?php

namespace App\MiniApps;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class MiniAppServiceProvider extends ServiceProvider
{
    /**
     * Middleware applied to all mini app routes.
     */
    private const MIDDLEWARE = [
        'api',
        'identify.miniapp',
        'miniapp.active',
        'log.api',
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerMiniAppRoutes();
    }

    /**
     * Auto-discover and register routes from each mini app folder.
     *
     * Scans each subdirectory of app/MiniApps for a routes.php file
     * and registers it with prefix /api/v1/app/{slug}.
     * The slug is derived from the folder name (PascalCase to kebab-case).
     */
    private function registerMiniAppRoutes(): void
    {
        $miniAppsPath = app_path('MiniApps');

        if (!File::isDirectory($miniAppsPath)) {
            return;
        }

        $directories = File::directories($miniAppsPath);

        foreach ($directories as $directory) {
            $routesFile = $directory . '/routes.php';

            if (!File::exists($routesFile)) {
                continue;
            }

            $folderName = basename($directory);
            $slug = $this->folderNameToSlug($folderName);

            Route::prefix("api/v1/app/{$slug}")
                ->middleware(['api'])
                ->group($routesFile);
        }
    }

    /**
     * Convert PascalCase folder name to kebab-case slug.
     *
     * Example: NnvnApisGo -> nnvn-apis-go
     */
    private function folderNameToSlug(string $folderName): string
    {
        return Str::kebab($folderName);
    }
}
