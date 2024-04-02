<?php
namespace MerapiPanel\Module\Domain\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

 class Client extends __Controller
{

    function register()
    {

        $index = Router::GET("/domain", "index");
        Box::module("Panel")->addMenu([
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
