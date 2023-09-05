<?php

namespace il4mb\Mpanel\Modules\Index\Controller;

use il4mb\Mpanel\Core\Box;
use il4mb\Mpanel\Core\Http\Router;
use il4mb\Mpanel\Core\Mod\Interface\Box_Controller;

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

        $this->box->view()->render("content.html.twig");
    }
}