<?php

namespace MerapiQu\Module\Panel;

use MerapiQu\Core\Abstract\Module;

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
            ],
            [
                'order' => 100,
                'name' => "Modules",
                'link' => $this->box->module_site()->adminLink('modules')
            ],
        ];
    }

    public function getNavs()
    {

        $listMenu = $this->ListMenu;
        $indexed = [];

        usort($listMenu, function ($a, $b) {
            return $a["order"] - $b["order"];
        });

        foreach ($listMenu as $menu) {
            $indexed[$menu['name']] = $menu;
        }

        foreach ($listMenu as $menu) 
        {

            if (isset($menu['parent']) && $indexed[$menu['parent']]) 
            {

                if (!isset($indexed[$menu['parent']]['childs'])) 
                    $indexed[$menu['parent']]['childs'] = [];

                $indexed[$menu['parent']]['childs'][] = $menu;

                if (isset($indexed[$menu['name']])) 
                {
                    unset($indexed[$menu['name']]);
                }
            }
        }

        return array_values($indexed);
    }



    public function addNav($nav = [
        'order' => 100,
        'name' => '',
        'link' => '',

    ])
    {
        $this->ListMenu[] = $nav;
    }



    public function getAuthedUsers()
    {

        $mod_user = $this->box->module_user();
        $user = $mod_user->getUserByEmail("admin@user.com");

        return $user;
    }
}
