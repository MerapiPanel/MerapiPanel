<?php

namespace MerapiQu\Module\Auth\Controller;

use MerapiQu\Box;
use MerapiQu\Core\Abstract\Module;

class Guest extends Module {

    protected $box;
    function setBox(Box $box)
    {

        $this->box = $box;
    }


    public function register($router)
    {

        $router->get("/login",  "index", self::class);
        $router->post("/login",  "login", self::class);
    }


    public function index($entity)
    {

        return $entity->render("login.html.twig");
    }


    public function login()
    {

        return [
            "status" => 203,
            "response" => "success"
        ];
    }
}
