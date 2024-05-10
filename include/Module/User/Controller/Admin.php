<?php

namespace MerapiPanel\Module\User\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Database\DB;
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

        $config = $this->module->getConfig();
        if (!$this->module->getRoles()->isAllowed(0)) {
            if ($config->get("profile")) {
                Box::module("Panel")->addMenu([
                    "name" => "Profile",
                    "link" => Router::GET("/users/profile", "profile", self::class),
                    'icon' => '<i class="fa-regular fa-circle-user"></i>',
                    "order" => 4
                ]);
            }
            return;
        }


        Router::GET("/users/add", "addUser", self::class);
        $index = Router::GET("/users", "index", self::class);
        Box::module("Panel")->addMenu([
            "name" => "Users",
            "link" => $index,
            'icon' => 'fa-solid fa-user'
        ]);

        if ($config->get("profile")) {
            Box::module("Panel")->addMenu([
                "name" => "Profile",
                "link" => Router::GET("/users/profile", "profile", self::class),
                'icon' => '<i class="fa-regular fa-circle-user"></i>',
                "order" => 1,
                "parent" => "Users"
            ]);
            if ($this->module->getRoles()->isAllowed(2)) {
                Router::GET("/users/profile/{user_id}", "otherProfile", self::class);
            }
        }
        $roleNames = json_encode(Util::getRoles());

        $script = <<<HTML
        <script>
        __.MUser.opts = {
            endpoints: {
                fetch: "{{ '/api/User/fetch' | access_path }}",
                fetchAll: "{{ '/api/User/fetchAll' | access_path }}",
                update: "{{ '/api/User/update' | access_path }}",
                delete: "{{ '/api/User/delete' | access_path }}",
                forceLogout: "{{ '/api/Auth/forceLogout' | access_path }}",
                profileURL: "{{ '/users/profile/{user_id}' | access_path }}",
            },
            session: {{ api.Auth.Session.getUser() | json_encode | raw }},
            roleNames: $roleNames,
            allowModify: {{ api.User.getRoles.isAllowed(1) | json_encode | raw }},
            allowVisit: {{ api.User.getRoles.isAllowed(2) | json_encode | raw }},
            profilePage: {{ api.User.getConfig.get('profile') | json_encode | raw }},
        }
        </script>
        HTML;
        Box::module("Panel")->Scripts->add("user-opts", $script);
    }


    function addUser(Request $req)
    {
        return View::render("add_user.twig");
    }



    function profile(Request $req)
    {
        return View::render("profile.html.twig");
    }

    function otherProfile(Request $req)
    {

        $user_id = $req->user_id();
        $user = $this->module->fetch(...[
            "where" => [
                "id" => $user_id
            ]
        ]);
        return View::render("profile.html.twig", ["user" => $user]);
    }



    public function index($req)
    {

        return View::render("index.html.twig");
    }
}
