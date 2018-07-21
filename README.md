# Laragoon

## Introduction
This package provides a simple way to run a Laravel app on the wonderful amazee.io Lagoon infrastructure.

The package consists of a few components:
 - Several Dockerfiles to run a basic Laravel app
 - A set of default configuration overrides for the containers
 - A console command that dumps the database stanza to enable a connection to the Lagoon database container
 - A console command that will update your .lagoon.yml and docker-compose.yml with a project name you provide
 - A service provider which 
   - Makes the Dockerfiles, the configuration files, and severl supporting files publishable
   - Looks for a Lagoon environment and overloads an environment specific config file if found 

## Requirements

This package is supported on Laravel 5.5 and above.

It is assumed that you have amazee.io's Lagoon requirements already installed. See http://lagoon.rtfd.io/ for details.

It is assumed that you have a Laravel app already running. 

## Installation

`composer require bryangruneberg/laragoon`

## Configuration

To publish the laragoon configuration run:
`php artisan vendor:publish --tag=laragoon-config`

To publish the laragoon database configuration run: 
`php artisan vendor:publish --tag=laragoon-db-config`. But be careful, the /config/database.php that comes with Laravel will prevent 
the laragoon configuration from writing. You can choose to force the operation by appending the --force option to the command.
Alternatively run `php artisan laragoon:db-config` to have a database stanza outputted to the console.

To publish Docker and Lagoon files for php-7.1 run: `php artisan vendor:publish --tag=laragoon-lagoon-php-7.1`

To publish Docker and Lagoon files for php-7.2 run: `php artisan vendor:publish --tag=laragoon-lagoon-php-7.2`

To publish helpful artisan scripts run: `php artisan vendor:publish --tag=laragoon-scripts`

Finally, to set your Lagoon project name, run `php artisan laragoon:set-project-name` and follow the prompts.

You can go ahead and customize the Dockerfiles, .lagoon.yml, and docker-compose.yml. If you want to retrieve the 
original files, you can rerun the vendor:publish commands, appending the --force option to force an overwrite. 

## Running

Once configured, you can run `docker-compose build` to have docker-compose build your environment. Once built you
can run `docker-compose up` to have the environment spin up. Append the -d flag to have the containers run in the 
background.

