<?php

namespace MerapiPanel\Module\Setting\Controller;


use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Proxy;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Util;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;
use Symfony\Component\Filesystem\Path;


class Admin extends __Fragment
{

    protected $module;
    function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function register()
    {

        if (!$this->module->getRoles()->isAllowed(0)) {
            return;
        }

        Router::POST("/settings/general", [$this, 'updateSetting']);
        Router::POST("/setting/endpoint/save", [$this, 'saveEndpoint']);
        Router::GET("/settings/config/{module_name}/", [$this, 'config_module']);

        // $general = Router::GET("/settings/general", [$this, 'index']);
        $route = Router::GET("/settings/route", [$this, 'route']);

        Box::module("Panel")->addMenu([
            'order' => 100,
            'name' => "Settings",
            'icon' => 'fa-solid fa-gear',
            "children" => [
                [
                    'order' => 0,
                    'name' => "Route",
                    'icon' => '<i class="fa-solid fa-route"></i>',
                    'link' => $route
                ],
                [
                    'order' => 0,
                    'name' => "Role",
                    'icon' => 'fa-solid fa-user-tag',
                    'link' => Router::GET("/settings/role", [$this, 'role'])
                ],
                [
                    'order' => 10,
                    'name' => "update",
                    'icon' => 'fa-solid fa-circle-info',
                    'link' => Router::GET("/settings/update", [$this, "update"])
                ]
            ]
        ]);

        $roles = json_encode([
            "modifyConfig" => $this->module->getRoles()->isAllowed(1) ? true : false,
            "modifyRule" => $this->module->getRoles()->isAllowed(2) ? true : false
        ]);
        $html = <<<HTML
        <script>
            __.Setting.roles = {$roles};
        </script>
        HTML;
        Box::module("Panel")->Scripts->add("setting-opts", $html);


        Router::GET("settings/check-update", function () {
            include_once $_ENV['__MP_APP__'] . "/scripts/update.php";
            return checkForUpdate();
        });
    }



    function index($view)
    {

        return View::render('admin/index');
    }


    function role()
    {

        $rolesData = $this->getRolesData();
        return View::render('admin/role', [
            "rolestack" => $rolesData
        ]);
    }


    private function getRolesData()
    {

        // get roles from database
        $from_database = [];
        try {
            $SQL = "SELECT * FROM roles";
            $from_database = DB::instance()->query($SQL)->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // do nothing
        }



        // get roles from modules
        $roles_stack = [];
        $pattern = Path::join($_ENV["__MP_APP__"], "Module", "*", "roles.json");
        foreach (glob($pattern) as $file) {
            $moduleName = strtolower(basename(dirname($file)));
            $data = json_decode(file_get_contents($file), true);
            if ($data) {
                $roles_stack[] = [
                    ...$data,
                    "module" => $moduleName,
                ];
            }
        }


        $rolesName = Util::getRoles();
        $data = [];
        foreach ($rolesName as $roleName) {

            $data[] = [
                "name" => $roleName,
                "roles" => array_map(function ($item) use ($roleName, $from_database) {
                    $moduleName = $item['module'];
                    $defaults = $item['defaults'] ?? [];
                    $roles = array_map(function ($role) use ($roleName, $defaults, $moduleName, $from_database) {

                        $value = isset($role['default']) && in_array($role['default'], [0, false]) ? $role['default'] : true;
                        $find = array_values(array_filter($from_database, function ($item) use ($roleName, $moduleName, $role) {
                            return $item['role'] === $roleName && $item['name'] === $moduleName . "." . $role['id'];
                        }));
                        if (!empty($find)) {
                            $value = $find[0]['value'];
                        } else {
                            if (isset($defaults[$roleName]['value'][$role['id']])) {
                                $value = $defaults[$roleName]['value'][$role['id']];
                            }
                        }
                        return [
                            ...$role,
                            "value" => $value,
                            "enable" => isset($defaults[$roleName]['enable']) ? $defaults[$roleName]['enable'] : true
                        ];
                    }, $item['roles']);

                    unset($item['defaults'], $item['roles']);
                    return [
                        ...$item,
                        "roles" => $roles
                    ];
                }, $roles_stack),
            ];
        }

        return $data;
    }


    function route($request)
    {
        $statck = Router::getInstance()->getRouteStack();

        return View::render('admin/route', [
            "route_stack" => array_map(

                fn ($key) => [
                    "name" => $key,
                    "routes" => array_map(

                        fn ($route) => [
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
        $configs = $module->getConfig()->getStack();

        return View::render('config', [
            "configs" => $configs,
            "module_name" => $module_name
        ]);
    }


    function update()
    {
        $manifest = json_decode(file_exists($_ENV['__MP_APP__'] . "/manifest.json") ? file_get_contents($_ENV['__MP_APP__'] . "/manifest.json") : "[]");
        return View::render("update", [
            "manifest" => $manifest
        ]);
    }
}
