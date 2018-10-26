<?php namespace Bryangruneberg\Laragoon\Commands;

use Illuminate\Console\Command;

class LaragoonSSHCommand extends LaragoonBaseCommand
{

    protected $signature = 'lagoon:ssh {lenv=@dev : Lagoon Environment}';

    protected $description = 'SSH to a lagoon environment';

    public function handle()
    {
        $lenv = $this->argument('lenv');

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
        
        $cmd = "ssh {$alias['remote-user']}@{$alias['remote-host']} {$alias['ssh-options']}";

        $process = proc_open($cmd, array(0 => STDIN, 1 => STDOUT, 2 => STDERR), $pipes);
        $proc_status = proc_get_status($process);
        $exit_code = proc_close($process);

        return $exit_code;
    }
}
