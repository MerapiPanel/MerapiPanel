<?php

namespace MerapiPanel\Module\Auth\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;
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
        $logout = Router::GET("/auth/logout", "logout", self::class);

        Box::module("Panel")->addMenu([
            "name" => "Auth",
            "parent" => "settings",
            "icon" => "fa fa-lock",
            "link" => $route->getPath()
        ]);

        Box::module("Panel")->addMenu([
            "name" => "Logout",
            "icon" => '<i class="fa-solid fa-person-walking-arrow-right"></i>',
            "link" => $logout,
            "order" => 9999
        ]);
    }


    function logout(Request $req, Response $res)
    {
        $referer = $req->http("referer");
        if (empty($referer)) {
            $referer = $_ENV['__MP_' . strtoupper($_ENV["__MP_ACCESS__"]) . '__']['prefix'] . "/";
        }

        $setting = $this->module->getSetting();
        setcookie($setting->cookie_name, "", time() - 3600, "/");
        $res->setHeader("Location", $referer);
        return $res;
    }

    
    function setting($view)
    {


        $setting = $this->module->getSetting();

        return View::render("setting.html.twig", [
            "setting" => $setting
        ]);
    }
}
