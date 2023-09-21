<?php

namespace MerapiPanel\Module\Settings\Controller;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module {

    public function register($router)
    {

        $router->get("/settings", "index", self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 100,
            'name' => "Settings",
            'icon' => 'fa-solid fa-gear',
            'link' => $this->box->module_site()->adminLink('settings')
        ]);
    }

    function index($view) {

        return $view->render('index.html.twig');
    }
}

