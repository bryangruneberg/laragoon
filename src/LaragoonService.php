<?php

namespace Bryangruneberg\Laragoon;

class LaragoonService
{
    public function __construct($someconf)
    {

    }

    public static function overloadLagoonEnvironment($lagoonEnvironment)
    {
        // Load environment variables on top of `.env` if it exists.
        $envFile = '.env.' . $lagoonEnvironment;
        if (file_exists(base_path() . DIRECTORY_SEPARATOR . $envFile)) {
            $dotenv = new \Dotenv\Dotenv(base_path(), $envFile);
            $dotenv->overload();
        }
    }
}
