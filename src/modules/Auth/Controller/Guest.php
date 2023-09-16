<?php

namespace Mp\Modules\Auth\Controller;

use Mp\Box;
use Mp\Core\Utilities\Http\Response;
use Mp\Core\Utilities\Router;
use Mp\Core\View\Entity;

class  Guest
{

    protected $box;
    function setBox(Box $box)
    {

        $this->box = $box;
    }


    public function register(Router $router)
    {

        $router->get("/login", self::class . "@index");
        $router->post("/login", self::class . "@login");
    }


    public function index(Entity $entity)
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
