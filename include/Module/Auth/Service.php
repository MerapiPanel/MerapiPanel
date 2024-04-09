<?php

namespace MerapiPanel\Module\Auth;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;

class Service extends __Fragment
{

    protected $module;
    function onCreate(Box\Module\Entity\Module $module)
    {

        $this->module = $module;
    }


}
