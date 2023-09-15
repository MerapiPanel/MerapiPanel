<?php

namespace Mp\Core\View;

use Twig\Loader\FilesystemLoader;

class Loader extends FilesystemLoader {

    public function __construct() {
        parent::__construct(__DIR__ . "/../../View");
    }

}