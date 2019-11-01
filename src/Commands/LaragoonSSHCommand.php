<?php namespace Bryangruneberg\Laragoon\Commands;

use Illuminate\Console\Command;

class LaragoonSSHCommand extends LaragoonBaseCommand
{

    protected $signature = 'lagoon:ssh {environment=@dev : Lagoon Environment} {--service=cli : Lagoon Service} {--container=cli : Lagoon Container}';

    protected $description = 'SSH to a lagoon environment';

    public function handle()
    {
        if(! $this->commandIsReady()) {
            return $this->handleCommandIsNotReady();
        }
        
        $lenv = $this->argument('environment');
        $service = $this->option('service');
        $container = $this->option('container');

        if ($lenv[0] == "@") {
            $lenv = ltrim($lenv, "@");
        }

        $lenvStyled = "@" . $lenv;

        $this->info("Checking for $lenvStyled");

        $aliases = $this->getSiteAliases();
        $aliasIds = array_keys($aliases);

        if (! in_array($lenv, $aliasIds)) {
            $this->error("$lenvStyled is not a valid site alias id");
            return 255;
        }

        $alias = $aliases[$lenv];
        
        $cmd = "ssh -t {$alias['ssh-options']} {$alias['remote-user']}@{$alias['remote-host']} service={$service} container={$container}";
        if($this->getOutput()->isVerbose()) {
            $this->warn($cmd);
        }

        $process = proc_open($cmd, array(0 => STDIN, 1 => STDOUT, 2 => STDERR), $pipes);
        $proc_status = proc_get_status($process);
        $exit_code = proc_close($process);

        return $exit_code;
    }
}
