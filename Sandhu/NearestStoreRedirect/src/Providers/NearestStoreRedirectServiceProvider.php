<?php

namespace Sandhu\NearestStoreRedirect\Providers;

use Illuminate\Support\ServiceProvider;

class NearestStoreRedirectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Log::info('NearestStoreRedirectServiceProvider booted');

        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'neareststore');

        view()->composer('shop::home.index', function ($view) {
            echo view('neareststore::location-popup')->render();
        });
    }

    public function register()
    {
        //
    }
}
