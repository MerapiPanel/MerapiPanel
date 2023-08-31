<?php

namespace il4mb\Mpanel\Core\Template;

use Twig\Loader\FilesystemLoader;

class Loader extends FilesystemLoader {

    public function __construct() {
        parent::__construct(__DIR__ . "/../../template");
    }

}