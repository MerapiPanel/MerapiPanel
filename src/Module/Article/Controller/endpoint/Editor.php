<?php
namespace MerapiPanel\Module\Article\Controller\Endpoint;

use MerapiPanel\Box;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Util;

class Editor
{

    private $directory = __DIR__ . "/../../content";

    public function __construct()
    {
        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }
    }



    function save(Request $req)
    {
        try {

            $content = $req->content();
            $thumbnail = $req->thumbnail();
            $title = $req->title();
            $slug = $req->slug();
            $category_id = $req->category();
            $isCustomDescription = $req->isCustomDescription();
            $description = $req->description();
            $isPublish = $req->isPublish();

            if (!empty($req->id())) {
                return $this->update($req);
            }



            $id = Box::module("article")->service()->save([
                "content" => $content,
                "thumbnail" => $thumbnail,
                "title" => $title,
                "slug" => $slug,
                "category_id" => $category_id,
                "isCustomDescription" => $isCustomDescription,
                "description" => $description,
                "isPublish" => $isPublish
            ]);

            if(!$id) {
                return [
                    "code" => 500,
                    "message" => "failed to create article",
                ];
            }

            return [
                "code" => 200,
                "message" => "new article created successfully",
                "data" => [
                    "id" => $id
                ]
            ];

        } catch (\Exception $e) {
            return [
                "code" => 500,
                "message" => $e->getMessage(),
            ];
        }
    }


    function update(Request $req)
    {
        try {

            $content = $req->content();
            $thumbnail = $req->thumbnail();
            $title = $req->title();
            $slug = $req->slug();
            $category_id = $req->category();
            $isCustomDescription = $req->isCustomDescription();
            $description = $req->description();
            $isPublish = $req->isPublish();
            $id = $req->id();

            if (
                !Box::module("article")->service()->update($id, [
                    "content" => $content,
                    "thumbnail" => $thumbnail,
                    "title" => $title,
                    "slug" => $slug,
                    "category_id" => $category_id,
                    "description" => $description,
                    "isPublish" => $isPublish
                ])
            ) {
                return [
                    "code" => 500,
                    "message" => "failed to update article",
                ];
            }

            return [
                "code" => 200,
                "message" => "article updated successfully",
                "data" => [
                    "id" => $id
                ]
            ];

        } catch (\Exception $e) {
            return [
                "code" => 500,
                "message" => $e->getMessage(),
            ];
        }
    }
}