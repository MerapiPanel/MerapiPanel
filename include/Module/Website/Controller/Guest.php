<?php

namespace MerapiPanel\Module\Website\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Router;

class Guest extends __Fragment
{

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {

    }


    public function register()
    {

        Router::GET("/", "index", self::class);

    }

    function index() {

        return View::render("index.html");
    }


}
