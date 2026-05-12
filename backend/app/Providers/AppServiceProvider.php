<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Detrás de proxy TLS (Render, etc.): URLs absolutas coherentes con APP_URL.
        $root = (string) config('app.url');
        if (str_starts_with($root, 'https://') || $this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
