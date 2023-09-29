<?php

namespace MerapiPanel\Module\Pages;

use MerapiPanel\Core\Abstract\Module;
use PDO;

class Service extends Module
{


    public function createPage($title, $slug)
    {
        if (!isset($title) || empty($title)) {
            return [
                "code" => 400,
                "message" => "Title is required"
            ];
        }
        if (!isset($slug) || empty($slug)) {
            return [
                "code" => 400,
                "message" => "Slug is required"
            ];
        }

        $id = uniqid();
        $db = $this->getDatabase();
        $this->validateTable($db);

        $SQL = "INSERT INTO `pages` (`id`, `template_id`, `title`, `slug`) VALUES (:id, :template_id, :title, :slug)";
        return $db->runQuery($SQL, [
            "id" => $id,
            "template_id" => null,
            "title" => $title,
            "slug" => $slug
        ]);
    }



    public function fetchAll()
    {

        $db = $this->getDatabase();
        $this->validateTable($db);

        $SQL = "SELECT * FROM `pages`";
        $stack =  $db->runQuery($SQL)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($stack as $key => $value) {
            $stack[$key]['slug'] = $this->box->Module_Site()->createLink("/page/" . $value['slug']);
        }
        return $stack;
    }

    private function validateTable($db)
    {

        $SQL = "CREATE TABLE IF NOT EXISTS `pages` (
            `id` text(11) PRIMARY KEY,
            `template_id` varchar(11) NULL,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL
        )";
        $db->runQuery($SQL);
    }
}
