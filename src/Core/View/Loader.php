<?php

namespace MerapiPanel\Core\View;

use Twig\Loader\FilesystemLoader;

class Loader extends FilesystemLoader
{

    public function __construct($paths = [])
    {

        parent::__construct($paths);
        $sections = ["admin", "parts", "guest"];

        $dir = realpath(__DIR__ . '/../../module');
        foreach (scandir($dir) as $file) {

            if(!is_dir($dir .'/'. $file)) continue;
            
            $this->addPath($dir . '/' . $file, "module::" . strtolower($file));

            foreach ($sections as $section) {

                $finalFile = $dir . '/' . $file . "/views/html_" . $section . '/';

                if (file_exists($finalFile)) {

                    $this->addPath($finalFile, "$section::" . strtolower($file));
                }
            }
        }
    }
}
