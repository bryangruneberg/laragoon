<?php

namespace Bryangruneberg\Laragoon;

use Illuminate\Support\ServiceProvider;

class LaragoonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laragoon.php' => config_path('laragoon.php'),
            ], 'laragoon-config');

        $this->publishes([
            __DIR__.'/../lagoon/' => base_path('lagoon'),
            ], 'laragoon-lagoon');

        $this->publishes([
            __DIR__.'/../docker/' => base_path(),
            ], 'laragoon-lagoon');
    }

    public function register() 
    {
        LaragoonService::overloadLagoonEnvironment(env('LAGOON_ENVIRONMENT_TYPE'));

        $this->mergeConfigFrom( __DIR__.'/../config/laragoon.php', 'laragoon');

        $this->app->singleton('laragoon', function($app) {
            $config = $app->make('config');
            $laragoonConfig = $config->get('laragoon');
            return new LaragoonService($laragoonConfig);
        });
    }

    public function provides() {
        return ['laragoon'];
    }
}

