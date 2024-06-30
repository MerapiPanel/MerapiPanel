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
                    "link" => Router::GET("/users/profile", [$this, 'profile']),
                    'icon' => '<i class="fa-regular fa-circle-user"></i>',
                    "order" => 4
                ]);
            }
            return;
        }


        Router::GET("/users/add", [$this, 'addUser']);
        $index = Router::GET("/users", [$this, 'index']);
        Box::module("Panel")->addMenu([
            "name" => "Users",
            "link" => $index,
            'icon' => 'fa-solid fa-user'
        ]);

        if ($config->get("profile")) {
            Box::module("Panel")->addMenu([
                "name" => "Profile",
                "link" => Router::GET("/users/profile", [$this, 'profile']),
                'icon' => '<i class="fa-regular fa-circle-user"></i>',
                "order" => 1,
                "parent" => "Users"
            ]);
            if ($this->module->getRoles()->isAllowed(2)) {
                Router::GET("/users/profile/{user_id}", [$this, 'otherProfile']);
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
            session: {{ _box.Auth.Session.getUser() | json_encode | raw }},
            roleNames: $roleNames,
            allowModify: {{ _box.User.getRoles.isAllowed(1) | json_encode | raw }},
            allowVisit: {{ _box.User.getRoles.isAllowed(2) | json_encode | raw }},
            profilePage: {{ _box.User.getConfig.get('profile') | json_encode | raw }},
        }
        </script>
        HTML;
        Box::module("Panel")->Scripts->add("user-opts", $script);
    }


    function addUser(Request $req)
    {
        return View::render("admin/add_user");
    }



    function profile(Request $req)
    {
        return View::render("admin/profile");
    }

    function otherProfile(Request $req)
    {

        $user_id = $req->user_id();
        $user = $this->module->fetch(...[
            "where" => [
                "id" => $user_id
            ]
        ]);
        return View::render("admin/profile", ["user" => $user]);
    }



    public function index($req)
    {

        return View::render("admin/index");
    }
}
