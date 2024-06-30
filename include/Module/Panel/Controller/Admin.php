<?php

namespace MerapiPanel\Module\Panel\Controller;

use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Utility\Router;
use MerapiPanel\Views\View;

class Admin extends __Controller
{

    public function register()
    {

        Router::GET("/", [$this, 'index']);
    }

    function index()
    {
        return View::render("base.html.twig");
    }
}
