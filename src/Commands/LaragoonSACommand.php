<?php namespace Bryangruneberg\Laragoon\Commands;

use Illuminate\Console\Command;

class LaragoonSACommand extends LaragoonBaseCommand
{

    protected $signature = 'lagoon:sa';

    protected $description = 'List available lagoon environments';

    public function handle()
    {
        foreach ($this->getSiteAliases() as $id => $details) {
            $this->line("@" . $id);
        }
    }
}
