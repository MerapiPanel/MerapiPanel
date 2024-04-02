<?php
namespace MerapiPanel\Module\Article;

use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Util;

class CategoryService extends Service
{
    public function fetchAll()
    {
        $data = DB::table("category")->select(["id", "name", "created_at", "updated_at"])->execute()->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($data as &$row) {
            $row['updated_at'] = Util::timeAgo($row['updated_at']);
            $row['created_at'] = date("Y M d, H:i", $row['created_at']);
        }

        return array_merge([
            [
                "id" => null,
                "name" => "default",
                "created_at" => null,
                "updated_at" => null
            ]
        ], $data);
    }
}