<?php

namespace MerapiPanel\Module\FileManager;

use MerapiPanel\Core\Abstract\Module;

class Service extends Module
{


    public function getRoot()
    {
        $root = $_SERVER['DOCUMENT_ROOT'] . "/public";
        if (!file_exists($root)) mkdir($root);
        $root .= "/upload";
        if (!file_exists($root)) mkdir($root);
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

            if ($file == '.' || $file == '..') continue;

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

                $stack[] =  $file;
            }
        }

        return $stack;
    }



    public function upload($file)
    {

        $root = $this->getRoot() . "/" . date('Y-m-w');
        if (!file_exists($root)) mkdir($root);

        $temp = $file['tmp_name'];
        $name = $file['name'];
        $path = $root . "/" . $name;

        if (move_uploaded_file($temp, $path)) {

            return $this->absoluteToRelativePath($path);
        }
    }


    function absoluteToRelativePath($absolute_path)
    {

        $server_root = strtolower(str_replace('\\', '/', strtolower($_SERVER['DOCUMENT_ROOT'])));
        $absolute_path = strtolower(str_replace('\\', '/', $absolute_path));

        return str_replace($server_root, '', $absolute_path);
    }



    function getIconName($file)
    {


        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if (empty($ext)) {
            return "fa-folder-closed";
        }
        switch ($ext) {
            case 'png':
            case 'jpg':
            case 'jpeg':
                return [
                    "src" => str_replace($_SERVER['DOCUMENT_ROOT'], '', $file),
                    "scale" => "cover"
                ];

            case 'pdf':
                return 'fa-file-pdf';
            case 'doc':
                return 'fa-file-word';
            case 'docx':
                return 'fa-file-word';
            case 'xls':
                return 'fa-file-excel';
            case 'xlsx':
                return 'fa-file-excel';
            case 'ppt':
                return 'fa-file-powerpoint';
            case 'pptx':
                return 'fa-file-powerpoint';
            case 'zip':
                return 'fa-file-archive';
            case 'rar':
                return 'fa-file-archive';
            default:
                return 'fa-file';
        }
    }
}
