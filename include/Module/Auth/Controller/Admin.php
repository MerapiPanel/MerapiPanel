<?php

namespace MerapiPanel\Module\Auth\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Http\Request;
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
        $route = Router::GET("/settings/auth", "index", self::class);

        Box::module("Panel")->addMenu([
            "name" => "Auth",
            "parent" => "settings",
            "icon" => "fa fa-lock",
            "link" => $route->getPath()
        ]);
    }


    function index($view)
    {

        return View::render("index.html.twig");
    }

    function UpdateSetting(Request $data)
    {

        $settings = $this->module->getSetting();

        $body = $data->getRequestBody();

        if (isset($body['session_name'])) {
            $settings['session_name'] = $body['session_name'];
        }
        if (isset($body['session_expired'])) {
            $settings['session_expired'] = $body['session_expired'];
        }

        return [
            "message" => "Settings updated successfully",
            'code' => 200
        ];
    }
}
