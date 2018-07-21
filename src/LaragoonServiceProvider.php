<?php

namespace Bryangruneberg\Laragoon;

use Illuminate\Support\ServiceProvider;
use Bryangruneberg\Laragoon\LaragoonFacade;

class LaragoonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /**
         * Register the console commands
         */
        if ($this->app->runningInConsole()) {
            $this->commands([
                LaragoonDbConfigCommand::class,
                LaragoonSetProjectNameCommand::class,
            ]);
        }

        /**
         * Laragoon configuration
         */
        $this->publishes([
            __DIR__.'/../config/laragoon.php' => config_path('laragoon.php'),
            ], 'laragoon-config');

        /**
         * Laragoon scripts
         */
        $this->publishes([
            __DIR__.'/../lagoon/scripts/' => base_path("scripts"),
        ], 'laragoon-scripts');

        /**
         * Laragoon DB configuration
         */
        $this->publishes([
            __DIR__.'/../config/database.php' => config_path('database.php'),
        ], 'laragoon-db-config');

        /**
         * Laragoon configuration for PHP-7.1
         */
        $this->publishes([
            __DIR__.'/../lagoon/.lagoon.yml' => base_path('.lagoon.yml'),
        ], 'laragoon-lagoon-php-7.1');

        $this->publishes([
            __DIR__.'/../lagoon/config/php-7.1' => base_path('lagoon'),
        ], 'laragoon-lagoon-php-7.1');

        $this->publishes([
            __DIR__.'/../docker/php-7.1' => base_path(),
            ], 'laragoon-lagoon-php-7.1');

        /**
         * Laragoon configuration for PHP-7.2
         */
        $this->publishes([
            __DIR__.'/../lagoon/.lagoon.yml' => base_path('.lagoon.yml'),
        ], 'laragoon-lagoon-php-7.2');

        $this->publishes([
            __DIR__.'/../lagoon/config/php-7.2' => base_path('lagoon'),
        ], 'laragoon-lagoon-php-7.2');

        $this->publishes([
            __DIR__.'/../docker/php-7.2' => base_path(),
        ], 'laragoon-lagoon-php-7.2');
    }

    public function register() 
    {
        LaragoonService::overloadLagoonEnvironment(env('LAGOON_ENVIRONMENT_TYPE'));

        $this->mergeConfigFrom( __DIR__.'/../config/laragoon.php', 'laragoon');

        $this->app->singleton(LaragoonService::class, function($app) {
            $config = $app->make('config');
            $laragoonConfig = $config->get('laragoon');
            return new LaragoonService($laragoonConfig);
        });
    }

    public function provides() {
        return [LaragoonService::class];
    }
}

