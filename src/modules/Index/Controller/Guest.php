<?php

namespace il4mb\Mpanel\Modules\Index\Controller;

use il4mb\Mpanel\Core\Box;

class Guest
{
    
    private $box;
    
    public function setBox(Box $box)
    {
        $this->box = $box;

        if(!is_object($this->box->core_http_router()))
        {
            throw new \Exception("Error: core_http_router not found");
        }

        $box->core_http_router()->get("/", self::class . "@index");
        
    }


    public function index()
    {

        // throw new \Exception("Error: index not found");
        $this->box->get_service("template")->render("index.html.twig");

        $this->box->template()->render("content.html.twig");
    }
}