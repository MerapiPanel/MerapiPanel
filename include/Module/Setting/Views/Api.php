<?php
namespace MerapiPanel\Module\Setting\Views;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Router;
use MerapiPanel\Views\View;

class Api extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {

        $this->module = $module;
    }




}