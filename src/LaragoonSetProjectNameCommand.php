<?php

namespace Bryangruneberg\Laragoon;

use Bryangruneberg\Laragoon\LaragoonFacade;
use Illuminate\Console\Command;

class LaragoonSetProjectNameCommand extends Command
{

    const RETURN_SUCCESS = 0;
    const RETURN_FAIL = 255;

    protected $signature = 'laragoon:set-project-name';

    protected $description = 'Print db-config entry for a standard lagoon installation';

    public function handle()
    {
        $laragoon = app(LaragoonService::class);
        $projectName = $this->ask("What is your projects name?");

        while (! $laragoon->isValidProjectName($projectName)) {
            $this->error("The project name should contain only alphanumeric characters or dashes.");
            $projectName = $this->ask("What is your projects name?", $projectName);
        }

        if (! $this->confirm("Are you sure we should set the Lagoon project name to " . $projectName . "?")) {
           $this->error("Bailing out.");
           return;
        }

        try {
            $laragoon->setLagoonProjectName($projectName);
        }
        catch (\ErrorException $exception)
        {
            $this->error($exception->getMessage());
            return self::RETURN_FAIL;
        }
        catch (LaragoonException $exception)
        {
            $this->error($exception->getMessage());
            return self::RETURN_FAIL;
        }

        $this->info(".lagoon.yml and docker-compose.yml updated successfully. Don't forget to rebuild the containers.");
        return self::RETURN_SUCCESS;
    }
}
