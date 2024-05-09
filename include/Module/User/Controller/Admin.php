<?php

namespace MerapiPanel\Module\User\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Util;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;


class Admin extends __Fragment
{


    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }

    public function register()
    {

        if(!$this->module->getRoles()->isAllowed(0)) {
            return;
        }

        Router::GET("/users/add", "addUser", self::class);
        $index = Router::GET("/users", "index", self::class);
        Box::module("Panel")->addMenu([
            "name" => "Users",
            "link" => $index,
            'icon' => 'fa-solid fa-user'
        ]);

        $script = <<<HTML
        <script>
        __.MUser.opts = {
            endpoints: {
                fetch: "{{ '/api/User/fetch' | access_path }}",
                fetchAll: "{{ '/api/User/fetchAll' | access_path }}",
                update: "{{ '/api/User/update' | access_path }}",
                delete: "{{ '/api/User/delete' | access_path }}"
            },
            session: {{ api.Auth.LogedinUser | json_encode | raw }},
            roles: {{ api.User.getRoles | json_encode | raw }}
        }
        </script>
        HTML;
        Box::module("Panel")->Scripts->add("user-opts", $script);
    }


    function addUser(Request $req)
    {

        return View::render("add_user.twig");
    }

    function avatar($req)
    {


        return View::render("setting_avatar.html.twig");
    }

    public function index($req)
    {

        return View::render("index.html.twig");
    }
}
