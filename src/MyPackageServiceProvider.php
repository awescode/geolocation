<?php

namespace Awescode\geoLocation;

use Awescode\geoLocation\Contracts\geoLocation as geoLocationContract;
use Awescode\geoLocation\geoLocation;
use Illuminate\Support\ServiceProvider;

class geoLocationServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/geolocation.php' => config_path('geolocation.php'),
        ], 'config');

        if (! class_exists('CreategeoLocationTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_geolocation_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_geolocation_table.php'),
            ], 'migrations');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(geoLocationContract::class, geoLocation::class);

        $this->app->singleton('geolocation', geoLocationContract::class);

        $this->mergeConfigFrom(
            __DIR__.'/../config/geolocation.php',
            'geolocation'
        );
    }
}
