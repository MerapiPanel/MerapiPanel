<?php
namespace MerapiPanel\Module\VpsManager\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Views\View;

class Client
{

    function register($router)
    {

        $index = $router->get('/vps-manager', "index");
        Box::module("panel")->addMenu([
            "name" => "VPS",
            "icon" => "fa-solid fa-server",
            "link" => $index
        ]);
    }


    function index()
    {
        return View::render("index.html.twig");
    }
}
