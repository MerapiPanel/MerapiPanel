<?php
namespace MerapiPanel\Module\FileManager;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Http\Request;
use Symfony\Component\Filesystem\Path;
use Throwable;

class Api extends __Fragment
{
    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }

    // Function to count files recursively with specific extensions
    private function countFilesByExtensions($directory, $extensions)
    {
        $total = 0;

        // Get all items (files and directories) in the current directory
        $items = glob(rtrim($directory, '/') . '/*');

        if ($items === false) {
            return 0; // If no items found, return 0
        }

        foreach ($items as $item) {
            if (is_file($item)) {
                // Check if the file has one of the specified extensions
                $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                if (in_array($extension, $extensions)) {
                    $total++; // Increment count for valid file
                }
            } elseif (is_dir($item)) {
                // Recursively count files in subdirectory
                $total += $this->countFilesByExtensions($item, $extensions);
            }
        }

        return $total;
    }

    public function fetchFolder()
    {

        function nested($dir)
        {
            $stack = [];
            foreach (glob($dir . "/*", GLOB_ONLYDIR) as $file) {
                $stack[] = [
                    "name" => basename($file),
                    "children" => (is_dir($file)) ? nested($file) : [],
                ];
            }

            return $stack;
        }

        return nested($_ENV['__MP_CWD__'] . "/content");
    }

    public function fetchFile()
    {
        $extensions = ["jpg", "png", "gif", "webp", "ico", "bmp", "mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"];
        $root = $_ENV['__MP_CWD__'] . "/content";
        $stack = [];
        $done = 0;

        // Open the directory handle
        if ($dh = opendir($root)) {
            while (false !== ($filename = readdir($dh))) {

                if ($filename == '.' || $filename == '..') {
                    continue; // Skip current and parent directory pointers
                }

                $filePath = $root . "/" . $filename;

                if (is_dir($filePath)) {
                    // Recursively fetch from nested directories
                    $this->fetchNested($filePath, $stack, $extensions);
                } elseif (in_array(pathinfo($filePath, PATHINFO_EXTENSION), $extensions)) {
                    // Process files with allowed extensions

                    // Only add files starting from the 'start' index
                    $stack[] = [
                        "name" => $filename,
                        "path" => $this->absoluteToRelativePath($filePath),
                        "type" => in_array(pathinfo($filePath, PATHINFO_EXTENSION), ["jpg", "png", "gif", "webp", "ico", "bmp"])
                            ? "image"
                            : (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ["mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"])
                                ? "video"
                                : "file"),
                    ];
                }
            }
            closedir($dh); // Close the directory handle
        }

        return [
            "total" => $this->countFilesByExtensions($root, $extensions),
            "items" => $stack
        ];
    }

    private function fetchNested($directory, &$stack, $extensions)
    {
        if ($dh = opendir($directory)) {
            while (false !== ($filename = readdir($dh))) {

                if ($filename == '.' || $filename == '..') {
                    continue; // Skip current and parent directory pointers
                }

                $filePath = $directory . "/" . $filename;

                if (is_dir($filePath)) {
                    // Recursively fetch from nested directories
                    $this->fetchNested($filePath, $stack, $extensions);
                } elseif (in_array(pathinfo($filePath, PATHINFO_EXTENSION), $extensions)) {
                    // Process files with allowed extensions

                    $stack[] = [
                        "name" => $filename,
                        "path" => $this->absoluteToRelativePath($filePath),
                        "type" => in_array(pathinfo($filePath, PATHINFO_EXTENSION), ["jpg", "png", "gif", "webp", "ico", "bmp"])
                            ? "image"
                            : (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ["mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"])
                                ? "video"
                                : "file"),
                    ];

                }
            }
            closedir($dh); // Close the directory handle
        }
    }


    private function absoluteToRelativePath($absolute_path)
    {

        $server_root = strtolower(str_replace('\\', '/', strtolower($_SERVER['DOCUMENT_ROOT'])));
        $absolute_path = strtolower(str_replace('\\', '/', $absolute_path));

        return str_replace($server_root, '', $absolute_path);

    }






    public function upload()
    {

        try {


            $files = $_FILES['files'];
            $request = Request::getInstance();


            if (!empty($request->parent())) {
                $root = Path::join($_ENV['__MP_CWD__'], "content", $request->parent());
            } else {

                $root = $_ENV['__MP_CWD__'] . "/content";
                if (!file_exists($root))
                    mkdir($root);
                $root .= "/upload";
                if (!file_exists($root))
                    mkdir($root);

                $root .= "/" . date('Y-m-w');
                if (!file_exists($root)) {
                    mkdir($root);
                }
            }

            $uploaded = [];

            for ($i = 0; $i < count($files['name']); $i++) {
                $temp = $files['tmp_name'][$i];
                $name = $files['name'][$i];
                $path = $root . "/" . $name;

                if (move_uploaded_file($temp, $path)) {

                    $uploaded[] = [
                        "name" => $name,
                        "status" => "success",
                        "src" => $this->absoluteToRelativePath($path),
                        "type" => in_array(pathinfo($path, PATHINFO_EXTENSION), ["img", "png", "jpg", "jpeg", "svg", "gif", "webp", "ico", "bmp"]) ? "image" : (in_array(pathinfo($path, PATHINFO_EXTENSION), ["mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"]) ? "video" : "file"),
                    ];
                } else {
                    $uploaded[] = [
                        "status" => "failed",
                    ];
                }
            }

            if (count($uploaded) <= 0) {
                throw new \Exception("Upload failed", 401);
            }

            return $uploaded;

        } catch (Throwable $e) {
            throw $e;
        }
    }









    function copy($files, $parent = null, $target = null)
    {

        function xcopy($src, $dest)
        {
            if (!is_dir($dest)) {
                mkdir($dest);
            }
            foreach (scandir($src) as $file) {
                if (!is_readable($src . '/' . $file))
                    continue;
                if (is_dir($src . '/' . $file) && ($file != '.') && ($file != '..')) {
                    mkdir($dest . '/' . $file);
                    xcopy($src . '/' . $file, $dest . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dest . '/' . $file);
                }
            }
        }

        if (is_string($files)) {
            $files = [$files];
        }
        foreach ($files as $file) {

            $source = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($parent) ? $parent : ""), $file);
            $target = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($target) ? $target : ""), $file);

            if (preg_replace("/[^a-zA-Z0-9-_]/", "", strtolower(Path::canonicalize($target . "/.."))) == preg_replace("/[^a-zA-Z0-9-_]/", "", strtolower($source))) {
                throw new \Exception("Target directory cannot be in the source directory");
            }

            if (is_file($source)) {
                copy($source, $target);
            }
            if (is_dir($source)) {
                xcopy($source, $target);
            }
        }
    }


    function move($files, $parent = null, $target = null)
    {

        function xmove($src, $dest)
        {

            if (!is_dir($dest)) {
                mkdir($dest);
            }

            foreach (scandir($src) as $file) {
                if (!is_readable($src . '/' . $file) || $file == '.' || $file == '..')
                    continue;
                if (is_dir($src . '/' . $file) && ($file != '.') && ($file != '..')) {
                    mkdir($dest . '/' . $file);
                    xmove($src . '/' . $file, $dest . '/' . $file);
                } else {
                    rename($src . '/' . $file, $dest . '/' . $file);
                }
            }
            rmdir($src);
        }

        if (is_string($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            $source = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($parent) ? $parent : ""), $file);
            $target = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($target) ? $target : ""), $file);
            if (preg_replace("/[^a-zA-Z0-9-_]/", "", strtolower(Path::canonicalize($target . "/.."))) == preg_replace("/[^a-zA-Z0-9-_]/", "", strtolower($source))) {
                throw new \Exception("Target directory cannot be in the source directory");
            }
            if (is_file($source)) {
                rename($source, $target);
            }
            if (is_dir($source)) {
                xmove($source, $target);
            }
        }
    }


    function rename($old_name, $new_name, $parent = null)
    {

        if (empty($old_name) || empty($new_name)) {
            throw new \Exception("Invalid parameters");
        }
        $source = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($parent) ? $parent : ""), $old_name);
        $target = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($parent) ? $parent : ""), $new_name);

        if (is_file($source)) {
            // check extension
            preg_match("/\.(.*)$/", $new_name, $matches);
            if (empty($matches[1])) {
                $old_extension = pathinfo($old_name, PATHINFO_EXTENSION);
                $target .= "." . $old_extension;
            }
        }

        if (rename($source, $target)) {
            return true;
        }
        throw new \Exception("Failed to rename file");
    }


    function delete($files = [], $parent = null)
    {
        if (is_string($files)) {
            $files = [$files];
        }
        $status = [];
        foreach ($files as $file) {
            $source = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($parent) ? $parent : ""), $file);
            if (is_file($source)) {
                if (unlink($source)) {
                    $status[$file] = true;
                }
            }
            if (is_dir($source)) {

                function rrmdir($dir)
                {
                    foreach (scandir($dir) as $file) {
                        if ('.' === $file || '..' === $file)
                            continue;
                        if (is_dir($dir . '/' . $file))
                            rrmdir($dir . '/' . $file);
                        else
                            unlink($dir . '/' . $file);
                    }
                    return rmdir($dir);
                }

                if (rrmdir($source)) {
                    $status[$file] = true;
                }

            }
        }

        if (count($status) > 0) {
            return $status;
        }


        throw new \Exception("Failed to delete file");
    }


    function newFolder($name, $parent = null)
    {

        $source = Path::join($_ENV['__MP_CWD__'], 'content', (!empty($parent) ? $parent : ""), $name);
        if (is_dir($source)) {
            throw new \Exception("Folder already exists");
        }

        if (mkdir($source)) {
            return true;
        }

        throw new \Exception("Failed to create folder");
    }
}