<?php

namespace Mp\Module\Panel;

use Mp\Core\Abstract\Module;

class Service extends Module
{

    protected $box;

    protected $navs;

    public function setBox($box)
    {
        $this->box = $box;


        $this->navs = [
            [
                'priority' => 100,
                'name' => 'Dashboard',
                'link' => $this->box->module_site()->adminLink()
            ],
            [
                'priority' => 100,
                'name' => 'Pages',
                'link' => $this->box->module_site()->adminLink('pages')
            ],
            [
                'priority' => 100,
                'name' => 'Users',
                'link' => $this->box->module_site()->adminLink('users')
            ],
            [
                'priority' => 100,
                'name' => "Modules",
                'link' => $this->box->module_site()->adminLink('modules')
            ],
            [
                'priority' => 100,
                'name' => 'Settings',
                'link' => $this->box->module_site()->adminLink('settings'),
                "childs" => [
                    [
                        'priority' => 100,
                        'name' => 'Users',
                        'link' => $this->box->module_site()->adminLink('settings/users')
                    ]
                ]
            ]
        ];
    }

    public function getNavs()
    {
        return $this->navs;
    }



    public function getAuthedUsers()
    {

        $mod_user = $this->box->module_user();
        $user = $mod_user->getUserByEmail("admin@user.com");

        return $user;
    }
}
