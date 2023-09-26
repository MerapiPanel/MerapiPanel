<?php

namespace MerapiPanel\Module\FileManager\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Http\Request;

class Admin extends Module
{

    public function register($router)
    {

        $panel = $this->getBox()->Module_Panel();
        setcookie("fm-adm-pth", $panel->adminLink(), time() + (86400 * 30), $panel->adminLink());

        $router->get('/filemanager/fetch-endpoint', "fetchEndpoint", self::class);
        $router->get("/filemanager/fetchJson", "fetchJson", self::class);
        $router->post("/filemanager/upload", "upload", self::class);
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



    public function fetchJson($v, Request $request)
    {

        $service = $this->service();

        $files = $service->getAllFile();

        return [
            'code' => 200,
            'message' => 'success',
            "data" => $files
        ];
    }

    public function index($view)
    {

        return $view->render("index.html.twig");
    }
}
