<?php

namespace MerapiPanel\Module\Panel;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Http\Request;

class Service extends __Fragment
{
    protected $ListMenu = [];
    protected $module;


    function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function getMenu()
    {

        $listMenu = $this->ListMenu;
        usort($listMenu, function ($a, $b) {
            return $a["order"] - $b["order"];
        });

        foreach ($listMenu as $key => $menu) {

            if (isset($menu['link']) && $this->isCurrent($menu['link'])) {
                $listMenu[$key]['active'] = true;
            }
        }

        $grouped = $this->buildMenuHierarchy(array_values($listMenu));
        return $grouped;
    }


    function buildMenuHierarchy($items, $parentId = null): array
    {

        $menu = [];

        foreach ($items as $key => $item) {

            $parent = isset($item['parent']) && !empty($item['parent']) ? strtolower($item['parent']) : null;
            // Check if the current item has a "parent" key that matches the provided $parentId.
            if ($parent === $parentId) {
                // If it does, recursively call the function to find its children.
                $childItems = $this->buildMenuHierarchy($items, strtolower($item['name']));

                // If the current item has children, add them as the "children" attribute.
                if (!empty($childItems)) {
                    $item['childs'] = $childItems;
                }

                // Add the current item to the menu.
                $menu[] = $item;
            }
        }

        if ($parentId == null) {
            return $menu;
        }

        return array_values($menu);
    }



    public function addMenu(
        $menu = [
            'order' => 100,
            'name' => '',
            'icon' => '',
            'link' => '',
        ]
    ) {


        if (empty($menu['name'])) {
            throw new \Exception("The name of the menu is required");
        }

        $menu['order'] = $menu['order'] ?? count($this->ListMenu) + 1;

        if (!empty($menu['icon'])) {
            $icon = $menu['icon'];

            if (str_contains($icon, "<svg")) {
                $menu['icon'] = $this->reBuildSvgIcon($icon);
            } else if (preg_match('/\bclass=["\'][^"\']*?\bfa-\w+\b[^"\']*?["\']/', $icon, $matches)) {
                $menu['icon'] = $icon;
            } else if (str_contains($icon, 'fa-')) {
                $menu['icon'] = "<i class='$icon'></i>";
            }
        }

        $childrens = [];

        if (!empty($menu['childs'])) {
            $childItems = $menu['childs'];
            assert(is_array($childItems));

            $childrens = array_merge($childrens, array_map(function ($item) use ($menu) {
                $item['parent'] = $menu['name'];
                return $item;
            }, $childItems));
        }
        if (!empty($menu['children'])) {
            $childItems = $menu['children'];
            assert(is_array($childItems));

            $childrens = array_merge($childrens, array_map(function ($item) use ($menu) {
                $item['parent'] = $menu['name'];
                return $item;
            }, $childItems));
        }
        foreach ($childrens as $child) {
            $this->addMenu($child);
        }

        $exist = array_search($menu['name'], array_column($this->ListMenu, 'name'));
        if ($exist !== false && strtolower($this->ListMenu[$exist]['parent'] ?? '') === strtolower($menu['parent'] ?? '')) {
            $this->ListMenu[$exist] = array_merge($this->ListMenu[$exist], $menu);
        } else {
            $this->ListMenu[] = $menu;
        }
    }





    function fullPathToRelativePath($fullPath, $basePath)
    {
        // Normalize directory separators and remove trailing slashes
        $fullPath = rtrim(str_replace('\\', '/', $fullPath), '/');
        $basePath = rtrim(str_replace('\\', '/', $basePath), '/');

        // Split the paths into arrays
        $fullPathArray = explode('/', $fullPath);
        $basePathArray = explode('/', $basePath);

        // Find the first differing element
        while (count($basePathArray) && $fullPathArray[0] == $basePathArray[0]) {
            array_shift($fullPathArray);
            array_shift($basePathArray);
        }

        // For each remaining element in the base path, prepend '../'
        return str_repeat('../', count($basePathArray)) . implode('/', $fullPathArray);
    }



    private function reBuildSvgIcon($xml)
    {
        $_xml = null;

        preg_match('/viewBox="([^"]+)"/i', $xml, $matches);

        if (isset($matches[1])) {
            $viewBox = $matches[1];
            $_xml = preg_replace('/\<svg[^>]+\>/', "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' fill='currentColor' viewBox='$viewBox'>", $xml);
        }
        return $_xml;
    }


    private function isCurrent($link)
    {

        $request = Request::getInstance();
        $path = $request->getPath();

        return strtolower(preg_replace('/[^a-z]+/i', '', $link)) == strtolower(preg_replace('/[^a-z]+/i', '', $path));
    }

}
