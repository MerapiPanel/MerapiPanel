<?php

namespace MerapiPanel\Module\Menu\Controller;

use MerapiPanel\Core\Abstract\Module;

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
            'icon' => 'fa-regular fa-folder',
            'link' => $route->getPath()
        ]);
    }


    public function index($view)
    {
        return $view->render("index.html.twig");
    }
}