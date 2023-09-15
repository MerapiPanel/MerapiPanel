<?php

namespace Mp\Modules\Auth\Controller;

use Mp\Core\Box;
use Mp\Core\Utilities\Router;

class  Guest {

    protected $box;
    function setBox(Box $box) {

        $this->box = $box;
    }


    public function register(Router $router) {

        $router->get("/login", self::class . "@index");
    }


    public function index(\Mp\Core\View\Entity $entity) {
        
        return $entity->render("login.html.twig");
    }

}