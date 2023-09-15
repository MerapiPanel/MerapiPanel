<?php

namespace Mp\Modules\Index\Controller;

use Mp\Core\Box;
use Mp\Core\Utilities\Router;
use Mp\Core\Mod\Interface\Box_Controller;

class Guest
{
    
    private $box;
    
    public function setBox(Box $box)
    {
        $this->box = $box;
    }

    public function getBox(): ?Box
    {
        return $this->box;
    }

    public function register(Router $router)
    {

        $router->get("/", self::class . "@index");
    }


    public function index()
    {

        // throw new \Exception("Error: index not found");
        //$this->box->get_service("template")->render("index.html.twig");

        
    }
}