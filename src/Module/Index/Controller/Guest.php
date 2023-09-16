<?php

namespace Mp\Module\Index\Controller;

use Mp\Box;
use Mp\Utility\Router;

class Guest
{

    private $box;

    public function setBox(Box $box)
    {
        $this->box = $box;
    }


    public function getBox(): ?Box
    {
        return $this->box;
    }


    public function register(Router $router)
    {

       $router->get("/", self::class . "@index");
    }


    public function index(\Mp\View\Entity $entity)
    {

        return $entity->render("/content.html.twig");
    }
}
