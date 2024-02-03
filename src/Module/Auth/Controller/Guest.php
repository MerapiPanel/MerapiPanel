<?php

namespace MerapiPanel\Module\Auth\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;
use MerapiPanel\Database\DB;
use MerapiPanel\Module\Auth\Middleware\Login;
use MerapiPanel\Utility\Http\Response;
use PDO;

class Guest extends Module
{

    protected $box;
    function setBox(Box $box)
    {

        $this->box = $box;
    }


    public function register($router)
    {

        // $router->get("/login",  "index", self::class);
        $router->get("/login",  "index", self::class);
        $router->post("/login",  "login", self::class);
    }


    public function index($request)
    {

        return View::render("login.html.twig");
    }


    public function login($request)
    {

        $email = $request->email();
        $password = $request->password();

        $user =  Box::Get($this)->Module_Users()->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            $this->service()->setAuthSession($user['username']);
            return Response::with("success")->redirect("/panel/admin");
        }

        return Response::with("failed!, user or password incorrect")->redirect($_SERVER['HTTP_REFERER']);
    }
}
