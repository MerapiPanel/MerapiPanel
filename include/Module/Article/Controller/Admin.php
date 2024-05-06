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

        $config = $this->module->getConfig();
        

        Router::POST("/article/endpoint/save", "endpointSave", self::class);
        Router::DELETE("/article/endpoint/delete", "endpointDelete", self::class);
        Router::POST("/article/endpoint/update", "endpointUpdate", self::class);
        Router::GET($config->get('path_create'), "create", self::class);
        Router::GET($config->get('path_edit'), "edit", self::class);
        Router::GET("/article/view/{id}", "view", self::class);
        $index = Router::GET("/article", "index", self::class);


        Box::module("Panel")->addMenu([
            "name" => "Article",
            "icon" => "fa-solid fa-newspaper",
            "link" => $index
        ]);
    }


    public function view(Request $request)
    {

        $id = $request->id();
        $article = $this->getModule()->fetch(["id", "title", "slug", "keywords", "users.name as author", "category", "description", "data", "status", "post_date", "update_date"], ["id" => $id]);

        if (!$article) {
            throw new \Exception("Article not found with id: $id", 404);
        }

        $components = Box::module("Editor")->Blocks->render($article['data']['components']);

        return View::render("public.html.twig", [
            "article" => $article,
            "components" => $components
        ]);
    }


    function options(Request $req)
    {

        return View::render("options.html.twig");
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





    // function endpointSave(Request $req)
    // {

    //     if (!empty($req->id())) {
    //         return $this->endpointUpdate($req);
    //     }
    //     $title = $req->title();
    //     $slug = $req->slug();
    //     $keywords = $req->keywords();
    //     $category = $req->category();
    //     $description = $req->description();
    //     $data = $req->data();
    //     $id = Util::uniq(12);
    //     $status = $req->status() ? 1 : 0;
    //     $author = null;
    //     $post_date = date("Y-m-d H:i:s");
    //     $update_date = date("Y-m-d H:i:s");

    //     $user = Box::module("Auth")->getLogedinUser();
    //     if (!isset($user['id'])) {
    //         return [
    //             "code" => 403,
    //             "message" => "Not allowed",
    //             "data" => [
    //                 "id" => $id,
    //                 "title" => $title,
    //                 "slug" => $slug,
    //                 "keywords" => $keywords,
    //                 "category" => $category,
    //                 "description" => $description,
    //                 "data" => $data,
    //                 "status" => $status,
    //                 "author" => $author,
    //                 "post_date" => $post_date,
    //                 "update_date" => $update_date
    //             ]
    //         ];
    //     }
    //     $author = $user['id'];




    //     $SQL = "INSERT INTO articles (id, title, slug, keywords, category, description, data, status, author, post_date, update_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //     $stmt = DB::instance()->prepare($SQL);

    //     $insert = $stmt->execute([
    //         $id,
    //         $title,
    //         $slug,
    //         $keywords,
    //         $category,
    //         $description,
    //         $data,
    //         $status,
    //         $author,
    //         $post_date,
    //         $update_date
    //     ]);

    //     if (!$insert) {

    //         return [
    //             "code" => 500,
    //             "message" => "Error, please try again",
    //             "data" => [
    //                 "id" => $id,
    //                 "title" => $title,
    //                 "slug" => $slug,
    //                 "keywords" => $keywords,
    //                 "category" => $category,
    //                 "description" => $description,
    //                 "data" => $data,
    //                 "status" => $status,
    //                 "author" => $author,
    //                 "post_date" => $post_date,
    //                 "update_date" => $update_date
    //             ]
    //         ];
    //     }

    //     return [
    //         "code" => 200,
    //         "message" => "Saved",
    //         "data" => [
    //             "id" => $id,
    //             "title" => $title,
    //             "slug" => $slug,
    //             "keywords" => $keywords,
    //             "category" => $category,
    //             "description" => $description,
    //             "data" => $data,
    //             "status" => $status,
    //             "author" => $author,
    //             "post_date" => $post_date,
    //             "update_date" => $update_date
    //         ]
    //     ];
    // }

    // function endpointUpdate(Request $req)
    // {
    //     $id = $req->id();
    //     $title = $req->title();
    //     $slug = $req->slug();
    //     $keywords = $req->keywords();
    //     $category = $req->category();
    //     $description = $req->description();
    //     $data = $req->data();
    //     $status = empty($req->status()) ? 0 : 1;

    //     if (empty($id)) {
    //         return [
    //             "code" => 500,
    //             "message" => "Error, please try again",
    //             "data" => [
    //                 "id" => $id,
    //                 "title" => $title,
    //                 "slug" => $slug,
    //                 "keywords" => $keywords,
    //                 "category" => $category,
    //                 "description" => $description,
    //                 "data" => $data,
    //                 "status" => $status
    //             ]
    //         ];
    //     }

    //     if (!empty($data)) {

    //         $SQL = "UPDATE articles SET title = ?, slug = ?, keywords = ?, category = ?, description = ?, data = ?, status = ? WHERE id = ?";
    //         $stmt = DB::instance()->prepare($SQL);

    //         $update = $stmt->execute([$title, $slug, $keywords, $category, $description, $data, $status, $id]);
    //     } else {

    //         $SQL = "UPDATE articles SET title = ?, slug = ?, keywords = ?, category = ?, description = ?, status = ? WHERE id = ?";
    //         $stmt = DB::instance()->prepare($SQL);
    //         $update = $stmt->execute([$title, $slug, $keywords, $category, $description, $status, $id]);
    //     }

    //     if (!$update) {
    //         return [
    //             "code" => 500,
    //             "message" => "Error, please try again",
    //             "data" => [
    //                 "id" => $id,
    //                 "title" => $title,
    //                 "slug" => $slug,
    //                 "keywords" => $keywords,
    //                 "category" => $category,
    //                 "description" => $description,
    //                 "status" => $status
    //             ]
    //         ];
    //     }

    //     return [
    //         "code" => 200,
    //         "message" => "Updated successfully",
    //         "data" => [
    //             "id" => $id,
    //             "title" => $title,
    //             "slug" => $slug,
    //             "keywords" => $keywords,
    //             "category" => $category,
    //             "description" => $description,
    //             "data" => $data,
    //             "status" => $status
    //         ]
    //     ];

    // }

    // function endpointDelete(Request $request)
    // {
    //     $id = $request->id();
    //     if (empty($id)) {
    //         return [
    //             "code" => 500,
    //             "message" => "Error, please try again",
    //             "data" => [
    //                 "id" => $id
    //             ]
    //         ];
    //     }
    //     $SQL = "DELETE FROM articles WHERE id = ?";
    //     $stmt = DB::instance()->prepare($SQL);
    //     $delete = $stmt->execute([$id]);
    //     if (!$delete) {
    //         return [
    //             "code" => 500,
    //             "message" => "Error, please try again",
    //             "data" => [
    //                 "id" => $id
    //             ]
    //         ];
    //     }
    //     return [
    //         "code" => 200,
    //         "message" => "Deleted successfully",
    //         "data" => [
    //             "id" => $id
    //         ]
    //     ];
    // }
}
