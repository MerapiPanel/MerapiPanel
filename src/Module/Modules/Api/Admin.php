<?php

namespace MerapiPanel\Module\Modules\Api;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    public function ListModule()
    {
        return Box::module("modules")->getListModule();
    }

    public function getModuleLength()
    {

        return count($this->ListModule());
    }
}
