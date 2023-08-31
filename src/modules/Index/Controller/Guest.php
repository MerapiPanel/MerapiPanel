<?php

namespace il4mb\Mpanel\Modules;

use il4mb\Mpanel\Core\AppBox;

class Controller
{
    
    private $box;
    
    public function init(AppBox $box)
    {
        $this->box = $box;
        
    }


    public function index()
    {
       // return $this->render('index');
    }
}