<?php

namespace MerapiPanel\Module\Article\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Mod\Mod_Controller;
use MerapiPanel\Core\Views\View;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;

class Guest extends Mod_Controller
{

    function register(Router $router)
    {

        $options = Box::module("article")->getOptions();
        $link_format = $options["links_style_format"][$options["link_format"]];

        $router->get($link_format, "index");
    }


    public function index(Request $request)
    {
        $id = $request->id();
        $slug = $request->slug();
        $category = $request->category();

        $articles = Box::module("article")->service()->fetchById($id);
        if (!isset($articles[0])) {
            return throw new \Exception("Article not found", 404);
        }
        return View::render("public.html.twig", [
            "article" => $articles[0],
        ]);
    }

    public function showarticle(Request $req)
    {

        $date = $req->date();
        $id = $req->id();
        $title = $req->title();

        return View::render("index.html.twig");
    }
}
