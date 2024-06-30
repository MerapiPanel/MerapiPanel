<?php

namespace MerapiPanel\Module\Auth\Controller;

use Google_Client;
use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\AES;
use MerapiPanel\Views\View;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Router;
use PDO;
use Throwable;

class Guest extends __Fragment
{

    protected $module;
    function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function register()
    {

        if (isset($_ENV["__MP_ADMIN__"]['prefix'])) {

            $login_path = trim($_ENV["__MP_ADMIN__"]['prefix'], "/") . "/login";


            Router::POST("/auth/api/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"), [$this, "GoogleAuth"]);
            Router::POST($login_path, [$this->module->Guest, "loginHandler"]);
            Router::GET($login_path, function () {

                if ($this->module->isAdmin()) {
                    return Response::with("Redirect...")->redirect("/" . trim($_ENV["__MP_ADMIN__"]['prefix'], "/") . "/dashboard");
                }
                return View::render("login");
            });
        }
    }
}
