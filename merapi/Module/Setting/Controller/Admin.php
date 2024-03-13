<?php

namespace MerapiPanel\Module\Setting\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;

class Admin extends Module
{

    public function register($router)
    {

        $router->post("/settings/general", "updateSetting", self::class);

        $setting =  $router->get("/settings", "index", self::class);
        $general = $router->get("/settings/general", "index", self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 100,
            'name' => "Settings",
            'icon' => 'fa-solid fa-gear',
            'link' => $setting->getPath()
        ]);
        $panel->addMenu([
            'order' => 0,
            'parent' => "Settings",
            'name' => "General",
            'icon' => 'fa-solid fa-circle-info',
            'link' => $general->getPath()
        ]);
    }

    function index($view)
    {

        return View::render('index.html.twig');
    }

    function updateSetting($request)
    {

        $setting = $this->service()->__getSettings();
        $_BODY = $request->getRequestBody();

        if (!isset($_BODY['website_name']) || empty($_BODY['website_name'])) {
            return [
                "code" => 400,
                "message" => "Website name is required",
            ];
        }

        $setting['website_name'] = $_BODY['website_name'];
        $setting['website_email'] = $_BODY['website_email'] ?? "";
        $setting['website_timezone'] = $_BODY['website_timezone'] ?? "";
        $setting['website_date_format'] = $_BODY['website_date_format'] ?? "";

        return [
            "code" => 200,
            "message" => "Settings updated successfully",
        ];
    }
}
