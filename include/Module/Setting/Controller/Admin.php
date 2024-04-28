<?php
namespace MerapiPanel\Module\Setting\Controller;


use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Proxy;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Http\Request;
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
        Router::POST("/setting/endpoint/save", "saveEndpoint", self::class);
        Router::GET("/settings/config/{module_name}/", "config_module", self::class);
        
        $general = Router::GET("/settings/general", "index", self::class);
        $route = Router::GET("/settings/route", "route", self::class);

        Box::module("Panel")->addMenu([
            'order' => 100,
            'name' => "Settings",
            'icon' => 'fa-solid fa-gear',
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


    function saveEndpoint(Request $req)
    {
        $token = $req->setting_token();

        if (!$token || !AES::decrypt($token)) {
            return [
                "code" => 401,
                "message" => "Unauthorized"
            ];
        }
        $data = AES::decrypt($token);
        $entry = unserialize($data);

        if (!isset($entry['module']) || !$entry['input']) {
            return [
                "code" => 401,
                "message" => "Unauthorized"
            ];
        }

        $module_name = $entry['module'];
        $input = $entry['input'];

        try {

            $module = Box::module(ucfirst($module_name));
            $config = $module->getConfig();
            foreach ($input as $name) {
                $config->set($name, $req->$name());
            }

            return [
                "code" => 200,
                "message" => "Saved"
            ];
        } catch (\Exception $e) {

            return [
                "code" => 500,
                "message" => $e->getMessage()
            ];
        }
    }


    function index($view)
    {

        return View::render('index.html.twig');
    }



    // function updateSetting($request)
    // {

    //     $setting = $this->module->__getSettings();
    //     $_BODY = $request->getRequestBody();

    //     if (!isset($_BODY['website_name']) || empty($_BODY['website_name'])) {
    //         return [
    //             "code" => 400,
    //             "message" => "Website name is required",
    //         ];
    //     }

    //     $setting['website_name'] = $_BODY['website_name'];
    //     $setting['website_email'] = $_BODY['website_email'] ?? "";
    //     $setting['website_timezone'] = $_BODY['website_timezone'] ?? "";
    //     $setting['website_date_format'] = $_BODY['website_date_format'] ?? "";

    //     return [
    //         "code" => 200,
    //         "message" => "Settings updated successfully",
    //     ];
    // }



    function route($request)
    {
        $statck = Router::getInstance()->getRouteStack();

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




    function config_module(Request $request)
    {

        $module_name = $request->module_name();
        $module = Box::module(ucfirst($module_name));

        return View::render('config.html.twig', [
            "configs" => $module->getConfig()->getStack(),
            "module_name" => $module_name
        ]);
    }


}
