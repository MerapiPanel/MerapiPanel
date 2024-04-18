<?php

namespace MerapiPanel\Module\User\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Http\Request;
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

        Router::GET("/users/add", "addUser", self::class);

        $index = Router::GET("/users", "index", self::class);
        $avatar_setting = Router::GET("/settings/users/avatar", "avatar", self::class);

        Box::module("Panel")->addMenu([
            "parent" => "settings",
            "name" => "Users Settings",
            "children" => [
                [
                    "name" => "Avatar",
                    "link" => $avatar_setting
                ]
            ]
        ]);

        Box::module("Panel")->addMenu([
            "name" => "Users",
            "link" => $index,
            'icon' => 'fa-solid fa-user'
        ]);
    }


    function addUser(Request $req) {

        return View::render("add_user.twig");
    }

    function avatar($req) {


        return View::render("setting_avatar.html.twig");
    }

    public function index($req)
    {

        return View::render("index.html.twig");
    }
}
