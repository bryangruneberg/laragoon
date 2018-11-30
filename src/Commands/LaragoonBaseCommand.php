<?php namespace Bryangruneberg\Laragoon\Commands;

use Illuminate\Console\Command;
use \Amazee\LaragoonSupport\SiteAliasesFactory;
use \Amazee\LaragoonSupport\SiteAliases;
use Illuminate\Support\Facades\Log;

class LaragoonBaseCommand extends Command
{
    protected $projectName;
    protected $lagoonDetails;
    protected $laragoonSiteAliasesManager;

    public function __construct()
    {
        try {
            list($this->projectName, $this->lagoonDetails) = SiteAliasesFactory::loadDefaults(base_path(".lagoon.yml"));
            $this->laragoonSiteAliasesManager = new SiteAliases($this->projectName);
        } catch(\Exception $ex) {
            Log::debug("Error loading the site aliases: " . $ex->getMessage());
        }

        parent::__construct();
    }

    public function getSiteAliases() 
    {
        if($this->laragoonSiteAliasesManager) {
            return $this->laragoonSiteAliasesManager->getAliases();
        } 

        return [];
    }
}
