<?php

namespace MerapiPanel\Module\Auth\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Http\Request;

class Admin extends Module
{

    public function register($router)
    {


        $router->post("/settings/auth",  "UpdateSetting", self::class);
        $route = $router->get("/settings/auth",  "index", self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            "name" => "Auth",
            "parent" => "settings",
            "icon" => "fa fa-lock",
            "link" => $route->getPath()
        ]);
    }


    function index($view)
    {

        return $view->render("index.html.twig");
    }

    function UpdateSetting(Request $data)
    {

        $settings = $this->service()->getSetting();

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
