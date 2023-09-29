<?php

namespace MerapiPanel\Module\Pages\Api;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    function getAll()
    {

        return $this->getBox()->Module_Pages()->fetchAll();
    }
}
