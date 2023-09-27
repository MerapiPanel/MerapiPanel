<?php
namespace MerapiPanel\Module\Editor\Controller;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module {

    function register($router)
    {
        $router->get("/editor", "index", self::class);
    }

    function index($view, $request) 
    {

        return $view->render("index.html.twig", []);
    }
}