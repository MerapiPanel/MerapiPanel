<?php

namespace MerapiPanel\Module\Content\Controller;

use MerapiPanel\Core\Mod\Mod_Controller;
use MerapiPanel\Core\View\View;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;

class Guest extends Mod_Controller
{

    function register(Router $router)
    {

        $router->get("/content/{date}/{id}/{title}", "showContent", self::class);
    }


    public function showContent(Request $req)
    {

        $date  = $req->date();
        $id    = $req->id();
        $title = $req->title();

        return View::render("index.html.twig");
    }
}
