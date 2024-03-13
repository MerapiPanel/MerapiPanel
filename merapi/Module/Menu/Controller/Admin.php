<?php

namespace MerapiPanel\Module\Menu\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;

class Admin extends Module
{

    public function register($router)
    {


        $route = $router->get("/menu", "index", self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 3,
            "parent" => "",
            'name' => "Menu",
            'icon' => 'fa-solid fa-bars-staggered',
            'link' => $route->getPath()
        ]);
    }


    public function index($view)
    {
        return View::render("index.html.twig");
    }
}