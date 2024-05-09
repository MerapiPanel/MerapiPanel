<?php
namespace MerapiPanel\Module\Dashboard\Views\Admin;
use MerapiPanel\Box\Module\__Fragment;
class Api extends __Fragment {
    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module) {
        $this->module = $module;
    }

    function isAllowed($id) {
        return $this->module->getRoles()->isAllowed($id);
    }
}