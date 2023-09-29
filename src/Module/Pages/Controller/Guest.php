<?php

namespace MerapiPanel\Module\Pages\Controller;

use MerapiPanel\Core\Abstract\Module;

class Guest extends Module {

    public function register($router)
    {

        $router->get("/page/{slug}/", "loadPage", self::class);
    }


    function loadPage($view, $request) {


        return $view->render("page.html.twig");
    }
}