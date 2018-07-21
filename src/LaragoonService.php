<?php

namespace Bryangruneberg\Laragoon;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

class LaragoonService
{
    private $config;

    public function __construct($laragoonConfig)
    {
        $this->config = $laragoonConfig;
    }

    public function isValidProjectName($projectName)
    {
        return preg_match("/^[\w-]*$/", $projectName);
    }

    public function setLagoonProjectName($projectName)
    {
        $this->setLagoonProjectNameInLagoonYml($projectName);
        $this->setLagoonProjectNameInDockerComposeYml($projectName);
    }

    public function setLagoonProjectNameInLagoonYml($projectName)
    {
        $newLinesBuffer = "";

        $fh = fopen(base_path(). DIRECTORY_SEPARATOR . '.lagoon.yml','r');
        $foundLagoonProject = FALSE;

        while ($line = fgets($fh)) {
            if(preg_match("/^project: /", $line))
            {
                $line = "project: " . $projectName . "\n";
                $foundLagoonProject = TRUE;
            }

            $newLinesBuffer .= $line;
        }

        fclose($fh);

        if (!$foundLagoonProject) {
            throw new LaragoonException("lagoon-project line not located in .lagoon.yml");
        }

        file_put_contents(base_path(). DIRECTORY_SEPARATOR . '.lagoon.yml', $newLinesBuffer);
    }

    public function setLagoonProjectNameInDockerComposeYml($projectName)
    {
        $newLinesBuffer = "";

        $fh = fopen(base_path(). DIRECTORY_SEPARATOR . 'docker-compose.yml','r');

        $foundLagoonProject = FALSE;
        $foundLagoonRoute = FALSE;

        while ($line = fgets($fh)) {
            if(preg_match("/(.*&lagoon-project) (.*)/", $line, $matches))
            {
                $line = $matches[1] . " " . $projectName . "\n";
                $foundLagoonProject = TRUE;
            }

            if(preg_match("/(.*LAGOON_ROUTE:) (.*)/", $line, $matches))
            {
                $line = $matches[1] . " http://" . $projectName . ".docker.amazee.io\n";
                $foundLagoonRoute = TRUE;
            }

            $newLinesBuffer .= $line;
        }

        fclose($fh);

        if (!$foundLagoonProject) {
            throw new LaragoonException("lagoon-project line not located in docker-compose.yml");
        }

        if (!$foundLagoonRoute) {
            throw new LaragoonException("LAGOON_ROUTE line not located in docker-compose.yml");
        }

        file_put_contents(base_path(). DIRECTORY_SEPARATOR . 'docker-compose.yml', $newLinesBuffer);
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
