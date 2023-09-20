<?php

namespace MerapiQu\Module\User\Api;

use MerapiQu\Core\Abstract\Module;
use MerapiQu\Module\User\Type;

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
