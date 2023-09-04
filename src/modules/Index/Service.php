<?php

namespace il4mb\Mpanel\Modules\Index;

use il4mb\Mpanel\Core\BoxMod;
use il4mb\Mpanel\Core\Mod\ModProxy;

class Service 
{

    protected $box;

    function setBox($box)
    {
        $this->box = $box;
        
       echo $this->box->api_index();
    }
    
}
