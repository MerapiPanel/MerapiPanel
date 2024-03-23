<?php

namespace MerapiPanel\Module\Setting\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\Proxy;
use MerapiPanel\Core\Views\View;

class Admin extends Module
{

    public function register($router)
    {

        $router->post("/settings/general", "updateSetting", self::class);

        $setting = $router->get("/settings", "index", self::class);
        $general = $router->get("/settings/general", "index", self::class);
        $route = $router->get("/settings/route", "route", self::class);
        $event = $router->get("/settings/event", "event", self::class);

        Box::module("panel")->addMenu([
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
                ],
                [
                    'order' => 0,
                    'name' => "Event",
                    'icon' => 'fa-solid fa-bell',
                    'link' => $event
                ]
            ]
        ]);
    }

    function index($view)
    {

        return View::render('index.html.twig');
    }


    function route($request)
    {
        $statck = Box::get($this)->utility_router()->getProperty('routeStack');

        return View::render('route.html.twig', [
            "route_stack" => array_map(

                fn($key) => [
                    "name" => $key,
                    "routes" => array_map(

                        fn($route) => [
                            "path" => preg_replace("/\{.*\}/", "<span class='text-primary'>$0</span>", $route->__toString()),
                            "method" => $route->getMethod(),
                            "controller" => is_string($route->getController()) ? preg_replace("/\@.*/", "<b>$0</b>", $route->getController()) : "{Closure}",
                        ],
                        $statck[$key]
                    )
                ],
                array_keys($statck)
            )
        ]);
    }


    function event($request)
    {
        $events = Proxy::fromObject(Box::get($this)->event())->getProperty('listeners');

        return View::render('event.html.twig', [
            "events" => array_map(fn($key) => [
                "instance" => $key,
                "listeners" => array_map(fn($listener) => (is_array($listener) ? $listener[0]."@".$listener[1] : $listener), $events[$key])
            ], array_keys($events))
        ]);
    }

    function updateSetting($request)
    {

        $setting = $this->service()->__getSettings();
        $_BODY = $request->getRequestBody();

        if (!isset ($_BODY['website_name']) || empty ($_BODY['website_name'])) {
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
