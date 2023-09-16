<?php

namespace Mp\Module\ViewEngine;

use Twig\Loader\FilesystemLoader;

class Loader extends FilesystemLoader
{

    public function __construct($paths = [])
    {

        parent::__construct($paths);

        $dir = realpath(__DIR__ . '/../');
        foreach (scandir($dir) as $file) {

            $this->addPath($dir . '/' . $file, $file);
        }
    }
}
