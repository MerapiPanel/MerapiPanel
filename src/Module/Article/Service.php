<?php

namespace MerapiPanel\Module\Article;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Database\DB;

class Service extends Module
{


    public array $config = [
        "name" => "Article",
        "slug" => "article",
        
    ];

    public function setBox(Box $box)
    {
        error_log("Config: " . json_encode([]));
    }



    public function fetchAll() {

        return DB::table("articles")->select(["id", "title", "user_id", "created_at", "updated_at"])->execute()->fetchAll(\PDO::FETCH_ASSOC);
    }
}
