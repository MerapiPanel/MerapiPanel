<?php

namespace MerapiPanel\Module\Settings\Controller;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    public function register($router)
    {

        $setting =  $router->get("/settings", "index", self::class);
        $general = $router->get("/settings/general", "index", self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 100,
            'name' => "Settings",
            'icon' => 'fa-solid fa-gear',
            'link' => $setting->getPath()
        ]);
        $panel->addMenu([
            'order' => 0,
            'parent' => "Settings",
            'name' => "General",
            'icon' => 'fa-solid fa-circle-info',
            'link' => $general->getPath()
        ]);
    }

    function index($view)
    {

        return $view->render('index.html.twig');
    }
}
