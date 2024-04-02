<?php

namespace MerapiPanel\Module\Auth\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Views\View;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Router;

class Guest extends __Fragment
{

    protected $module;
    function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function register()
    {

        Router::GET("/login", "index", self::class);
        Router::GET("/login", "index", self::class);
        Router::POST("/login", "login", self::class);
    }


    public function index($request)
    {

        return View::render("login.html.twig");
    }


    public function login($request)
    {

        $email = $request->email();
        $password = $request->password();

        $user = Box::module("User")->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            $this->module->setSession($user['username']);
            return Response::with("success")->redirect("/panel/admin");
        }

        return Response::with("failed!, user or password incorrect")->redirect($_SERVER['HTTP_REFERER']);
    }
}
