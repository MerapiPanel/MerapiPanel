<?php

namespace MerapiPanel\Module\ViewEngine\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Module\ViewEngine\Abstract\ViewComponent;
use MerapiPanel\Module\ViewEngine\Zone;
use MerapiPanel\Utility\Http\Request;
use Twig\Loader\ArrayLoader;

class Admin extends Module
{

    function register($router)
    {

        $router->get("view-engine/components", "index", self::class);
        $router->post("view-engine/components/static/{id}", "staticRender", self::class);
    }


    public function index(Request $request)
    {

        $root       = realpath(__DIR__ . "/../../") . "\**\Views\Component.php";
        $components = [];

        foreach (glob($root) as $file) {

            $className  = "MerapiPanel\Module\{**}\Views\Component";

            $file = str_replace("\\", "/", $file);
            $file = trim(str_replace(str_replace("\\", "/", realpath(__DIR__ . "/../../")), "", $file), "/");
            [$module_name] = explode("/", $file);
            $className     = str_replace("{**}", $module_name, $className);

            if (class_exists($className)) {

                $instance     = new $className();
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



    function staticRender(Request $request)
    {
        ob_start();

        $id   = $request->getParam("id");
        $body = $request->getRequestBody();

        $params = json_encode($body);

        $loader = new ArrayLoader([
            "template" => "{{ guest.$id($params, 1) | raw }}"
        ]);

        // Initialize the Twig environment
        $twig = new \Twig\Environment($loader, []);

        $guest = new Zone("guest");
        $guest->setBox($this->getBox());

        $twig->addGlobal("guest", $guest);
        $htmlString = $twig->render('template', []);

        $string =  ob_get_clean();


        return [
            "code"    => 200,
            "message" => "Ok",
            "data"    => [
                "string" => $string,
                'output' => $htmlString
            ]
        ];
    }
}
