<?php

namespace MerapiPanel\Module\Auth\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Admin extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function register()
    {


        Router::POST("/settings/auth", "UpdateSetting", self::class);
        $route = Router::GET("/settings/auth", "setting", self::class);

        Box::module("Panel")->addMenu([
            "name" => "Auth",
            "parent" => "settings",
            "icon" => "fa fa-lock",
            "link" => $route->getPath()
        ]);
    }


    function setting($view)
    {


        $setting = $this->module->getSetting();

        return View::render("setting.html.twig", [
            "setting" => $setting
        ]);
    }
}
