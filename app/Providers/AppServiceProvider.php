<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerRenderHook(
                'footer.end',
                fn () => new HtmlString('
                    <div style="text-align:center; padding:12px 0; font-size:13px; color:#6b7280; border-top:1px solid #e5e7eb; margin-top:8px;">
                        Powered by <a href="https://vusol.io.vn/" target="_blank" style="color:#3b82f6; text-decoration:none; font-weight:600;">VuSol</a>
                        &nbsp;·&nbsp;
                        <a href="mailto:vuquocvietkg@gmail.com" style="color:#3b82f6; text-decoration:none;">vuquocvietkg@gmail.com</a>
                    </div>
                ')
            );
        });
    }
}
