<?php

namespace MerapiPanel\Module\Users\Api;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Module\Users\Type;

class Admin extends Module
{

    protected Type $user;
    protected $box;

    public function setBox($box)
    {

        $this->box = $box;
        $this->user = $this->service()->getLogedinUser();
    }

    function getName()
    {
        return $this->user->getName();
    }

    function AllUser()
    {

        return $this->service()->getAllUser();
    }
}
