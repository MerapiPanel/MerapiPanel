<?php

namespace Mp\Module\Index\Controller;

use Mp\Box;

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


    public function register($router)
    {

        $router->get("/", self::class . "@index");
    }


    public function index($entity)
    {

        return $entity->render("/content.html.twig");
    }
}
