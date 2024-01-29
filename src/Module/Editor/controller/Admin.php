<?php

namespace MerapiPanel\Module\Editor\Controller;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    function register($router)
    {

        $router->get("/editor", "index", self::class);
        $router->get("/editor/load-component", "loadComponent", self::class);
    }


    function index($view, $request)
    {
        
        return $view->render("index.html.twig", []);
    }


    function loadComponent($view, $request)
    {

        $stack  = [];
        $result = $this->getBox()->getEvent()->notify("module:editor:loadcomponent", $stack);
        
        return [
            "code" => 200,
            "message" => "Ok",
            "data" => $result
        ];
    }
}
