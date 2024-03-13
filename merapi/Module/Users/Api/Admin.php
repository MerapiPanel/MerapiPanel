<?php

namespace MerapiPanel\Module\Users\Api;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Module\Users\Type;

class Admin extends Module
{

    protected $box;

    public function setBox($box)
    {
        $this->box = $box;
    }

    

    function AllUser()
    {

        return $this->service()->getAllUser();
    }
}
