<?php

namespace MerapiPanel\Module\User\Api;

use MerapiPanel\Core\Abstract\Module;

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
