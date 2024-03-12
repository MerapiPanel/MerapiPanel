<?php

namespace MerapiPanel\Module\Editor\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\Component\ProcessingComponent;
use MerapiPanel\Core\View\Component\ViewComponent;
use MerapiPanel\Core\View\View;
use MerapiPanel\Utility\Http\Request;
use Twig\Loader\ArrayLoader;

class Admin extends Module
{

    function register($router)
    {

        $router->get("/editor", "index", self::class);
        $router->get("/editor/load-component", "loadComponent", self::class);
        $router->get("/template/component/fetch", "componentFetch", self::class);
        $router->post("/template/component/fetch/render/{addr}", "componentRender", self::class);
    }




    public function componentFetch(Request $req)
    {

        $root       = realpath(__DIR__ . "/../../") . "\\**\\Views\\component.php";
        $components = [];

        foreach (glob($root) as $file) {

            $className   = "MerapiPanel\Module\{**}\Views\Component";
            $file        = str_replace("\\", "/", $file);
            $module_name = Module::getModuleName($file);
            $className   = str_replace("{**}", $module_name, $className);

            if (class_exists($className)) {

                $component     = ViewComponent::from($className);
                $components[] = [
                    "name" => $module_name,
                    "components" => $component->getComponents()
                ];
            }
        }


        return [
            "code"    => 200,
            "message" => "Ok",
            "data"    => $components
        ];
    }





    public function componentRender(Request $req)
    {

        $addr = rawurldecode($req->addr());

        ob_start();

        $body = $req->getRequestBody();

        $attr = implode(" ", array_map(function ($k, $v) {
            return "{$k}=\"{$v}\"";
        }, array_keys($body), $body));

        $loader = new ArrayLoader([
            "template" => "<$addr {$attr}></$addr>"
        ]);
        ob_end_clean();


        return [
            "code"    => 200,
            "message" => "Ok",
            "data"    => [
                'output' => View::newInstance($loader)->load("template", [])->render([]),
            ]
        ];
    }



    function index($request)
    {

        return View::render("index.html.twig", []);
    }


    function loadComponent($request)
    {

        $stack  = [];
        $result = $this->getBox()->getEvent()->notify("module:editor:loadcomponent", $stack);

        return [
            "code" => 200,
            "message" => "Ok",
            "data" => $result
        ];
    }
}
