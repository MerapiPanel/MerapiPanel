<?php

namespace MerapiPanel\Module\FileManager\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Http\Request;

class Admin extends Module
{

    public function register($router)
    {

        $router->post("/filemanager/upload", "upload", self::class);
        $route = $router->get("/filemanager", "index", self::class);
        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 3,
            'name' => "File Manager",
            'icon' => 'fa-regular fa-folder',
            'link' => $route->getPath()
        ]);
    }


    public function upload(Request $request) {

    }

    public function index($view)
    {
        return [
            "data" => "File Manager",
        ];
        return $view->render("index.html.twig");
    }
}
