<?php

namespace MerapiPanel\Module\User\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\Views\View;
use MerapiPanel\Module\User\Custom\UsersFunction;

class Admin extends Module
{


    public function register($router)
    {

        $index = $router->get("/users", "index", self::class);

        $panel = $this->getBox()->Module_Panel();
        $site = $this->getBox()->Module_Site();
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
