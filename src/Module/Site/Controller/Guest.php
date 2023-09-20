<?php

namespace MerapiQu\Module\Site\Controller;

use MerapiQu\Box;
use MerapiQu\Core\Abstract\Module;

class Guest extends Module
{

    protected $box;

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

        $router->get("/", "index", self::class);
    }


    public function index($entity)
    {

        $db = $this->getDatabase();

        return $entity->render("/content.html.twig");
    }
}
