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
        $index = Router::GET("/article", "index", self::class);
        Router::GET($config->get('path_create'), "create", self::class);
        Router::GET($config->get('path_edit'), "edit", self::class);
        Router::GET("/article/view/{id}", "view", self::class);
        $options = Router::GET("/article/options", "options", self::class);


        Box::module("Panel")->addMenu([
            "name" => "Article",
            "icon" => "fa-solid fa-newspaper",
            "link" => $index
        ]);

        Box::module("Panel")->addMenu([
            "name" => "Article options",
            "parent" => "settings",
            "icon" => '<svg viewBox="0 0 536.41 506.22" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="m192.41 0c-39.8 0-72 32.2-72 72v137.27c.10467-.00019.20779-.006.3125-.006 9.5714 0 18.945.79233 28.055 2.3106 6.0729.99014 10.693 5.7431 12.014 11.75l7.6191 34.801v-186.12c0-13.3 10.7-24 24-24h272c13.3 0 24 10.7 24 24v304c0 13.3-10.7 24-24 24h-212.66l23.105 21.023c4.5547 4.0926 6.3369 10.497 4.2246 16.24-1.3497 3.6503-2.8417 7.2221-4.4414 10.736h189.77c39.8 0 72-32.2 72-72v-304c0-39.8-32.2-72-72-72zm-144 56c-13.3 0-24 10.7-24 24v191.83l18.949 6.0234c8.8453-6.799 18.615-12.477 29.045-16.768l.0059-.0273v-181.05c0-13.3-10.7-24-24-24zm176 24c-13.3 0-24 10.7-24 24v80c0 13.3 10.7 24 24 24h96c13.3 0 24-10.7 24-24v-80c0-13.3-10.7-24-24-24zm176 0c-13.3 0-24 10.7-24 24s10.7 24 24 24h32c13.3 0 24-10.7 24-24s-10.7-24-24-24zm0 80c-13.3 0-24 10.7-24 24s10.7 24 24 24h32c13.3 0 24-10.7 24-24s-10.7-24-24-24zm-176 80c-13.3 0-24 10.7-24 24 0 4.3988 1.2568 8.4572 3.3047 11.994l31.139-9.8945c5.8088-1.8483 12.279-.1984 16.174 4.4883 4.5546 5.5104 8.7506 11.327 12.594 17.412h168.79c13.3 0 24-10.7 24-24s-10.7-24-24-24zm54.887 80c1.6953 5.541-.0845 11.561-4.4394 15.475l-28.584 26.008c.28479 2.1489.47487 4.3318.6543 6.5176h185.48c13.3 0 24-10.7 24-24s-10.7-24-24-24z"/><path d="m240.46 333.62c1.5973 4.3426.24958 9.1844-3.1946 12.279l-21.613 19.667c.54906 4.143.84856 8.3857.84856 12.678s-.2995 8.5355-.84856 12.678l21.613 19.667c3.4442 3.0947 4.7919 7.9365 3.1946 12.279-2.1963 5.9399-4.8418 11.63-7.8866 17.121l-2.346 4.0431c-3.2944 5.4907-6.9881 10.682-11.031 15.574-2.945 3.5939-7.8367 4.7918-12.229 3.3942l-27.803-8.835c-6.6886 5.1413-14.076 9.434-21.963 12.678l-6.2394 28.502c-.9983 4.5423-4.4924 8.1362-9.0846 8.8849-6.8883 1.148-13.976 1.747-21.214 1.747-7.2377 0-14.326-.59899-21.214-1.747-4.5922-.74873-8.0862-4.3426-9.0846-8.8849l-6.2394-28.502c-7.8866-3.2445-15.274-7.5372-21.963-12.678l-27.753 8.8849c-4.3925 1.3976-9.2842.14974-12.229-3.3942-4.0431-4.8917-7.7368-10.083-11.031-15.574l-2.346-4.0431c-3.0448-5.4906-5.6903-11.181-7.8866-17.121-1.5973-4.3426-.24957-9.1844 3.1946-12.279l21.613-19.667c-.54906-4.1929-.84856-8.4357-.84856-12.728s.29949-8.5355.84856-12.678l-21.613-19.667c-3.4441-3.0947-4.7918-7.9365-3.1946-12.279 2.1963-5.9399 4.8418-11.63 7.8866-17.121l2.346-4.0431c3.2944-5.4906 6.9881-10.682 11.031-15.574 2.945-3.5939 7.8367-4.7919 12.229-3.3942l27.803 8.835c6.6886-5.1412 14.076-9.434 21.963-12.678l6.2394-28.502c.99829-4.5423 4.4924-8.1362 9.0846-8.8849 6.8883-1.198 13.976-1.797 21.214-1.797s14.326.599 21.214 1.747c4.5922.74872 8.0863 4.3426 9.0846 8.8849l6.2394 28.502c7.8866 3.2445 15.274 7.5372 21.963 12.678l27.803-8.835c4.3925-1.3976 9.2842-.14975 12.229 3.3942 4.0431 4.8917 7.7369 10.083 11.031 15.574l2.346 4.0431c3.0448 5.4907 5.6903 11.181 7.8866 17.121zm-119.75 84.556a39.932 39.932 0 100-79.864 39.932 39.932 0 100 79.864z"/></svg>',
            "link" => $options
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
