<?php

namespace MerapiPanel\Module\Article\Controller;

use MerapiPanel\Box;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Http\Request;



class endpoint
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

            if (!$id) {
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
                    "content" => $content ?? false,
                    "thumbnail" => $thumbnail ?? false,
                    "title" => $title ?? false,
                    "slug" => $slug ?? false,
                    "category_id" => $category_id ?? false,
                    "description" => $description ?? false,
                    "isPublish" => $isPublish ?? false
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



    public function update_status(Request $req)
    {

        $id = $req->id();
        if (!$id || strlen($id) < 16) {
            return [
                "code" => 400,
                "message" => "id character is not possible to be less than 16",
            ];
        }

        $status = $req->status();

        if (!in_array($status, [0, 1])) {
            return [
                "code" => 400,
                "message" => "Invalid request, status must be 0 or 1 but " . $status . " given",
            ];
        }


        if (
            !Box::module("article")
                ->service()
                ->update($id, [
                    "status" => $status ?? false
                ])
        ) {
            return [
                "code" => 500,
                "message" => "failed to update article",
            ];
        }


        return [
            "code" => 200,
            "message" => "success",
        ];
    }



    function delete(Request $req)
    {

        $id = $req->id();
        $delete = Box::module("article")->service()->delete($id);

        if (!$delete) {
            return [
                "code" => 500,
                "message" => "failed to delete article",
            ];
        }

        return [
            "code" => 200,
            "message" => "article deleted successfully",
        ];
    }


}