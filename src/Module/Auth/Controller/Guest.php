<?php

namespace Mp\Module\Auth\Controller;

use Mp\Box;
use Mp\Module\Utility\Router;
use Mp\View\Entity;

class  Guest
{

    protected $box;
    function setBox(Box $box)
    {

        $this->box = $box;
    }


    public function register($router)
    {

        $router->get("/login", self::class . "@index");
        $router->post("/login", self::class . "@login");
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
