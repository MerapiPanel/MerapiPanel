<?php

namespace MerapiPanel\Module\Dashboard\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;
use Symfony\Component\Filesystem\Path;

class Admin extends __Fragment
{

    protected $module;

    public function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }
    public function register()
    {

        // Router::GET("/widget/edit", "widgetEdit", self::class);
        Router::GET("/widget/load/{name}", "widgetLoadComponent", self::class);
        // Router::GET("/widget/load", "widgetLoad", self::class);
        // Router::POST("/widget/save", "widgetSave", self::class);

        $route = Router::GET("/", "index", self::class);

        Box::module("Panel")->addMenu([
            'order' => 0,
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-house',
            'link' => $route
        ]);
    }


    public function index($view)
    {
        return View::render("index.html.twig");
    }


    function widgetLoadComponent(Request $request)
    {
        $name = $request->name();
        if (empty($name)) {
            return false;
        }
        return Box::module("Dashboard")->Widget->renderWidget($name);
    }
}
