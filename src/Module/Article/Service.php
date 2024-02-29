<?php

namespace MerapiPanel\Module\Article;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Util;

class Service extends Module
{

    private $directory = __DIR__ . "/content";

    public array $config = [
        "name" => "Article",
        "slug" => "article",
    ];

    public function setBox(Box $box)
    {
        error_log("Config: " . json_encode([]));
    }


    public function save(array $data)
    {

        if (!isset($data['content']) || empty($data['content'])) {
            throw new \Exception("Content is required");
        }
        if (!isset($data["title"]) || empty($data["title"])) {
            throw new \Exception("Title is required");
        }
        if (!isset($data["slug"]) || empty($data["slug"])) {
            throw new \Exception("Slug is required");
        }

        $session = Box::module("auth")->getSession();
        $content = $data["content"];
        $thumbnail = $data["thumbnail"];
        $title = $data["title"];
        $slug = $data["slug"];
        $category_id = $data["category_id"];
        $description = $data["description"];
        $isPublish = $data["isPublish"];

        $id = Util::uniqReal();

        if (
            !DB::table("articles")->insert(
                [
                    "id" => $id,
                    "title" => $title,
                    "category_id" => $category_id,
                    "user_id" => $session["id"] ?? 0,
                    "description" => $description,
                    "status" => $isPublish,
                    "created_at" => time(),
                    "updated_at" => time()
                ]
            )->execute()
        ) {

            throw new \Exception("Failed to create article");
        }

        if (!file_put_contents($this->directory . "/" . $id . ".json", (gettype($content) == "string" ? json_encode(json_decode($content, true), JSON_PRETTY_PRINT) : json_encode($content, JSON_PRETTY_PRINT)))) {
            DB::table("articles")->delete()->where("id")->equal($id)->execute();
            throw new \Exception("Failed to save article content");
        }

        if ($thumbnail) {
            file_put_contents($this->directory . "/" . $id . ".jpg", $thumbnail);
        }
        return $id;
    }


    public function update($id, array $data)
    {

        if (!DB::table("articles")->select("id")->where("id")->equal($id)->execute()->fetch(\PDO::FETCH_ASSOC)) {
            throw new \Exception("Article not found");
        }

        if (!isset($data['content']) || empty($data['content'])) {
            throw new \Exception("Content is required");
        }
        if (!isset($data["title"]) || empty($data["title"])) {
            throw new \Exception("Title is required");
        }
        if (!isset($data["slug"]) || empty($data["slug"])) {
            throw new \Exception("Slug is required");
        }

        $session = Box::module("auth")->getSession();
        $content = $data["content"];
        $thumbnail = $data["thumbnail"];
        $title = $data["title"];
        $slug = $data["slug"];
        $category_id = $data["category_id"];
        $description = $data["description"];
        $isPublish = $data["isPublish"];

        if (
            !DB::table("articles")->update([
                "title" => $title,
                "category_id" => $category_id,
                "description" => $description,
                "status" => $isPublish,
                "updated_at" => time()
            ])->where("id")->equal($id)->execute()
        ) {

            throw new \Exception("Failed to update article");
        }

        if (
            !file_put_contents($this->directory . "/" . $id . ".json", (gettype($content) == "string" ? json_encode(json_decode($content, true), JSON_PRETTY_PRINT) : json_encode($content, JSON_PRETTY_PRINT)))
        ) {
            throw new \Exception("Failed to save article content");
        }
        if ($thumbnail) {
            if (!file_put_contents($this->directory . "/" . $id . ".jpg", $thumbnail)) {
                throw new \Exception("Failed to save article thumbnail");
            }
        }

        return true;
    }


    public function fetchAll()
    {

        $articles = DB::table("articles")->select("*")->execute()->fetchAll(\PDO::FETCH_ASSOC);
        $categorys = DB::table("category")->select("*")->execute()->fetchAll(\PDO::FETCH_ASSOC);

        $options = Box::module("article")->getOptions();
        $linkFormat = $options['links_style_format'][$options['link_format']];

        foreach ($articles as &$article) {

            $article["created_at"] = date("Y M d, H:i:s", $article["created_at"]);
            $article["updated_at"] = date("Y M d, H:i:s", $article["updated_at"]);
            $slug = preg_replace("/[^a-z0-9]+/i", "-", $article["title"]);
            $link = str_replace('{id}', $article["id"], $linkFormat);

            if ($article['category_id'] && $found_key = array_search($article['category_id'], array_column($categorys, "id"))) {
                $link = str_replace('{category}', ($categorys[$found_key]['name']) || "default", $link);
            }
            $link = str_replace('{category}', "default", $link);

            $link = str_replace('{slug}', $slug, $link);
            $article['link'] = Util::siteURL() . "/" . ltrim($link, "\\/");
        }

        return $articles;
    }
}
