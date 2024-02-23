<?php
namespace MerapiPanel\Module\Article\Controller\Endpoint;

use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Util;

class Category
{

    function create(Request $req)
    {

        $name = $req->name();
        if (!$name) {
            return [
                "code" => 400,
                "message" => "Invalid request"
            ];
        }

        $query = DB::table("category")->insert([
            "name" => $name,
            "created_at" => time(),
            "updated_at" => time(),
        ])->execute();
        if ($query) {
            return [
                "code" => 200,
                "message" => "success"
            ];
        }
        return [
            "code" => 500,
            "message" => "fail"
        ];
    }



    function delete(Request $req)
    {

        $id = $req->id();

        if (!$id) {
            return [
                "code" => 400,
                "message" => "Invalid request"
            ];
        }

        $query = DB::table("category")->select("*")->where("id")->equal($id)->execute();
        if (!$query->fetch(\PDO::FETCH_ASSOC)) {
            return [
                "code" => 404,
                "message" => "Not found"
            ];
        }

        if ((!DB::table("category")->delete()->where("id")->equal($id)->execute())) {
            return [
                "code" => 500,
                "message" => "fail to delete category"
            ];
        }

        return [
            "code" => 200,
            "message" => "success"
        ];
    }


    function edit(Request $req)
    {

        $id = $req->id();
        $name = $req->name();

        if (!$id || !$name || strlen($name) < 3) {
            return [
                "code" => 400,
                "message" => "Invalid request"
            ];
        }

        $query = DB::table("category")->select("*")->where("id")->equal($id)->execute();
        if (!$query->fetch(\PDO::FETCH_ASSOC)) {
            return [
                "code" => 404,
                "message" => "Not found"
            ];
        }

        if (!(DB::table("category")->update(["name" => $name, "updated_at" => time()])->where("id")->equal($id)->execute())) {
            return [
                "code" => 500,
                "message" => "fail to update category"
            ];
        }

        return [
            "code" => 200,
            "message" => "success",
            "data" => [
                    "id" => $id,
                    "name" => $name,
                    "updated_at" => Util::timeAgo(time())
                ]
        ];
    }
}