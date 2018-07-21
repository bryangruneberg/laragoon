<?php 

namespace Bryangruneberg\Laragoon;

use \Illuminate\Support\Facades\Facade;

class LaragoonFacade extends Facade {
    protected static function getFacadeAccessor() {
        return LaragoonService::class;
    }
}
