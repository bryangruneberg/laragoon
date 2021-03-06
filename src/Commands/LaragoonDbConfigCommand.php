<?php namespace Bryangruneberg\Laragoon\Commands;

use Illuminate\Console\Command;

class LaragoonDbConfigCommand extends LaragoonBaseCommand
{

    protected $signature = 'laragoon:db-config';

    protected $description = 'Print db-config entry for a standard lagoon installation';

    public function handle()
    {
        if(! $this->commandIsReady()) {
            return $this->handleCommandIsNotReady();
        }
        
        $this->info("The following configuration can be placed in /config/database.php");
        $this->info("To publish this config, you can run:");
        $this->warn("php artisan vendor:publish --force --tag=laragoon-db-config");

        $this->output->write(file_get_contents(__DIR__
            . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . "config"
            . DIRECTORY_SEPARATOR . "database.php")
        );
    }
}
