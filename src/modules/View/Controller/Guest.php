<?php

namespace Mp\Modules\View\Controller;

use Mp\Core\Box;
use Mp\Core\Utilities\Router;

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

        $router->get("/view", self::class . "@index");
    }

    public function index(\Mp\Core\View\Entity $entity)
    {

        return $entity->render("/content.html.twig");
    }

}
