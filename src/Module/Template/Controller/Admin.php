<?php

namespace MerapiPanel\Module\Template\Controller;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    public function register($router)
    {


        $route = $router->get("/template", "index", self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 3,
            "parent" => "",
            'name' => "Template",
            'icon' => 'fa-solid fa-brush',
            'link' => $route->getPath()
        ]);
    }


    public function index($view)
    {
        return $view->render("index.html.twig");
    }
}