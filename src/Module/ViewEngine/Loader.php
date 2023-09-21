<?php

namespace MerapiPanel\Module\ViewEngine;

use Twig\Loader\FilesystemLoader;

class Loader extends FilesystemLoader
{

    public function __construct($paths = [])
    {

        parent::__construct($paths);

        $scopes = [
            "admin",
            "parts",
            "guest"
        ];

        $dir = realpath(__DIR__ . '/../');
        foreach (scandir($dir) as $file) {


            foreach ($scopes as $scope) {

                $finalFile = $dir . '/' . $file . "/html_" . $scope . '/';

                if (file_exists($finalFile)) {

                    $space = strtolower($scope . ">" . $file);

                    $this->addPath($finalFile, $space);
                }
            }
        }
    }
}
