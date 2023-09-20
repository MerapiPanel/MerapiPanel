<?php

namespace MerapiQu\Module\Panel\Controller;

use MerapiQu\Core\Abstract\Module;

class Admin extends Module
{

    protected $box;

    public function setBox($box)
    {
        $this->box = $box;
    }


    public function register($router)
    {

        $router->get('/', "index", self::class);
    }

    function index($viewEn)
    {

        $this->box->module_user();

        return $viewEn->render("base.html.twig");
    }
}
