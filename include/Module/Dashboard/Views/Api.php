<?php
namespace MerapiPanel\Module\Dashboard\Views;
use MerapiPanel\Box\Module\__Fragment;
class Api extends __Fragment {
    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module) {
        $this->module = $module;
    }

    function getLogedinUser() {
        return $this->module->getLogedinUser();
    }
}