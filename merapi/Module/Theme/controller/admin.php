<?php
namespace MerapiPanel\Module\Theme\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\View\View;
use MerapiPanel\Utility\Router;

class admin
{

    public function register(Router $router)
    {
        $index = $router->get('/admin/theme', "index");
        Box::module("panel")->addMenu([
            "name" => "Theme",
            "link" => $index,
            "order" => 2,
            "icon" => file_get_contents(__DIR__ . "/../icon.svg"),
        ]);
    }

    public function index()
    {

        return View::render("index.html.twig");
    }
}