<?php

namespace MerapiPanel\Module\Auth\Controller;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    public function register($router)
    {


        $route = $router->get("/settings/auth",  "index", self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            "name" => "Auth",
            "parent" => "settings",
            "icon" => "fa fa-lock",
            "link" => $route->getPath()
        ]);
    }


    function index($view)
    {

        return $view->render("index.html.twig");
    }
}
