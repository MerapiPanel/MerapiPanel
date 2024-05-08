<?php

namespace MerapiPanel\Module\Article\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;

class Admin extends __Controller
{

    function register()
    {

        if (!$this->module->getRoles()->isAllowed(0)) {
            return;
        }

        Router::GET("/article/create/", "create", self::class);
        Router::GET("/article/edit/{id}", "edit", self::class);
        Router::GET("/article/view/{id}", "view", self::class);
        $index = Router::GET("/article", "index", self::class);


        Box::module("Panel")->addMenu([
            "name" => "Article",
            "icon" => "fa-solid fa-newspaper",
            "link" => $index
        ]);
        $scrips = <<<HTML
        <script>
            __.Article.endpoints = {
                createURL: "{{ '/article/create/' | access_path }}",
                editURL: "{{ '/article/edit/{id}' | access_path }}",
                fetchAll: "{{ '/api/Article/fetchAll' | access_path }}",
                save: "{{ '/api/Article/save' | access_path }}",
                delete: "{{ '/api/Article/delete' | access_path }}",
                update: "{{ '/api/Article/update' | access_path }}",
                view: "{{ '/article/view/{id}' | access_path }}"
            }
        </script>
        HTML;
        Box::module("Panel")->Scripts->add("article-opts", $scrips);
    }


    public function view(Request $request)
    {

        $id = $request->id();
        $article = $this->getModule()->fetch(["id", "title", "slug", "keywords", "users.name", "category", "description", "data", "status", "post_date", "update_date"], ["id" => $id]);

        if (!$article) {
            throw new \Exception("Article not found with id: $id", 404);
        }

        $components = Box::module("Editor")->Blocks->render($article['data']['components']);

        return View::render("public.html.twig", [
            "article" => $article,
            "components" => $components
        ]);
    }





    function index($view)
    {

        $collumns = ["id", "title", "slug", "status", "keywords", "category", "description", "post_date", "update_date", "users.id as author_id", "users.name as author_name"];
        $opts = ['status' => null];

        $articles = Box::module("Article")->fetchAll($collumns, $opts);
        return View::render("index.html.twig", [
            "articles" => $articles
        ]);
    }



    function create()
    {

        $config = $this->module->getConfig();

        return View::render("editor.html.twig", [
            "settings" => [
                "prefix" => $_ENV['__MP_' . strtoupper($_ENV["__MP_ACCESS__"]) . '__']['prefix'],
                "path_edit" => $config->get('path_edit'),
                "path_create" => $config->get('path_create')
            ]
        ]);
    }


    function edit(Request $request)
    {

        $id = $request->id();
        $article = Box::module("Article")->fetchOne(["id", "title", "slug", "keywords", "category", "description", "data", "status", "post_date", "update_date"], $id);

        if (!$article) {
            throw new \Exception("Article not found with id: $id", 404);
        }
        $config = $this->module->getConfig();

        return View::render("editor.html.twig", [
            "article" => [
                "id" => $article['id'],
                "title" => $article['title'],
                "slug" => $article['slug'],
                "keywords" => $article['keywords'],
                "category" => $article['category'],
                "description" => $article['description'],
                "data" => [
                    "components" => $article['data']['components'],
                    "css" => $article['data']['css']
                ],
                "status" => $article['status'],
                "post_date" => $article['post_date'],
                "update_date" => $article['update_date']
            ],
            "settings" => [
                "prefix" => $_ENV['__MP_' . strtoupper($_ENV["__MP_ACCESS__"]) . '__']['prefix'],
                "path_edit" => $config->get('path_edit'),
                "path_create" => $config->get('path_create')
            ]
        ]);
    }
}
