<?php

namespace MerapiPanel\Module\FileManager\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\AES;
use MerapiPanel\Utility\Http\Request;

class Guest extends Module
{


    public function register($router)
    {
        $router->get("/public/filemanager/image_viewer/{path}", "imageViewer", self::class);
        $router->get(Box::module("FileManager")->service("Assets")->getProperty("routeLink"), "assetsLoader", self::class);
    }



    public function imageViewer(Request $req)
    {

        $path = urldecode($req->path);
        $file = $_SERVER['DOCUMENT_ROOT'] . '/public/upload' . (!empty($path) ? '/' . ltrim($path, "\\/") : "");
        $destination = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/.resize';
        if (!file_exists($destination)) {
            mkdir($destination);
        }


        if (!file_exists($file)) {
            return [
                "code" => 404,
                "message" => "File not found "
            ];
        }

        if (!str_contains(mime_content_type($file), 'image')) {

            if ($req->icon() != 1) {
                return [
                    "code" => 400,
                    "message" => "File is not an image"
                ];
            } else {

                header("Content-Type: image/png");
                return file_get_contents($this->service()->getIcon($file));
            }
        }

        $maxWidth = 200;
        $maxHeight = 200;
        $targetDirectory = preg_replace('/[^a-z0-9]+/', '', dirname(str_replace($_SERVER['DOCUMENT_ROOT'] . "/public/upload/", "", $file)));
        $targetFileName = ($targetDirectory ? $targetDirectory . "_" : "") . basename($file, "." . strtolower(pathinfo($file, PATHINFO_EXTENSION))) . "-$maxWidth" . 'x' . "$maxHeight." . strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $destinationFile = $destination . '/' . $targetFileName;

        ob_start();
        if (!file_exists($destinationFile)) {
            $this->resizeImage($file, $destinationFile, $maxWidth, $maxHeight);
        }
        if (dirname($destinationFile) == dirname($file)) {
            echo file_get_contents($file);
        } else {
            echo file_get_contents($destinationFile);
        }
        $output = ob_get_clean();

        header("Content-Type: image/webp");

        return $output;
    }




    function resizeImage($sourcePath, $destinationPath, $maxWidth, $maxHeight)
    {
        // Get original image dimensions
        list($originalWidth, $originalHeight) = getimagesize($sourcePath);

        // Calculate aspect ratio
        $ratio = $originalWidth / $originalHeight;

        // Determine new dimensions based on max width and height
        if ($originalWidth > $originalHeight) {
            // Landscape orientation
            $newWidth = $maxWidth;
            $newHeight = $newWidth / $ratio;
            if ($newHeight > $maxHeight) {
                $newHeight = $maxHeight;
                $newWidth = $newHeight * $ratio;
            }
        } else {
            // Portrait or square orientation
            $newHeight = $maxHeight;
            $newWidth = $newHeight * $ratio;
            if ($newWidth > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = $newWidth / $ratio;
            }
        }

        // Load the source image
        $sourceImage = imagecreatefromstring(file_get_contents($sourcePath));
        if (!$sourceImage) {
            return false; // Unable to create image
        }

        // Create a new true color image with the new dimensions
        $destinationImage = imagecreatetruecolor($newWidth, $newHeight);

        // Copy and resize the original image into the new image
        imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        // Save the resized image to the destination path
        switch (exif_imagetype($sourcePath)) {
            case IMAGETYPE_JPEG:
                imagewebp($destinationImage, $destinationPath, 75);
            case IMAGETYPE_PNG:
                imagecolortransparent($destinationImage, imagecolorallocate($destinationImage, 0, 0, 0));
                imagealphablending($destinationImage, false);
                imagesavealpha($destinationImage, true);
                // Copy and resize the original image into the new image
                imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
                imagewebp($destinationImage, $destinationPath, 75);
                break;
            case IMAGETYPE_GIF:
                imagegif($destinationImage, $destinationPath);
                break;
            default:
                return false; // Unsupported image type
        }

        // Free up memory
        imagedestroy($sourceImage);
        imagedestroy($destinationImage);

        return true;
    }




    function assetsLoader($req)
    {
        return Box::module("FileManager")->service("Assets")->getAssset($req);
    }
}
