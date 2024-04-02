<?php
namespace MerapiPanel\Module\Dashboard\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Client extends __Fragment
{

    protected $module;

    function onCreate(Box\Module\Entity\Module $module) {
        $this->module = $module;
    }

    
    public function register()
    {

        $index = Router::GET("/", "index", self::class);

        Box::module("Panel")->addMenu([
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