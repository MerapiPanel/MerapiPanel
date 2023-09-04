<?php

namespace il4mb\Mpanel\Modules\Index;

use il4mb\Mpanel\Core\BoxMod;
use il4mb\Mpanel\Core\Mod\ModProxy;

class Service 
{

    protected $box;

    public function __construct()
    {
        print_r("Hello");
    }

    function setBox($box)
    {
        $this->box = $box;
        
        $this->box->api_index();
    }
    
}
