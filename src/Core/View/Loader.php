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

            // $this->addPath($dir . '/' . $file, strtolower($file));

            foreach ($sections as $section) {

                $finalFile = $dir . '/' . $file . "/views/html_" . $section . '/';

                if (file_exists($finalFile)) {

                    $this->addPath($finalFile, "$section::" . strtolower($file));
                }
            }
        }
    }
}
