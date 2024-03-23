<?php
namespace MerapiPanel\Module\Dashboard\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Views\View;

class Client extends \MerapiPanel\Core\Abstract\Module
{
    public function register($router)
    {

        $index = $router->get("/", "index", self::class);

        Box::module("panel")->addMenu([
            "order" => 0,
            "name" => "Dashboard",
            "link" => $index,
            "icon" => "fa-solid fa-house"
        ]);
    }

    public function index()
    {
        return View::render("index.html.twig");
    }
}