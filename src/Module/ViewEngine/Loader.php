<?php

namespace Mp\Module\ViewEngine;

use Twig\Loader\FilesystemLoader;

class Loader extends FilesystemLoader {

    public function __construct() {
        parent::__construct(__DIR__ . "/../../templates");
    }

}