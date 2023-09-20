<?php

namespace MerapiQu\Module\Panel;

use MerapiQu\Core\Abstract\Module;
use MerapiQu\Utility\Http\Request;

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

    public function getMenu()
    {



        $listMenu = $this->ListMenu;
        $indexed = [];

        usort($listMenu, function ($a, $b) {
            return $a["order"] - $b["order"];
        });

        foreach ($listMenu as $menu) {
            if ($this->isCurrent($menu['link'])) {
                $menu['active'] = true;
            }

            $indexed[$menu['name']] = $menu;
        }

        foreach ($listMenu as $menu) {

            if (isset($menu['parent']) && $indexed[$menu['parent']]) {

                if (!isset($indexed[$menu['parent']]['childs']))
                    $indexed[$menu['parent']]['childs'] = [];

                if ($this->isCurrent($menu['link'])) 
                {
                    $menu['active'] = true;
                }

                $indexed[$menu['parent']]['childs'][] = $menu;

                if (isset($indexed[$menu['name']])) {
                    unset($indexed[$menu['name']]);
                }
            }
        }

        return array_values($indexed);
    }



    public function addMenu($menu = [
        'order' => 100,
        'name' => '',
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

        $this->ListMenu[] = $menu;
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
