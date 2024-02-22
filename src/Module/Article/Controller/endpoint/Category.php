<?php
namespace MerapiPanel\Module\Article\Controller\Endpoint;

use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Http\Request;

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
}