<?php namespace Bryangruneberg\Laragoon\Commands;

use Illuminate\Console\Command;
use \Amazee\LaragoonSupport\SiteAliasesFactory;
use \Amazee\LaragoonSupport\SiteAliases;

class LaragoonBaseCommand extends Command
{
    protected $projectName;
    protected $lagoonDetails;
    protected $laragoonSiteAliasesManager;
    
    const RETURN_FAIL=254;

    public function __construct()
    {
        if(file_exists(base_path(".lagoon.yml"))) {
            list($this->projectName, $this->lagoonDetails) = SiteAliasesFactory::loadDefaults(base_path(".lagoon.yml"));
            $this->laragoonSiteAliasesManager = new SiteAliases($this->projectName);
        }

        parent::__construct();
    }

    public function getSiteAliases() 
    {
        return $this->laragoonSiteAliasesManager->getAliases();
    }
    
    public function commandIsReady()
    {
        if(! $this->laragoonSiteAliasesManager) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function handleCommandIsNotReady()
    {
        $this->error($this->signature . ": The lagoon subsystem is not configured. Perhaps you need to create a .lagoon.yml?");
        return self::RETURN_FAIL;
    }
}
