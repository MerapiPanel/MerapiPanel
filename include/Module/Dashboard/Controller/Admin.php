<?php

namespace MerapiPanel\Module\Dashboard\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;
use Symfony\Component\Filesystem\Path;

class Admin extends __Fragment
{

    public function onCreate(Box\Module\Entity\Module $module)
    {

    }
    public function register()
    {

        Router::GET("/widget/edit", "widgetEdit", self::class);
        Router::GET("/widget/load/{name}", "widgetLoadComponent", self::class);
        Router::GET("/widget/load", "widgetLoad", self::class);
        Router::POST("/widget/save", "widgetSave", self::class);

        $route = Router::GET("/", "index", self::class);

        Box::module("Panel")->addMenu([
            'order' => 0,
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-house',
            'link' => $route
        ]);
    }


    public function index($view)
    {
        return View::render("index.html.twig");
    }



    function widgetEdit(Request $req)
    {

        $widgets = [];
        foreach (glob(Path::join($_ENV['__MP_APP__'], "Module", "**", "Widgets", "index.php")) as $file) {
            /**
             * @var array $data
             */
            $data = require $file;
            $widgets = array_merge($widgets, $data);
        }
        return [
            "code" => 200,
            "message" => "success",
            "data" => $widgets
        ];
    }


    function widgetFetch(Request $req)
    {

        $widgets = Box::module("Dashboard")->Widget->getDefindedWidgets();
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
            "data" => is_string($data) ? json_decode($data, true) : $data
        ];
    }


    function widgetLoadComponent(Request $req)
    {

        return Box::module("Dashboard")->Widget->renderWidget($req->name());
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
