<?php

namespace MerapiPanel\Module\Dashboard\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\Views\View;
use MerapiPanel\Utility\Http\Request;

class Admin extends Module
{

    public function register($router)
    {

        $router->get("/widget/fetch", "widgetFetch", self::class);
        $router->get("/widget/load/{name}", "widgetLoadComponent", self::class);
        $router->get("/widget/load", "widgetLoad", self::class);
        $router->post("/widget/save", "widgetSave", self::class);

        $route = $router->get("/", "index", self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 0,
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-house',
            'link' => $route->getPath()
        ]);
    }


    public function index($view)
    {
        return View::render("index.html.twig");
    }


    function widgetFetch(Request $req)
    {

        $widgets = Box::module("Dashboard")->service("Widget")->getDefindedWidgets();
        return [
            "code" => 200,
            "message" => "success",
            "data" => $widgets
        ];
    }


    public function widgetSave(Request $req)
    {
        if (!$req->data()) {
            return [
                "code" => 400,
                "message" => "fail",
                "data" => []
            ];
        }

        $data = $req->data();
        $file = __DIR__ . "/../widget.json";
        file_put_contents($file, is_string($data) ? $data : json_encode($data));

        return [
            "code" => 200,
            "message" => "success",
        ];
    }


    function widgetLoadComponent(Request $req)
    {

        return Box::module("Dashboard")->service("Widget")->renderWidget($req->name());
    }


    public function widgetLoad(Request $req)
    {

        $file = __DIR__ . "/../widget.json";
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }

        $wgetData = json_decode(file_get_contents($file), true);

        return [
            "code" => 200,
            "message" => "success",
            "data" => $wgetData
        ];
    }
}
