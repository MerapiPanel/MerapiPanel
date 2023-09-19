<?php

namespace Mp\Module\User\Api;

use Mp\Core\Abstract\Module;
use Mp\Module\User\Type;

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
}
