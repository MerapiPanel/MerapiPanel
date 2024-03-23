<?php
namespace MerapiPanel\Module\Setting\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Proxy;
use MerapiPanel\Core\Views\View;

class Client
{

    function register($router)
    {
        $index = $router->get("/settings", "index", self::class);
        $route = $router->get("/settings/route", "route", self::class);
        $event = $router->get("/settings/event", "event", self::class);

        Box::module("panel")->addMenu([
            'order' => 100,
            'name' => "Settings",
            'icon' => 'fa-solid fa-gear',
            'link' => $index->getPath(),
            "children" => [
                [
                    'order' => 0,
                    'name' => "Route",
                    'icon' => '<i class="fa-solid fa-route"></i>',
                    'link' => $route
                ], 
                [
                    'order' => 1,
                    'name' => "Event",
                    'icon' => '<i class="fa-solid fa-bell"></i>',
                    'link' => $event
                ]
            ]
        ]);
    }


    function index($request)
    {

        return View::render('index.html.twig');
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
}