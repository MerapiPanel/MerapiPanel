<?php
namespace MerapiPanel\Module\Article\Controller\Endpoint;

use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Http\Request;

class Update
{

    public function quick(Request $req)
    {

        $id = $req->id();
        if (!$id || strlen($id) < 16) {
            return [
                "code" => 400,
                "message" => "id character is not possible to be less than 16",
            ];
        }
        $data = [
            "title" => $req->title() ?? "",
            "category_id" => $req->category() ?? "",
            "description" => $req->description() ?? "",
            "status" => is_numeric($req->isPublish()) ? $req->isPublish() : 0,
        ];
        if (!DB::table("articles")->update($data)->where("id")->equal($id)->execute()) {
            return [
                "code" => 500,
                "message" => "fail to update article",
            ];
        }

        return [
            "code" => 200,
            "message" => "success",
            "data" => array_merge(["id" => $id], $data),
        ];
    }




    
}