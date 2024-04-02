<?php
namespace MerapiPanel\Module\VpsManager\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Core\Views\View;
use MerapiPanel\Utility\Router;

class Client extends __Controller
{

    function register()
    {

        $index = Router::GET('/vps-manager', "index");
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
