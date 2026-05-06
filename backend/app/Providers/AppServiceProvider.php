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
        // In cloud platforms behind reverse proxies (e.g. Render),
        // force generated URLs to use HTTPS to avoid mixed content.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
