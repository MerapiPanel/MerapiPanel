<?php
namespace MerapiPanel\Module\Website;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Database\DB;
use PDO;
use Symfony\Component\Filesystem\Path;


class Pages extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    function fetchAll()
    {

        $manifest_pages = [];
        foreach (glob(Path::join($this->module->path, "pages", "*.json")) as $file) {
            $data = json_decode(file_get_contents($file), true);
            if (!isset($data['route'], $data['components'], $data['styles'])) {
                continue;
            }

            foreach ($data as $key => $value) {
                if (is_string($value) && strpos($value, 'file:.') === 0) {
                    $data[$key] = file_get_contents(Path::join($this->module->path, "pages", substr($value, 5)));
                }
            }
            if (!isset($data['title'])) {
                $data['title'] = basename($file, ".json");
            }
            $data['name'] = basename($file, ".json");
            $manifest_pages[] = $data;
        }


        $SQL = "SELECT A.*, B.variables, B.header FROM pages A LEFT JOIN pages_metadata B ON A.id = B.page_id ORDER BY A.`post_date` DESC";
        $pages = DB::instance()->query($SQL)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($manifest_pages as $page) {

            $find = array_search($page['name'], array_column($pages, 'name'));
            if ($find !== false) {
                $page = array_merge($page, $pages[$find]);
                $pages[$find] = $page;
            } else {

                $pages[] = $page;
            }
        }


        foreach ($pages as &$page) {
            if (isset($page['components']) && is_string($page['components'])) {
                $page['components'] = json_decode($page['components'], true);

            }
            if (isset($page['variables']) && is_string($page['variables'])) {
                $page['variables'] = json_decode($page['variables'], true);
            }
        }


        return $pages;
    }



    function fetchOne($id)
    {
        $SQL = "SELECT * FROM pages WHERE id = ?";
        $stmt = DB::instance()->prepare($SQL);
        $stmt->execute([$id]);
        $template = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($template['components'])) {
            $template['components'] = json_decode($template['components'], true);
        }
        return $template;
    }



    function add($name, $title, $description, $route, $components = [], $styles = "")
    {
        if (!$this->module->getRoles()->isAllowed(0)) {
            throw new \Exception("Access denied");
        }

        $SQL = "INSERT INTO pages (name, title, description, route, components, styles) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = DB::instance()->prepare($SQL);
        if ($stmt->execute([$name, $title, $description, $route, (is_string($components) ? $components : json_encode($components)), $styles])) {
            return [
                "id" => DB::instance()->lastInsertId(),
                "title" => $title,
                "description" => $description,
                "route" => $route,
                "components" => $components,
                "styles" => $styles
            ];
        }
        throw new \Exception("Failed to insert page");
    }


    function save($id, $name, $title, $route, $description, $components = [], $styles = "", $variables = "", $header = "")
    {
        if (!$this->module->getRoles()->isAllowed(0)) {
            throw new \Exception("Access denied");
        }

        if (empty($id)) {
            return $this->add($name, $title, $description, $route, $components, $styles);
        }

        $SQL = "REPLACE INTO pages (id, name, title, description, route, components, styles) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = DB::instance()->prepare($SQL);
        if ($stmt->execute([$id, $name, $title, $description, $route, (is_string($components) ? $components : json_encode($components)), $styles])) {

            if (!empty($variables) || !empty($header)) {
                $SQL = "REPLACE INTO pages_metadata (page_id, variables, header) VALUES (?, ?, ?)";
                $stmt = DB::instance()->prepare($SQL);
                if (empty($header) || $header == null || $header == "null") {
                    $header = "";
                }
                if (empty($variables) || $variables == null || $variables == "null") {
                    $variables = "[]";
                }
                $header = is_string($header) ? $header : json_encode($header);
                $variables = is_string($variables) ? $variables : json_encode($variables);
                $stmt->execute([$id, $variables, $header]);

            } else if (empty(trim($variables)) && empty(trim($header))) {
                $SQL = "DELETE FROM pages_metadata WHERE page_id = ?";
                $stmt = DB::instance()->prepare($SQL);
                $stmt->execute([$id]);
            }

            return [
                "id" => $id,
                "name" => $name,
                "title" => $title,
                "description" => $description,
                "route" => $route,
                "components" => $components,
                "styles" => $styles
            ];
        }
        throw new \Exception("Failed to update page");

    }


    function delete($id)
    {
        if (!$this->module->getRoles()->isAllowed(0)) {
            throw new \Exception("Access denied");
        }
        $SQL = "DELETE FROM pages WHERE id = ?";
        $stmt = DB::instance()->prepare($SQL);
        if ($stmt->execute([$id])) {
            return true;
        }
        throw new \Exception("Failed to delete page");
    }

}