<?php
namespace MerapiPanel\Module\Email\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Views\View;

class Client
{

    function register($router)
    {

        $index = $router->get("/email", "index");

        Box::module("panel")->addMenu([
            "order" => 3,
            "name" => "Email",
            "icon" => "<i class=\"fa-regular fa-envelope\"></i>",
            "link" => "$index",
        ]);
    }


    function index()
    {

        return View::render("index.html.twig");
    }
}
