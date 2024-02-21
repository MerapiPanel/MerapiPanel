<?php

namespace MerapiPanel\Module\Article\Api;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;

class Admin extends Module {

    public function fetchAll() {
        return Box::module("article")->service()->fetchAll();
    }
}