<?php

namespace MerapiPanel\Module\Panel;

use MerapiPanel\Core\Abstract\Module;

class Service extends Module
{

    protected $box;

    protected $ListMenu = [];

    public function setBox($box)
    {

        $this->box = $box;
        $this->ListMenu = [
            [
                'order' => 0,
                'name' => 'Dashboard',
                'icon' => 'fa-solid fa-house',
                'link' => $this->box->module_site()->adminLink()
            ]
        ];
    }



    function adminLink($path = "")
    {
        $AppConfig = $this->box->getConfig();
        return rtrim($AppConfig['admin'], "\\/") . "/" . ltrim($path, "\\/");
    }



    public function getMenu()
    {

        $listMenu = $this->ListMenu;
        usort($listMenu, function ($a, $b) {
            return $a["order"] - $b["order"];
        });

        $unique = [];
        foreach ($listMenu as $menu) {

            if ($this->isCurrent($menu['link'])) {
                $menu['active'] = true;
            }
            $unique[strtolower($menu['name'])] = $menu;
        }

        $grouped = $this->buildMenuHierarchy(array_values($unique));
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


    public function assignToParent(&$source, $item)
    {
    }



    public function addMenu($menu = [
        'order' => 100,
        'name' => '',
        'icon' => '',
        'link' => '',

    ])
    {

        if (!isset($menu['name'])) {
            throw new \Exception("The name of the menu is required");
        }
        if (!isset($menu['link'])) {
            throw new \Exception("The link of the menu is required");
        }

        if (!isset($menu['order'])) {
            $menu['order'] = count($this->ListMenu) + 1;
        }

        if (isset($menu['icon'])) {
            $icon = $menu['icon'];
            if (strpos($icon, 'fa-') !== false) {
                $menu['icon'] = '<i class="w-[14] ' . $icon . '"></i>';
            } elseif (strpos(trim($icon), '<svg') !== false) {
                // SVG code
                $icon  = str_replace('<svg', '<svg width="15" height="16" class="inline-block"', $icon);
                $menu['icon'] = $icon;
            }
        }
        $this->ListMenu[] = $menu;
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


    public function getBase()
    {
        return $this->box->getConfig()->get('admin');
    }


    public function getAuthedUsers()
    {

        $mod_user = $this->box->module_user();
        $user = $mod_user->getUserByEmail("admin@user.com");

        return $user;
    }


    private function isCurrent($link)
    {

        $request = $this->box->utility_http_request();
        $path = $request->getPath();

        return strtolower(preg_replace('/[^a-z]+/i', '', $link)) == strtolower(preg_replace('/[^a-z]+/i', '', $path));
    }
}
