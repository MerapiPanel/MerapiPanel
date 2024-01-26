<?php

namespace MerapiPanel\Module\Dashboard\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;

class Admin extends Module
{

    public function register($router)
    {


        $route = $router->get("/", "index", self::class);
        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 0,
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-house',
            'link' => $route->getPath()
        ]);
    }


    public function index($view)
    {
        return View::render("index.html.twig");
    }
}
