<?php

namespace MerapiPanel\Module\Article\Api;
use MerapiPanel\Box;

class Category
{

    public function fetchAll()
    {

        $data = Box::module("article")->service("category")->fetchAll();

        return $data;

    }

}