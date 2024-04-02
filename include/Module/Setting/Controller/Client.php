<?php
namespace MerapiPanel\Module\Setting\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Core\Proxy;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Client extends __Controller
{

    function register()
    {
        $index = Router::GET("/settings", "index", self::class);
        $route = Router::GET("/settings/route", "route", self::class);
        $event = Router::GET("/settings/event", "event", self::class);

        Box::module("Panel")->addMenu([
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
                ]
            ]
        ]);
    }


    function index($request)
    {

        return View::render('index.html.twig');
    }


    function route($request)
    {
        $statck = Proxy::fromObject(Router::getInstance())->getProperty('routeStack');

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