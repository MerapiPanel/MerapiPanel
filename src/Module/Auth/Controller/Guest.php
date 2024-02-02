<?php

namespace MerapiPanel\Module\Auth\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;
use MerapiPanel\Module\Auth\Middleware\Login;

class Guest extends Module {

    protected $box;
    function setBox(Box $box)
    {

        $this->box = $box;
    }


    public function register($router)
    {

        // $router->get("/login",  "index", self::class);
        ($router->get("/login",  "index", self::class))->addMiddleware(new Login());
        ($router->post("/login",  "login", self::class))->addMiddleware(new Login());
    }


    public function index($entity)
    {

        return View::render("login.html.twig");
    }


    public function login()
    {

        return [
            "status" => 203,
            "response" => "success"
        ];
    }
}
