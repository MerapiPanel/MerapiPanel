<?php
namespace MerapiPanel\Module\User\Views\Admin;

class Api
{

    public function fetchAll()
    {
        return $this->getBox()->Module_User()->fetchAll();
    }
}