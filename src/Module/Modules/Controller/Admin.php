<?php

namespace MerapiPanel\Module\Modules\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;

class Admin extends Module
{

    public function register($router)
    {

        $router->get("/modules", "index", self::class);

        $panel = $this->getBox()->Module_Panel();
        $site = $this->getBox()->Module_Site();
        $panel->addMenu([
            "parent" => "settings",
            "name" => "Modules",
            "link" => $site->adminLink("/modules"),
            'icon' => 'fa-solid fa-cube'
        ]);
    }

    public function index($view)
    {

        return View::render("index.html.twig");
    }
}
