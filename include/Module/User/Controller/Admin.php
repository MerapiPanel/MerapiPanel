<?php

namespace MerapiPanel\Module\User\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;


class Admin extends __Fragment
{


    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }



    public function register()
    {

        $index = Router::GET("/users", "index", self::class);

        $panel = Box::module("Panel");
        $site = Box::module("Site");

        $panel->addMenu([
            "name" => "Users",
            "link" => $index,
            'icon' => 'fa-solid fa-user'
        ]);
    }

    public function index($req)
    {

        return View::render("index.html.twig");
    }
}
