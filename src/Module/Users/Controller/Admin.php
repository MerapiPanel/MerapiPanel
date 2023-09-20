<?php

namespace MerapiQu\Module\Users\Controller;

use MerapiQu\Core\Abstract\Module;

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

    public function index($view)
    {
        return $view->render("index.html.twig");
    }
}
