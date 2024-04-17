<?php

namespace MerapiPanel\Module\Article;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Database\DB;
use PDO;

class Service extends __Fragment
{

    protected $module;

    public function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }

    function fetchAll($columns = ["id", "title", "slug", "data", "post_date", "update_date"])
    {

        $SQL = "SELECT " . implode(",", array_map(function ($item) {
            if (strpos($item, "users.") === 0) {
                return str_replace("users.", "B.", $item);
            }
            return "A.{$item}";
        }, $columns)) . " FROM articles A LEFT JOIN users B ON A.author = B.id ORDER BY `post_date` DESC";

        $articles = DB::instance()->query($SQL)->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($articles)) {
            foreach ($articles as $key => $value) {
                if (isset($value['data'])) {
                    $articles[$key]['data'] = json_decode($value['data'], true);
                }
                $articles[$key]['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/article/' . $value['slug'];
            }
        }
        return $articles;
    }

    function fetchOne($columns = ["id", "title", "data", "slug", "post_date", "update_date"], $id = null)
    {

        $SQL = "SELECT " . implode(",", array_map(function ($item) {
            if (strpos($item, "users.") === 0) {
                return str_replace("users.", "B.", $item);
            }
            return "A.{$item}";
        }, $columns)) . " FROM articles A LEFT JOIN users B ON A.author = B.id WHERE A.id = ? ORDER BY `post_date` DESC";

        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute([$id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($article) {
            if (isset($article['data'])) {
                $article['data'] = json_decode($article['data'], true);
            }
            $article['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/article/' . $article['slug'];
        }

        return $article;
    }


    function fetch($columns = ["id", "title", "data", "slug", "post_date", "update_date"], $where = ["id" => 1])
    {
        $SQL = "SELECT " . implode(",", array_map(function ($item) {
            if (strpos($item, "users.") === 0) {
                return str_replace("users.", "B.", $item);
            }
            return "A.{$item}";
        }, $columns)) . " FROM articles A LEFT JOIN users B ON A.author = B.id WHERE ";
        $SQL .= implode(" AND ", array_map(function ($item) {
            if (strpos($item, "users.") === 0) {
                return str_replace("users.", "B.", $item);
            }
            return "A.{$item} = ?";
        }, array_keys($where)));
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute(array_values($where));
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if($article) {
            if (isset($article['data'])) {
                $article['data'] = json_decode($article['data'], true);
            }
            $article['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/article/' . $article['slug'];
        }
        return $article;
    }
}
