<?php

namespace MerapiPanel\Module\Site\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;

class Guest extends Module
{

    protected $box;

    public function setBox(Box $box)
    {
        $this->box = $box;
    }


    public function getBox(): ?Box
    {
        return $this->box;
    }


    public function register($router)
    {

        $router->get("/", "index", self::class);
    }


    public function index($entity)
    {
        return View::render("base.html.twig");
    }
}
