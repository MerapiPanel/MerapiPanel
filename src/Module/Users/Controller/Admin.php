<?php

namespace MerapiPanel\Module\Users\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;
use MerapiPanel\Module\Users\Custom\UsersFunction;

class Admin extends Module
{


    public function register($router)
    {

        $router->get("/users", "index", self::class);

        $panel = $this->getBox()->Module_Panel();
        $site = $this->getBox()->Module_Site();
        $panel->addMenu([
            "name" => "Users",
            "link" => $site->adminLink("/users"),
            'icon' => 'fa-solid fa-user'
        ]);
    }

    public function index($req)
    {
        
        View::AddExtension(new UsersFunction());
        return View::render("index.html.twig");
    }
}
