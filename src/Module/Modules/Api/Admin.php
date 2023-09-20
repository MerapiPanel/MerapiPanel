<?php

namespace MerapiQu\Module\Modules\Api;

use MerapiQu\Core\Abstract\Module;

class Admin extends Module
{

    public function ListModule()
    {
        return $this->service()->getListModule();
    }
}
