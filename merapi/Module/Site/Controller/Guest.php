<?php

namespace MerapiPanel\Module\Site\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\Views\View;

class Guest extends Module
{


    public function register($router)
    {

        $router->get("/", "index", self::class);
    }


    public function index($entity)
    {
        return View::render("base.html.twig");
    }
}
