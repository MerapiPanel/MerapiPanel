<?php

namespace MerapiPanel\Module\FileManager\Controller;

use Exception;
use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Util;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;

class Admin extends __Fragment
{

    protected $module;

    function onCreate(Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }

    public function register()
    {

        $panel = Box::module("Panel");
        $script = <<<HTML
        <script>
            __.FileManager.endpoints = {
                fetch: "{{ '/api/FileManager/fetch' | access_path }}",
                upload: "{{ '/api/FileManager/uploadChunk' | access_path }}",
                uploadInfo: "{{ '/api/FileManager/uploadInfo' | access_path }}",
                delete: "{{ '/api/FileManager/delete' | access_path }}",
                rename: "{{ '/api/FileManager/rename' | access_path }}",
                newFolder: "{{ '/api/FileManager/newFolder' | access_path }}",
            }
        </script>
        HTML;
        $panel->Scripts->add("filemanager-opts", $script);

    }



    public function uploadFront()
    {
        return View::render("upload.html.twig");
    }

    public function index($req)
    {

        return View::render("index.html.twig");
    }


    public function fetchEndpoint()
    {

        $panel = Box::module('Panel');

        return [
            'code' => 200,
            'data' => [
                'assets' => $panel->adminLink('filemanager/fetchJson'),
                'uploads' => $panel->adminLink('filemanager/upload')
            ],
            "message" => "Ok"
        ];
    }

    // public function upload(Request $request)
    // {

    //     if (!isset($_FILES['files'])) {
    //         return [
    //             'code' => 400,
    //             'message' => 'Error invalid parameter',
    //         ];
    //     }

    //     $files = $_FILES['files'];

    //     $error_stack = [];
    //     $success_stack = [];
    //     for ($x = 0; $x < count($files['name']); $x++) {

    //         $file = [];
    //         $file['name'] = $files['name'][$x];
    //         $file['tmp_name'] = $files['tmp_name'][$x];


    //         $upload = $this->module->upload($file);

    //         if ($upload) {

    //             $folder = str_replace("/" . basename($upload), "", $upload);
    //             $ext = pathinfo($upload, PATHINFO_EXTENSION);
    //             switch ($ext) {
    //                 case 'jpg':
    //                 case 'jpeg':
    //                 case 'png':
    //                 case 'gif':
    //                 case 'bmp':
    //                 case 'ico':
    //                 case 'webp':
    //                 case 'svg':
    //                 case 'tiff':
    //                 case 'tif':
    //                 case 'tga':
    //                     $file['type'] = 'image';
    //                     break;
    //                 case 'mp3':
    //                 case 'wav':
    //                 case 'wma':
    //                 case 'ogg':
    //                 case 'flac':
    //                 case 'm4a':
    //                 case 'aac':
    //                 case 'm4b':
    //                 case 'm4p':
    //                     $file['type'] = 'audio';
    //                     break;
    //                 case 'mp4':
    //                 case 'mkv':
    //                 case 'avi':
    //                 case 'mov':
    //                 case 'flv':
    //                 case 'wmv':
    //                 case 'm4v':
    //                 case 'mpg':
    //                 case 'mpeg':
    //                 case 'ogv':
    //                 case 'webm':
    //                 case '3gp':
    //                 case '3g2':
    //                     $file['type'] = 'video';
    //                     break;
    //                 case 'pdf':
    //                 case 'doc':
    //                 case 'docx':
    //                 case 'xls':
    //                 case 'xlsx':
    //                 case 'ppt':
    //                 case 'pptx':
    //                 case 'txt':
    //                 case 'odt':
    //                 case 'ods':
    //                 case 'odp':
    //                 case 'odg':
    //                 case 'odf':
    //                     $file['type'] = 'document';
    //                     break;
    //                 default:
    //                     $file['type'] = 'application/octet-stream';
    //             }
    //             $success_stack[] = [
    //                 'src' => $upload,
    //                 'category' => basename($folder),
    //                 'type' => $file['type'],
    //                 'name' => $file['name'],
    //             ];
    //         } else {
    //             $error_stack[] = [
    //                 'file' => $file['name'],
    //                 'time' => time()
    //             ];
    //         }
    //     }


    //     return [
    //         'code' => 200,
    //         'message' => 'success',
    //         "data" => $success_stack,
    //         "error" => $error_stack
    //     ];
    // }



    public function createFolder(Request $request)
    {

        $name = $request->name;
        $parent = $request->parent;
        $newdirectory = $_SERVER['DOCUMENT_ROOT'] . '/public/upload' . (empty(rtrim($parent, "\\/")) ? "/" : "/" . ltrim($parent, "\\/")) . "/" . trim($name, "\\/");

        try {

            if (file_exists($newdirectory)) {
                throw new \Exception("Folder already exists");
            }

            mkdir($newdirectory, 0777, true);
            $result = [
                "code" => 200,
                "message" => "Folder created successfully"
            ];
        } catch (\Exception $e) {
            $result = [
                "code" => 500,
                "message" => $e->getMessage()
            ];
        }

        return $result;
    }


    function deleteFile(Request $req)
    {

        if (empty($req->file())) {
            return [
                "code" => 400,
                "message" => "No file provided"
            ];
        }

        try {
            $file = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/' . ltrim($req->file(), "\\/");

            if (is_dir($file)) {

                if (count(glob(ltrim($file, "\\/") . "/*")) > 0) {
                    array_map('unlink', glob("$file/*.*"));
                }
                rmdir($file);

                if (file_exists($file)) {
                    throw new \Exception("Cant delete folder because it is not empty");
                }
            } elseif (is_file($file)) {
                unlink($file);
            } else {
                return [
                    "code" => 404,
                    "message" => "File not found"
                ];
            }

            return [
                "code" => 200,
                "message" => "File deleted successfully"
            ];
        } catch (Exception $e) {
            return [
                "code" => 500,
                "message" => $e->getMessage()
            ];
        }
    }


    function renameFile(Request $request)
    {
        $file = $request->file();
        $old_name = $request->old_name();
        $new_name = $request->new_name();

        $old_path = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/' . ltrim($file, "\\/");
        $new_path = dirname($old_path) . "/{$new_name}";

        if (!rename($old_path, $new_path)) {
            return [
                'code' => 500,
                'message' => 'Could not rename file.'
            ];
        }

        return [
            'code' => 200,
            'data' => [
                "old_path" => str_replace($_SERVER['DOCUMENT_ROOT'] . '/public/upload/', '', $old_path),
                "new_path" => str_replace($_SERVER['DOCUMENT_ROOT'] . '/public/upload/', '', $new_path)
            ],
            'message' => 'File renamed successfully.'
        ];
    }

    public function fetchJson(Request $request)
    {

        $service = $this->module;

        $files = $service->getAllFile();

        return [
            'code' => 200,
            'message' => 'success',
            "data" => $files
        ];
    }





    function uploadFile(Request $req)
    {

        $tempUploadDirectory = __DIR__ . '/../temp/';
        if (!is_dir($tempUploadDirectory)) {
            mkdir($tempUploadDirectory, 0755, true);
        }

        // Ensure a directory exists to store the uploaded files
        $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/public/upload' . (!empty($req->parent) ? '/' . ltrim($req->parent, "\\/") : "");
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        // Check if the required parameters are present
        if (!isset($_FILES['file']) || !isset($_POST['resumableIdentifier']) || !isset($_POST['resumableFilename']) || !isset($_POST['resumableChunkNumber'])) {
            header("HTTP/1.1 400 Invalid Request");
            die('Invalid request');
        }

        // Assign variables for easy access
        $file = $_FILES['file'];
        $temporaryFilePath = $file['tmp_name'];
        $resumableIdentifier = $_POST['resumableIdentifier'];
        $resumableFilename = $_POST['resumableFilename'];
        $resumableChunkNumber = $_POST['resumableChunkNumber'];

        // Create a unique file path
        $filePath = rtrim($tempUploadDirectory, "\\/") . "/" . $resumableIdentifier . '.' . $resumableFilename;

        // Handle the upload
        if (!move_uploaded_file($temporaryFilePath, "{$filePath}.part{$resumableChunkNumber}")) {
            header("HTTP/1.1 500 Internal Server Error");
            die('Error moving uploaded chunk');
        }

        // Check if all chunks are uploaded
        // You might need to adjust this to your needs
        $allChunksUploaded = true;
        for ($i = 1; $i <= $_POST['resumableTotalChunks']; $i++) {
            if (!file_exists("{$filePath}.part{$i}")) {
                $allChunksUploaded = false;
                break;
            }
        }

        // Combine chunks into a single file
        if ($allChunksUploaded) {

            $outputFilePath = rtrim($uploadDirectory, "\\/") . "/" . $resumableFilename;

            $x = 0;
            while (file_exists($outputFilePath)) {
                $x++;

                $fileName = pathinfo($resumableFilename, PATHINFO_FILENAME);
                $extension = pathinfo($resumableFilename, PATHINFO_EXTENSION);

                // Check if the filename already contains a number at the end
                $pattern = "/\\(\\d+\\." . preg_quote($extension) . "\\)$/";
                if (preg_match($pattern, $fileName)) {
                    $outputFileName = preg_replace_callback($pattern, function ($match) use ($x) {
                        return '-' . ($x + intval($match[0])) . '.';
                    }, $fileName);
                } else {
                    // Append the auto-incremented number to the end of the filename
                    $outputFileName = "{$fileName} ({$x}).{$extension}";
                }

                $outputFilePath = rtrim($uploadDirectory, "\\/") . "/" . $outputFileName;
            }

            // Now $outputFilePath contains the unique file path


            $out = fopen($outputFilePath, "wb");
            if ($out) {
                for ($i = 1; $i <= $_POST['resumableTotalChunks']; $i++) {
                    $in = fopen("{$filePath}.part{$i}", "rb");
                    if ($in) {
                        while ($buff = fread($in, 4096)) {
                            fwrite($out, $buff);
                        }
                    }
                    fclose($in);
                    unlink("{$filePath}.part{$i}"); // Delete the chunk
                }
                fclose($out);
            }


            return [
                "code" => 200,
                "message" => "File uploaded successfully",
                "data" => [
                    "path" => [
                        "relative" => trim(str_replace($_SERVER['DOCUMENT_ROOT'] . "/public/upload", '', $outputFilePath), '\\/'),
                        "full" => str_replace($_SERVER['DOCUMENT_ROOT'], '', $outputFilePath)
                    ],
                    "name" => basename($outputFilePath),
                    "size" => file_exists($outputFilePath) ? filesize($outputFilePath) : 0,
                    "icon" => $this->module->getIconName($outputFilePath),
                    "time" => date("Y-M-d H:i:s", filemtime($outputFilePath)),
                    "type" => mime_content_type($outputFilePath)
                ]
            ];
        }

        return [
            "code" => 200,
            "message" => "Ok",
        ];
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
                return file_get_contents($this->module->getIcon($file));
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
            case IMAGETYPE_PNG:
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
}
