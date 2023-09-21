<?php

namespace MerapiPanel\Module\FileManager\Controller;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    public function register($router)
    {


        $route = $router->get("/filemanager", "index", self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 3,
            'name' => "File Manager",
            'icon' => 'fa-regular fa-folder',
            'link' => $route->getPath()
        ]);
    }


    public function index($view)
    {
        return $view->render("index.html.twig");
    }
}
