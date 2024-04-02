<?php
namespace MerapiPanel\Module\Setting\Controller;


use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Proxy;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;


class Admin extends __Fragment
{

    protected $module;
    function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }




    public function register()
    {

        Router::POST("/settings/general", "updateSetting", self::class);

        $setting = Router::GET("/settings", "index", self::class);
        $general = Router::GET("/settings/general", "index", self::class);
        $route = Router::GET("/settings/route", "route", self::class);

        Box::module("Panel")->addMenu([
            'order' => 100,
            'name' => "Settings",
            'icon' => 'fa-solid fa-gear',
            'link' => $setting->getPath(),
            "children" => [
                [
                    'order' => 0,
                    'name' => "General",
                    'icon' => 'fa-solid fa-circle-info',
                    'link' => $general->getPath()
                ],
                [
                    'order' => 0,
                    'name' => "Route",
                    'icon' => '<i class="fa-solid fa-route"></i>',
                    'link' => $route
                ]
            ]
        ]);
    }



    function index($view)
    {

        return View::render('index.html.twig');
    }




    function event($request)
    {
        $events = Proxy::fromObject(Box::get($this)->event())->getProperty('listeners');

        return View::render('event.html.twig', [
            "events" => array_map(fn($key) => [
                "instance" => $key,
                "listeners" => array_map(fn($listener) => (is_array($listener) ? $listener[0] . "@" . $listener[1] : $listener), $events[$key])
            ], array_keys($events))
        ]);
    }

    function updateSetting($request)
    {

        $setting = $this->module->__getSettings();
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
