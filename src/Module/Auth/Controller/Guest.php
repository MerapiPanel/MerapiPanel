<?php

namespace Mp\Module\Auth\Controller;

use Mp\Box;
use Mp\Core\Abstract\Module;

class Guest extends Module {

    protected $box;
    function setBox(Box $box)
    {

        $this->box = $box;
    }


    public function register($router)
    {

        $this->service()->hallo();

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
