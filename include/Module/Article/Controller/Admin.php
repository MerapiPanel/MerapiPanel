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

        Router::GET("/article/create/", [$this, 'create']);
        Router::GET("/article/edit/{id}", [$this, 'edit']);
        Router::GET("/article/view/{id}", [$this, 'view']);
        $index = Router::GET("/article", [$this, 'index']);
        Box::module("Panel")->addMenu([
            "name" => "Article",
            "icon" => "fa-solid fa-newspaper",
            "link" => $index
        ]);

        $roles = json_encode([
            "modify" => $this->module->getRoles()->isAllowed(1) ? true : false,
        ]);

        $scrips = <<<HTML
        <script>
            __.Article.endpoints = {
                create: "{{ '/article/create/' | access_path }}",
                edit: "{{ '/article/edit/{id}' | access_path }}",
                fetchAll: "{{ '/api/Article/fetchAll' | access_path }}",
                save: "{{ '/api/Article/save' | access_path }}",
                delete: "{{ '/api/Article/delete' | access_path }}",
                update: "{{ '/api/Article/update' | access_path }}",
                view: "{{ '/article/view/{id}' | access_path }}"
            }
            __.Article.config = {
                roles: $roles,
                ...{
                    payload: {
                        page: 1,
                        limit: 5
                    }
                }
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

        return View::render("view", [
            "article" => $article,
            "components" => $components
        ]);
    }





    function index($view)
    {

        $collumns = ["id", "title", "slug", "status", "keywords", "category", "description", "post_date", "update_date", "users.id as author_id", "users.name as author_name"];
        $articles = Box::module("Article")->fetchAll($collumns);
        return View::render("index", [
            "articles" => $articles
        ]);
    }



    function create()
    {

        $config = $this->module->getConfig();

        return View::render("editor", [
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

        return View::render("editor", [
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
