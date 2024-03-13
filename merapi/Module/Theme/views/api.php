<?php
namespace MerapiPanel\Module\Theme\views;

use MerapiPanel\Box;

class api
{

    public function fetchAll()
    {
        return Box::module("theme")->service()->fetchAll();
    }

    public function active()
    {
        return Box::module("theme")->service()->getActive();
    }
}
