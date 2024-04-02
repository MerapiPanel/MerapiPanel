<?php

namespace MerapiPanel\Views;

use Twig\Loader\FilesystemLoader;

class Loader extends FilesystemLoader
{

    public function __construct($paths = [])
    {

        parent::__construct($paths);

        $dir = $_ENV["__MP_APP__"] . "/Module";
        foreach (scandir($dir) as $file) {

            if(!is_dir($dir .'/'. $file)) continue;
            $base      = $dir . '/' . $file . '/Views';
            if(!file_exists($base)) continue;
            $namespace = strtolower($file);
            $this->addPath($base, $namespace);
        }
    }
}
