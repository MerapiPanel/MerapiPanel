<?php

namespace MerapiPanel\Module\ViewEngine\Controller;

use MerapiPanel\Module\ViewEngine\Abstract\ViewComponent;
use MerapiPanel\Utility\Http\Request;

class Admin
{

    function register($router)
    {

        $router->get("view-engine/components", "index", self::class);
        $router->post("view-engine/components/static/{id}", "staticRender", self::class);
    }


    public function index($view, $request)
    {

        $root       = realpath(__DIR__ . "/../../") . "\**\View\Component.php";
        $components = [];

        foreach (glob($root) as $file) {

            $className  = "MerapiPanel\Module\{**}\View\Component";

            $file = str_replace("\\", "/", $file);
            $file = trim(str_replace(str_replace("\\", "/", realpath(__DIR__ . "/../../")), "", $file), "/");
            [$module_name] = explode("/", $file);
            $className     = str_replace("{**}", $module_name, $className);

            if (class_exists($className)) {

                $instance     = new $className(ViewComponent::COMPONENTMODE_EDIT);
                $components[] = [
                    "name" => "Module " . $module_name,
                    "components" => $instance->getAvailableMethods()
                ];
            }
        }


        return [
            "code"    => 200,
            "message" => "Ok",
            "data"    => $components
        ];
    }


    function staticRender(Request $request = null)
    {

        
        $id = $request->getParam("id");
        $body = $request->getRequestBody();


        return [
            "code"    => 200,
            "message" => "Ok",
            "data"    => [
                "id" => $id,
                "body" => $body
            ]
        ];
    }
}
