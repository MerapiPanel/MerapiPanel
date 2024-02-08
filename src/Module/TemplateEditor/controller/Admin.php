<?php

namespace MerapiPanel\Module\TemplateEditor\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\Section;
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

        $root       = realpath(__DIR__ . "/../../") . "\\**\\template\\component.php";
        $components = [];

        foreach (glob($root) as $file) {

            $className  = "MerapiPanel\Module\{**}\Template\Component";

            $file = str_replace("\\", "/", $file);
            $file = trim(str_replace(str_replace("\\", "/", realpath(__DIR__ . "/../../")), "", $file), "/");
            [$module_name] = explode("/", $file);
            $className     = str_replace("{**}", $module_name, $className);

            if (class_exists($className)) {

                $instance     = new $className();
                $components[] = [
                    "name" => $module_name,
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





    public function componentRender(Request $req)
    {

        [$comp, $module, $method] = explode(":", rawurldecode($req->addr()));
        $addr = implode("_", [$module, "component", $method]);

        ob_start();

        $body = $req->getRequestBody();
        $params = json_encode($body);

        $loader = new ArrayLoader([
            "template" => "{{ guest.$addr($params, {edit: true}) | raw }}"
        ]);

        // Initialize the Twig environment
        $twig = new \Twig\Environment($loader, []);

        $guest = new Section("guest");

        $twig->addGlobal("guest", $guest);
        $htmlString = $twig->render('template', []);
        ob_end_clean();


        return [
            "code"    => 200,
            "message" => "Ok",
            "data"    => [
                'output' => $htmlString
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
