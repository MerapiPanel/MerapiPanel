<?php 

namespace Mp\Module\Dashboard\Controller;

use Mp\Core\Abstract\Module;

class Admin extends Module {


    public function register($router) {

        $router->get('/', "index", self::class);

    }

    function index($viewEn) {
        
        return $viewEn->render("base.html.twig");
    }
}