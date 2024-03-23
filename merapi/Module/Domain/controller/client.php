<?php
namespace MerapiPanel\Module\Domain\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Views\View;

class Client
{

    function register($router)
    {

        $index = $router->get("/domain", "index");
        Box::module("panel")->addMenu([
            "name" => "Domain",
            "icon" => "<i class=\"fa-solid fa-globe\"></i>",
            "link" => $index,
        ]);
    }


    function index()
    {
        return View::render("index.html.twig");
    }


}
