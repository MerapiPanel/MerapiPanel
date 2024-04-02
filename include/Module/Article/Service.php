<?php

namespace MerapiPanel\Module\Article;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Util;

class Service extends __Fragment
{

    private $directory = __DIR__ . "/content";

    public array $config = [
        "name" => "Article",
        "slug" => "article",
    ];



    public function getOptions()
    {
        return $this->config;
    }

    
    public function onCreate(Box\Module\Entity\Module $module)
    {

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

        if (!($article = DB::table("articles")->select("*")->where("id")->equal($id)->execute()->fetch(\PDO::FETCH_ASSOC))) {
            throw new \Exception("Article not found");
        }

        $session = Box::module("auth")->getSession();
        $content = $data["content"];
        $thumbnail = $data["thumbnail"];

        $update = [];

        foreach ($data as $key => $value) {
            if (in_array($key, array_keys($article))) {
                $update[$key] = $value;
            }
        }

        if (
            !DB::table("articles")
                ->update(array_merge($update, ["updated_at" => time()]))
                ->where("id")
                ->equal($id)
                ->execute()
        ) {
            throw new \Exception("Failed to update article");
        }



        if (
            $content &&
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


    public function delete($id)
    {

        $article = DB::table("articles")->select("id")->where("id")->equal($id)->execute()->fetch(\PDO::FETCH_ASSOC);
        if (!$article) {
            throw new \Exception("Article not found");
        }

        $file_content = $this->directory . "/" . $id . ".json";
        $file_thumbnail = $this->directory . "/" . $id . ".jpg";

        if (!DB::table("articles")->delete()->where("id")->equal($id)->execute()) {
            throw new \Exception("Failed to delete article");
        }

        if (file_exists($file_content)) {
            unlink($file_content);
        }
        if (file_exists($file_thumbnail)) {
            unlink($file_thumbnail);
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


    public function fetchById($id)
    {

        $articles = DB::table("articles")->select("*")->where("id")->equal($id)->execute()->fetchAll(\PDO::FETCH_ASSOC);
        $articles = array_map(function ($article) {

            $file_content = __DIR__ . "/content/" . $article["id"] . ".json";
            if (file_exists($file_content)) {
                $json = json_decode(file_get_contents($file_content), true);
                $article['content'] = [
                    'json' => $json,
                    'html' => $this->parseBlock($json['blocks'])
                ];
            }

            $article['created_at'] = date('Y-m-d H:i:s', $article['created_at']);
            $article['updated_at'] = date('Y-m-d H:i:s', $article['updated_at']);

            return $article;
        }, $articles);
        return $articles;
    }

    public function fetchByCategoryId($categoryId)
    {

        $articles = DB::table("articles")->select("*")->where("category_id")->equal($categoryId)->execute()->fetchAll(\PDO::FETCH_ASSOC);
        return $articles;
    }

    public function fetchBySlug($slug)
    {

        $articles = DB::table("articles")->select("*")->where("slug")->equal($slug)->execute()->fetchAll(\PDO::FETCH_ASSOC);
        return $articles;
    }



    public function parseBlock($blocks = [])
    {

        $htmls = [];
        foreach ($blocks as $block) {
            $htmls[] = $this->parseBlockHtml($block);
        }

        return implode("\n", $htmls);
    }

    public function parseBlockHtml($block)
    {
        switch ($block["type"]) {
            case "header":
                return "<h" . $block["data"]["level"] . ">" . $block["data"]["text"] . "</h" . $block["data"]["level"] . ">";
            case "paragraph":
                return "<p>" . $block["data"]["text"] . "</p>";
            case "image":
                return "<img src='" . $block["data"]["src"] . "' alt='" . $block["data"]["alt"] . "'>";
            case "code":
                return "<pre><code>" . $block["data"]["text"] . "</code></pre>";
            case "delimiter":
                return "<hr/>";
            case "list":
                return "<ul><li>" . $block["data"]["text"] . "</li></ul>";
            case "quote":
                return "<blockquote><p>" . $block["data"]["text"] . "</p></blockquote>";
            case "table":
                $data = $block["data"];
                $withHeadings = $data["withHeadings"];
                $content = $data['content'];
                $table = "<table class='table table-danger table-striped table-bordered'>";
                foreach ($content as $key => $row) {
                    if ($key == 0 && $withHeadings) {
                        $table .= "<thead><tr>";
                        foreach ($row as $cell) {
                            $table .= "<th>" . $cell . "</th>";
                        }
                        $table .= "</tr></thead><tbody>";
                        continue;
                    }

                    $table .= "<tr>";
                    foreach ($row as $cell) {
                        $table .= "<td>" . $cell . "</td>";
                    }
                    $table .= "</tr>";
                }
                $table .= "</tbody></table>";
                return $table;
            default:
                return "<div>unknown block type: " . $block["type"] . "</div>";
        }
    }
}
