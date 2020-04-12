<?php

namespace Statamic\Addons\Heartbeats;

use Statamic\Extend\ServiceProvider;

class HeartbeatsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $storagePath = site_storage_path('addons/heartbeats/');

        $this->app->singleton(HeartbeatsManager::class, function($app) use ($storagePath) {
            return new HeartbeatsManager($storagePath . 'heartbeats.yaml');
        });
    }

    public function provides()
    {
        return [
            HeartbeatsManager::class,
        ];
    }

}
