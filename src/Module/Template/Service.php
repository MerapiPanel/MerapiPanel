<?php

namespace MerapiPanel\Module\Template;

use Exception;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\Database;
use PDO;

class Service extends Module
{


    public function getTemplate($id) 
    {
        $db = $this->getDatabase();
        $this->checkTable($db);

        $SQL = "SELECT * FROM `template` WHERE `id` = :id";
        $result = $db->runQuery($SQL, [
            'id' => $id
        ]);

        $template = $result->fetch(PDO::FETCH_ASSOC);
        $html = file_get_contents(__DIR__ . "/Contents/" . $id . "/index.html");
        $css  = file_get_contents(__DIR__ . "/Contents/" . $id . "/style.css");

        $template['html'] = $html;
        $template['css'] = $css;

        return $template;
    }


    function getAllTemplate()
    {
        $db = $this->getDatabase();
        $this->checkTable($db);

        $SQL = "SELECT * FROM `template`";
        $result = $db->runQuery($SQL);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }




    function saveTemplate($id, $name, $description, $html, $css)
    {

        $db = $this->getDatabase();
        $this->checkTable($db);

        $SQL = "INSERT INTO `template` (`id`, `name`, `description`) VALUES (:id, :name, :description)";

        if (!$db->runQuery($SQL, [
            'id' => $id,
            'name' => $name,
            'description' => $description
        ])) {

            throw new Exception("Error saving template", 500);
        }

        $save_path = __DIR__ . "/Contents/" . $id;
        if (!file_exists($save_path)) {
            mkdir($save_path);
        }
        file_put_contents($save_path . "/index.html", $html);
        file_put_contents($save_path . "/style.css", $css);

        return true;
    }



    function updateTemplate($id, $name, $description, $html, $css)
    {

        $db = $this->getDatabase();
        $this->checkTable($db);

        $SQL = "UPDATE `template` SET `name` = :name, `description` = :description WHERE `id` = :id";

        if (!$db->runQuery($SQL, [
            'id' => $id,
            'name' => $name,
            'description' => $description
        ])){

            throw new Exception("Error updating template", 500);
        } 

        $save_path = __DIR__ . "/Contents/" . $id;

        if (!file_exists($save_path)) {
            mkdir($save_path);

        }

        file_put_contents($save_path . "/index.html", $html);
        file_put_contents($save_path . "/style.css", $css);

        return true;
    }




    private function checkTable(Database $db)
    {

        $SQL = "CREATE TABLE IF NOT EXISTS `template` (
            `id` TEXT(15) PRIMARY KEY,
            `name` TEXT(100) NOT NULL,
            `description` TEXT NULL)";

        $db->runQuery($SQL);
    }
}
