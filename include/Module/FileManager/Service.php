<?php

namespace MerapiPanel\Module\FileManager;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Http\Request;
use Symfony\Component\Filesystem\Path;
use Throwable;


class Service extends __Fragment
{

    protected string $root;
    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
        $this->module->props->root = Path::join($_ENV['__MP_CWD__'], 'content');
    }


    public function getRoot()
    {
        $root = $_ENV['__MP_CWD__'] . "/content";
        if (!file_exists($root))
            mkdir($root);
        $root .= "/upload";
        if (!file_exists($root))
            mkdir($root);
        return $root;
    }



    public function getFolder($path = '')
    {

        $root = $this->getRoot();
        $path = rtrim($root, '/') . '/' . ltrim($path, '/');
        return $path;
    }



    public function getAllFile()
    {
        return $this->scanFolder($this->getRoot());
    }


    private function scanFolder($dir, &$stack = [])
    {

        foreach (scandir($dir) as $file) {

            if ($file == '.' || $file == '..')
                continue;

            $absolute_path = strtolower(str_replace('\\', '/', (rtrim($dir, '/') . '/' . $file)));

            if (is_dir($absolute_path) && !is_file($absolute_path)) {

                $this->scanFolder($absolute_path, $stack);
            } elseif (is_file($absolute_path)) {

                $server_root = strtolower(str_replace('\\', '/', strtolower($_SERVER['DOCUMENT_ROOT'])));
                $relative_path = str_replace($server_root, '', $absolute_path);

                $info = pathinfo($absolute_path);

                $file = [
                    'name' => $info['basename'],
                    'extension' => $info['extension'],
                    'size' => $info['size'] ?? filesize($absolute_path),
                    'path' => $relative_path,
                    'parent' => basename(str_replace("/" . basename($absolute_path), '', $absolute_path)),
                    "last_modified" => date('Y-m-d H:i:s', filemtime($absolute_path)),
                ];

                $stack[] = $file;
            }
        }

        return $stack;
    }







    

    


    function absoluteToRelativePath($absolute_path)
    {

        $server_root = strtolower(str_replace('\\', '/', strtolower($_SERVER['DOCUMENT_ROOT'])));
        $absolute_path = strtolower(str_replace('\\', '/', $absolute_path));

        return str_replace($server_root, '', $absolute_path);
    }





    function isDirectoryNotEmpty($dir)
    {
        if (!is_readable($dir))
            return null; // Check if directory is readable

        $handle = opendir($dir);
        if ($handle) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    closedir($handle);
                    return true; // Directory is not empty
                }
            }
            closedir($handle);
        }
        return false; // Directory is empty
    }
}
