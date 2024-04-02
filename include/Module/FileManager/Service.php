<?php

namespace MerapiPanel\Module\FileManager;

use MerapiPanel\Box\Module\__Fragment;
use Symfony\Component\Filesystem\Path;


class Service extends __Fragment
{

    protected string $root;
    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
        $this->module->props->root = Path::join($_ENV['__MP_CWD__'], 'content');
    }

    function hallo()
    {
        error_log("from service: " . $this->module->props->root);
    }

    public function getRoot()
    {
        $root = $_SERVER['DOCUMENT_ROOT'] . "/public";
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



    public function upload($file)
    {

        $root = $this->getRoot() . "/" . date('Y-m-w');
        if (!file_exists($root))
            mkdir($root);

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

        switch ($ext) {
            case 'png':
            case 'jpg':
            case 'jpeg':
                return [
                    "src" => "/public/filemanager/image_viewer/" . str_replace($_SERVER['DOCUMENT_ROOT'] . "/public/upload/", '', $file),
                    "scale" => "cover"
                ];

            default:
                return [
                    "src" => "/public/filemanager/image_viewer/" . str_replace($_SERVER['DOCUMENT_ROOT'] . "/public/upload/", '', $file) . "?icon=1",
                    "scale" => "scale-down"
                ];
        }
    }



    public function getIcon($file)
    {

        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if (empty($ext)) {
            if ($this->isDirectoryNotEmpty($file)) {
                return __DIR__ . "/assets/icon/folder-file.png";
            } else {
                return __DIR__ . "/assets/icon/folder-empty.png";
            }
        }

        switch ($ext) {
            case 'png':
            case 'jpg':
            case 'jpeg':
                $file = "image";
                break;
            case 'mp4':
            case 'avi':
            case 'mov':
            case 'wmv':
            case 'flv':
            case 'mkv':
            case 'webm':
            case 'm4v':
            case '3gp':
            case 'mpeg':
            case 'mpg':
                $file = "video";
                break;

            case 'mp3':
            case 'wav':
            case 'ogg':
            case 'aac':
            case 'wma':
            case 'flac':
            case 'amr':
                $file = "audio";
                break;

            case 'gif':
                $file = "image";
                break;

            case 'pdf':
                $file = "pdf";
                break;
            case 'doc':
            case 'docx':
                $file = "docs";
                break;
            case 'xls':
            case 'xlsx':
                $file = "sheet";
                break;

            case 'csv':
                $file = "sheet";
                break;

            case 'ppt':
            case 'pptx':
                $file = "powerpoint";
                break;

            case 'txt':
                $file = "alt";
                break;

            case 'zip':
            case 'rar':
                $file = 'zip';
                break;
            case 'php':
            case 'html':
            case 'js':
            case 'css':
            case 'py':
            case 'json':
            case 'sql':
            case 'xml':
            case 'yaml':
            case 'yml':
            case 'ini':
            case 'sh':
            case 'bat':
            case 'ps1':
            case 'md':
            case 'vue':
                $file = 'coding';
                break;

            default:
                $file = 'unknown';
        }

        return __DIR__ . "/assets/icon/$file.png";
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
