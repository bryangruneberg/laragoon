<?php namespace Bryangruneberg\Laragoon\Commands;

use Illuminate\Console\Command;
use \Amazee\LaragoonSupport\SiteAliasesFactory;
use \Amazee\LaragoonSupport\SiteAliases;

class LaragoonBaseCommand extends Command
{
    protected $projectName;
    protected $lagoonDetails;
    protected $laragoonSiteAliasesManager;

    public function __construct()
    {
        list($this->projectName, $this->lagoonDetails) = SiteAliasesFactory::loadDefaults(base_path(".lagoon.yml"));
        $this->laragoonSiteAliasesManager = new SiteAliases($this->projectName);

        parent::__construct();
    }

    public function getSiteAliases() 
    {
        return $this->laragoonSiteAliasesManager->getAliases();
    }
}
