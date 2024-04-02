<?php

namespace MerapiPanel\Module\Client;

use MerapiPanel\Box\Module\Entity\Module;
use Symfony\Component\Filesystem\Path;
use Twig\Loader\FilesystemLoader;

class ClientViewLoader extends FilesystemLoader
{

    public function __construct(Module $module, $paths = [])
    {
        parent::__construct($paths);

        $dirname = $module->props->cwd;
        if (file_exists($dirname)) {
            $module = Path::join($dirname, "Module");
            $views = Path::join($dirname, "Views");

            if (file_exists($views)) {
                $this->addPath($views);
            }

            foreach (scandir($module) as $file) {

                if (!is_dir($module . '/' . $file))
                    continue;
                $base = $module . '/' . $file . '/Views';
                if (!file_exists($base))
                    continue;

                $namespace = strtolower($file);
                $this->addPath($base, $namespace);
            }
        }

    }
}