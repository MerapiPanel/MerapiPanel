<?php
namespace MerapiPanel\Module\FileManager;

use MerapiPanel\Box\Module\__Fragment;

class Api extends __Fragment
{
    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }


    public function fetchFile($start, $limit)
    {
        $extensions = ["jpg", "png", "gif", "webp", "ico", "bmp", "mp4", "avi", "mov", "wmv", "flv", "mkv", "webm", "m4v", "3gp", "mpeg", "mpg"];
        $root = $_ENV['__MP_CWD__'] . "/content";
        $stack = [];
        $done = 0;

        // Open the directory handle
        if ($dh = opendir($root)) {
            while (false !== ($filename = readdir($dh))) {
                if ($done >= $start + $limit) {
                    break; // Stop if we have processed enough files
                }

                if ($filename == '.' || $filename == '..') {
                    continue; // Skip current and parent directory pointers
                }

                $filePath = $root . "/" . $filename;

                if (is_dir($filePath)) {
                    // Recursively fetch from nested directories
                    $this->fetchNested($filePath, $stack, $start, $done, $limit, $extensions);
                } elseif (in_array(pathinfo($filePath, PATHINFO_EXTENSION), $extensions)) {
                    // Process files with allowed extensions
                    if ($done >= $start) {
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
                    $done++; // Increment the count of processed files
                }
            }
            closedir($dh); // Close the directory handle
        }

        return [
            "total" => count(glob($root . "/*.{jpg,png,gif,webp,ico,bmp,mp4,avi,mov,wmv,flv,mkv,webm,m4v,3gp,mpeg,mpg}", GLOB_BRACE)),
            "items" => $stack
        ];
    }

    private function fetchNested($directory, &$stack, &$start, &$done, $limit, $extensions)
    {
        if ($dh = opendir($directory)) {
            while (false !== ($filename = readdir($dh))) {
                if ($done >= $start + $limit) {
                    break; // Stop if we have processed enough files
                }

                if ($filename == '.' || $filename == '..') {
                    continue; // Skip current and parent directory pointers
                }

                $filePath = $directory . "/" . $filename;

                if (is_dir($filePath)) {
                    // Recursively fetch from nested directories
                    $this->fetchNested($filePath, $stack, $start, $done, $limit, $extensions);
                } elseif (in_array(pathinfo($filePath, PATHINFO_EXTENSION), $extensions)) {
                    // Process files with allowed extensions
                    if ($done >= $start) {
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
                    $done++; // Increment the count of processed files
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



}