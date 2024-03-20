<?php
namespace MerapiPanel\Module\Theme\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\View\View;
use MerapiPanel\Utility\Router;

class admin
{

    public function register(Router $router)
    {
        $index = $router->get('/theme', "index");
        $customize = $router->get('/theme/customize', "customize");

        Box::module("panel")->addMenu([
            "name" => "Theme",
            "link" => $index,
            "order" => 2,
            "icon" => file_get_contents(__DIR__ . "/../icon.svg"),
            "childs" => [
                [
                    "name" => "Customize",
                    "link" => $customize,
                    "order" => 1
                ]
            ]
        ]);
    }

    public function index()
    {

        return View::render("index.html.twig");
    }

    function customize()
    {
        return View::render("customize.html.twig");
    }
}