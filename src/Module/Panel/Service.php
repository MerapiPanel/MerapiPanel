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
                'name' => 'Dashboard',
                'link' => $this->box->module_site()->adminLink()
            ],
            [
                'name' => 'Pages',
                'link' => $this->box->module_site()->adminLink('pages')
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
