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

        Box::module("Panel")->addMenu([
            'order' => 0,
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-house',
            'link' => Router::GET("/dashboard", [$this, "index"])
        ]);
        Router::GET("/widget/load/{name}", [$this, "widgetLoadComponent"]);
    }


    public function index()
    {
        return View::render("admin/index");
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
