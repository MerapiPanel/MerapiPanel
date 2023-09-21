<?php

namespace MerapiPanel\Module\Modules\Api;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    public function ListModule()
    {
        return $this->service()->getListModule();
    }
}
