<?php 

namespace Mp\Module\Dasboard\Controller;

use Mp\Core\Abstract\Module;

class Admin extends Module {


    public function register($router) {

        $router->get('/dasboard', self::class . "@index");
    }

    function index($viewEn) {
        
        return $viewEn->render("index.html.twig");
    }
}