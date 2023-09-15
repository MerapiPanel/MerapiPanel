<?php

namespace Mp\Modules\Api\Controller;

use Mp\Core\Utilities\Router;

class Guest {

    function register(Router $router) {

        $router->post("/app_api", self::class . "@index");
    }

    function index(\Mp\Core\View\Entity $entity) {
         
    }
}
