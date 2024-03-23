<?php
namespace MerapiPanel\Module\FileManager\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Views\View;

class Client
{

    public function register($router)
    {
        $index = $router->get("/filemanager", "index", self::class);
        Box::module("panel")->addMenu([
            "order" => 2,
            "name" => "File Manager",
            "link" => $index->getPath(),
            "icon" => "fa-regular fa-folder"
        ]);
    }

    public function index()
    {
        return View::render("index.html.twig");
    }
}