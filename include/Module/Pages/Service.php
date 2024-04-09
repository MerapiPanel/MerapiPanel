<?php

namespace MerapiPanel\Module\Pages;

use MerapiPanel\Box\Module\__Fragment;
use PDO;

class Service extends __Fragment
{
    
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module) {

    }


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



    public function assignTemplate($page_id, $template_id)
    {

        $SQL = "UPDATE `pages` SET `template_id` = :template_id WHERE `id` = :page_id";
        $db = $this->getDatabase();

        return $db->runQuery($SQL, [
            "page_id" => $page_id,
            "template_id" => $template_id
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

            if(isset($value['template_id'])) {
                $stack[$key]['template'] = $this->box->Module_Template()->getTemplate($value['template_id']);
            }
        }
        return $stack;
    }



    public function getPageBySlug($slug)
    {

        $db = $this->getDatabase();
        $SQL = "SELECT * FROM `pages` WHERE `slug` = :slug";
        $result = $db->runQuery($SQL, [
            "slug" => $slug
        ]);

        return $result->fetch(PDO::FETCH_ASSOC);
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
