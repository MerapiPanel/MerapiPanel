<?php
namespace MerapiPanel\Module\Article;

use MerapiPanel\Database\DB;

class CategoryService extends Service
{
    public function fetchAll()
    {
        $data = DB::table("category")->select(["id", "name", "created_at", "updated_at"])->execute()->fetchAll(\PDO::FETCH_ASSOC);

        return array_merge([[
            "id" => null,
            "name" => "default",
            "created_at" => null,
            "updated_at" => null
        ]], $data);
    }
}