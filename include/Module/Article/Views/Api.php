<?php
namespace MerapiPanel\Module\Article\Views;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;

class Api extends __Fragment
{

    protected $module;
    function onCreate(Module $module)
    {
        $this->module = $module;
    }

    function fetch($columns = ["id", "title", "content", "status", "post_date", "update_date"], $id = 1)
    {
        return $this->module->fetch($columns, $id);
    }

    function fetchAll()
    {
        return $this->module->fetchAll();
    }
}