<?php

namespace MerapiPanel\Module\Article\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Utility\Router;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Http\Request;

class Guest extends __Controller
{

    public function register()
    {
        Router::GET("/article/{slug}", "index", self::class);
    }

    public function index(Request $request)
    {

        $slug = $request->slug();
        $article = $this->getModule()->fetch(["id", "title", "slug", "keywords", "users.name as author", "category", "description", "data", "status", "post_date", "update_date"], ["slug" => $slug]);

        if (!$article) {
            throw new \Exception("Article not found with slug: $slug", 404);
        }

        $components = Box::module("Editor")->Blocks->render($article['data']['components']);

        return View::render("public.html.twig", [
            "article" => $article,
            "components" => $components
        ]);
    }
}
