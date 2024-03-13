<?php

namespace MerapiPanel\Core\View;

use Twig\Loader\FilesystemLoader;

class Loader extends FilesystemLoader
{

    public function __construct($paths = [])
    {

        parent::__construct($paths);
       // $sections = ["admin", "parts", "guest"];

        $dir = realpath(__DIR__ . '/../../module');
        foreach (scandir($dir) as $file) {

            if(!is_dir($dir .'/'. $file)) continue;
            $base      = $dir . '/' . $file . '/views';
            if(!file_exists($base)) continue;
            $namespace = strtolower($file);

            $this->addPath($base, $namespace);
        }
    }
}
