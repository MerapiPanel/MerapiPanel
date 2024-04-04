<?php
namespace MerapiPanel\Module\Domain\Controller;

use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Client extends __Controller
{

    function register()
    {
        Router::GET("/", "index", self::class);
        // register other route
    }
    function index()
    {
        return View::render("index.html.twig");
    }
}
