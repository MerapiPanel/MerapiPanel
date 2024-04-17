<?php

namespace MerapiPanel\Module\User;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Database\DB;
use PDO;

class Service extends __Fragment
{
    
    protected $module;


    function onCreate(Box\Module\Entity\Module $module)
    {

        $this->module = $module;
    }




    public function fetch($columns = ["id", "name", "email", "role", "post_date", "update_date"], $where = ["id" => 1])
    {

        $SQL = "SELECT " . implode(",", array_map(function ($item) {
            return "`" . $item . "`";
        }, $columns)) . " FROM `users` WHERE " . implode(" AND ", array_map(function ($item) {
            return "`" . $item . "` = ?";
        }, array_keys($where)));

        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(array_values($where));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




    public function fetchAll($columns = ["id", "name", "email", "role", "post_date", "update_date"])
    {

        return DB::table("users")->select($columns)->execute()->fetchAll(PDO::FETCH_ASSOC);
    }


}
