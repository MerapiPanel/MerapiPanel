<?php

namespace MerapiQu\Module\Panel\Api;

use MerapiQu\Core\Abstract\Module;

class Admin extends Module
{

    public function Base()
    {
        return $this->service()->getBase();
    }
}
