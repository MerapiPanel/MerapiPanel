<?php

namespace MerapiPanel\Module\FileManager\Controller;

use Exception;
use finfo;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;
use MerapiPanel\Utility\Http\Request;
use Symfony\Component\DependencyInjection\Attribute\Exclude;

class Admin extends Module
{

    public function register($router)
    {

        $panel = $this->getBox()->Module_Panel();
        setcookie("fm-adm-pth", $panel->adminLink(), time() + (86400 * 30), $panel->adminLink());

        $router->get('/filemanager/fetch-endpoint', "fetchEndpoint", self::class);
        $router->get("/filemanager/fetchJson", "fetchJson", self::class);
        $router->post("/filemanager/upload", "upload", self::class);
        $router->post("/filemanager/create_folder", "createFolder", self::class);
        $router->post("/filemanager/delete_file", "deleteFile", self::class);
        $router->post("/filemanager/rename_file", "renameFile", self::class);

        $route = $router->get("/filemanager", "index", self::class);
        $panel = $this->getBox()->Module_Panel();

        $panel->addMenu([
            'order' => 3,
            'name' => "File Manager",
            'icon' => 'fa-regular fa-folder',
            'link' => $route->getPath()
        ]);
    }


    public function fetchEndpoint()
    {

        $panel = $this->getBox()->Module_Panel();

        return [
            'code' => 200,
            'data' => [
                'assets' => $panel->adminLink('filemanager/fetchJson'),
                'uploads' => $panel->adminLink('filemanager/upload')
            ],
            "message" => "Ok"
        ];
    }

    public function upload(Request $request)
    {

        if (!isset($_FILES['files'])) {
            return [
                'code' => 400,
                'message' => 'Error invalid parameter',
            ];
        }

        $files = $_FILES['files'];

        $error_stack = [];
        $success_stack = [];
        for ($x = 0; $x < count($files['name']); $x++) {

            $file = [];
            $file['name'] = $files['name'][$x];
            $file['tmp_name'] = $files['tmp_name'][$x];


            $upload = $this->service()->upload($file);

            if ($upload) {

                $folder = str_replace("/" . basename($upload), "", $upload);
                $ext = pathinfo($upload, PATHINFO_EXTENSION);
                switch ($ext) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                    case 'bmp':
                    case 'ico':
                    case 'webp':
                    case 'svg':
                    case 'tiff':
                    case 'tif':
                    case 'tga':
                        $file['type'] = 'image';
                        break;
                    case 'mp3':
                    case 'wav':
                    case 'wma':
                    case 'ogg':
                    case 'flac':
                    case 'm4a':
                    case 'aac':
                    case 'm4b':
                    case 'm4p':
                        $file['type'] = 'audio';
                        break;
                    case 'mp4':
                    case 'mkv':
                    case 'avi':
                    case 'mov':
                    case 'flv':
                    case 'wmv':
                    case 'm4v':
                    case 'mpg':
                    case 'mpeg':
                    case 'ogv':
                    case 'webm':
                    case '3gp':
                    case '3g2':
                        $file['type'] = 'video';
                        break;
                    case 'pdf':
                    case 'doc':
                    case 'docx':
                    case 'xls':
                    case 'xlsx':
                    case 'ppt':
                    case 'pptx':
                    case 'txt':
                    case 'odt':
                    case 'ods':
                    case 'odp':
                    case 'odg':
                    case 'odf':
                        $file['type'] = 'document';
                        break;
                    default:
                        $file['type'] = 'application/octet-stream';
                }
                $success_stack[] = [
                    'src' => $upload,
                    'category' => basename($folder),
                    'type' => $file['type'],
                    'name' => $file['name'],
                ];
            } else {
                $error_stack[] = [
                    'file' => $file['name'],
                    'time' => time()
                ];
            }
        }


        return [
            'code' => 200,
            'message' => 'success',
            "data" => $success_stack,
            "error" => $error_stack
        ];
    }



    public function createFolder(Request $request)
    {

        $name = $request->name();
        $parent = $request->parent();
        $newdirectory = $_SERVER['DOCUMENT_ROOT'] . '/public/upload' . rtrim($parent, "\\/") . "/" . trim($name, "\\/");

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
            'message' => 'File renamed successfully.'
        ];
    }

    public function fetchJson(Request $request)
    {

        $service = $this->service();

        $files = $service->getAllFile();

        return [
            'code' => 200,
            'message' => 'success',
            "data" => $files
        ];
    }



    public function index($req)
    {

        $root = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/' . (!empty($req->getQuery("directory")) ? ltrim($req->getQuery("directory"), "\\/") : "");
        $container = [];

        if (!is_file($root)) {
            $data = [];
            $files = scandir($root);
            foreach ($files as $f) {

                if ($f == '.' || $f == '..') {
                    continue;
                }
                $filePath = $root . "/{$f}";

                $data[] = [
                    "name" => $f,
                    "path" => trim(str_replace($_SERVER['DOCUMENT_ROOT'] . "/public/upload", '', $filePath), '\\/'),
                    "size" => filesize($filePath),
                    "time" => date("Y-M-d H:i:s", filemtime($filePath)),
                    "type" => mime_content_type($filePath),
                    "icon" => $this->service()->getIconName($filePath)
                ];
            }
            $container['type'] = "navigate";
        } else {
            $data = [
                "name" => basename($root),
                "mime" => mime_content_type($root),
                "size" => filesize($root),
                "time" => date("Y-M-d H:i:s", filemtime($root)),
                "path" => [
                    "relative" => str_replace($_SERVER['DOCUMENT_ROOT'], '', $root),
                    "full" => $root
                ]
            ];
            $container['type'] = "open";
        }

        $container["root"] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $root);
        $container["data"] = $data;


        return View::render("index.html.twig", [
            'container' => $container
        ]);
    }
}
