<?php

namespace MerapiPanel\Module\FileManager;

use MerapiPanel\Box;
use MerapiPanel\Box\FileFragment;
use MerapiPanel\Box\FileLoader;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;
use Symfony\Component\Filesystem\Path;


class ModuleFileAssetsLoader extends FileLoader
{

    public function getFile(string $name): Box\FileFragment|null
    {

        preg_match("/\@(\w+)/i", $name, $matches); // get module name
        if (isset($matches[1])) { // assets with module namespace
            $moduleName = ucfirst($matches[1]); // add module namespace
            $module = Box::module($moduleName); // get module
            $modulePath = $module->path; // module path
            // $modulePath = $_ENV['__MP_APP__'] . "/Module/" . $moduleName; // module path
            $path = ltrim(str_replace("@" . $matches[1], "", $name), "\\/"); // remove module namespace from path

            if (strpos($path, "assets") !== 0 && file_exists($modulePath . "/assets/" . $path)) {
                $path = "assets/" . $path;
            } else if (strpos($path, "Assets") !== 0 && file_exists($modulePath . "/Assets/" . $path)) {
                $path = "Assets/" . $path;
            }

            return new FileFragment(Path::join($modulePath, $path));
        }

        return null;

    }
}

class BuildinFilesLoader extends FileLoader
{

    public function getFile(string $name): Box\FileFragment|null
    {

        $name = ltrim($name, "\\/");

        if (file_exists(Path::join($this->directory, $name)))
        // file in directory directly
        {
            $path = Path::join($this->directory, $name);
        } else if (strpos($name, "assets") !== 0 && file_exists(Path::join($this->directory, "assets", $name)))
        // case sensitive in linux
        {
            $path = Path::join($this->directory, "assets", $name);
        } else if (strpos($name, "Assets") !== 0 && file_exists(Path::join($this->directory, "Assets", $name)))
        // case sensitive in linux
        {
            $path = Path::join($this->directory, "Assets", $name);
        }

        if (isset($path) && file_exists($path)) {
            return new FileFragment($path);
        }
        return null;
    }
}



enum AssetsType: string
{
    case BUILDIN = "buildin";
    case MODULE = "module";
}



class Assets extends __Fragment
{

    public string $routeLink = "/public/assets/{data}";
    protected Module $module;

    protected $loaders = [
        AssetsType::BUILDIN->value => [],
        AssetsType::MODULE->value => []
    ];


    function onCreate(Module $module)
    {
        $this->module = $module;
        $this->loaders = [
            AssetsType::BUILDIN->value => [
                new BuildinFilesLoader(Path::join($_ENV['__MP_APP__'], "Buildin"))
            ],
            AssetsType::MODULE->value => [
                new ModuleFileAssetsLoader(Path::join($_ENV['__MP_APP__'], "Module"))
            ]
        ];
    }



    public function addLoader(AssetsType|string $type, FileLoader $loader)
    {
        if (!in_array($type, array_keys($this->loaders))) {
            throw new \Exception("Invalid type: " . $type);
        }
        $this->loaders[$type instanceof AssetsType ? $type->value : $type][] = $loader;
    }




    /**
     * Generates a URL for the given absolute file path, with caching for performance.
     *
     * @param string $absoluteFilePath The absolute file path for which to generate the URL
     * @return string The generated URL
     */
    public function url($absoluteFilePath, $encrypt = false)
    {
        // Remove query string from the absolute file path
        $absoluteFilePath = preg_replace("/\?.*/", "", $absoluteFilePath);
        $final = str_replace("{data}", $absoluteFilePath, $this->routeLink);
        // Return the generated URL
        return $final;
    }




    public function getAssset(Request $req)
    {

        $realPath = $this->getRealPath($req->data);

        if (!$realPath || !is_file($realPath)) {
            return [
                "code" => 404,
                "message" => "Assets not found"
            ];
        }

        return $this->sendResponse($req, $realPath);
    }






    private function loadAssetComponent($req, $source, $file)
    {

        $refRealPath = $this->getRealPath($source);

        if (!file_exists($refRealPath)) {
            return [
                "code" => 404,
                "message" => "Referer assets not found",
            ];
        }

        $dirname = dirname($refRealPath);
        $fullPath = rtrim($dirname, "\/") . "/" . ltrim($file, "\/");
        if (!file_exists($fullPath)) {
            $fullPath = rtrim($dirname, "\/") . "/" . pathinfo(ltrim($file, "\/"), PATHINFO_BASENAME);
        }
        if (!file_exists($fullPath)) {
            return [
                "code" => 404,
                "message" => "Assets not found ",
            ];
        }

        return $this->sendResponse($req, $fullPath);
    }





    private function sendResponse($req, $file): Response
    {

        $response = new Response();

        if ($_ENV['__MP_CACHE__'] === true) {
            // Determine the last modified time of the file for caching
            $lastModifiedTime = filemtime($file);
            $etag = md5($lastModifiedTime . $file);

            $response->setStatusCode(200);
            $response->setHeader("Status-Code", 200);
            $response->setHeader("Cache-Control", "public, max-age=86400");
            $response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s", $lastModifiedTime) . " GMT");
            $response->setHeader("Etag", $etag);

            // Check if the page has been modified since the browser's cache
            if ($req->http("if-modified-since") && $req->http("if-modified-since") == gmdate("D, d M Y H:i:s", $lastModifiedTime)) {
                // Return 304 Not Modified without any content if the ETag matches
                $response->setStatusCode(304);
                return $response;
            }
        }

        $output = file_get_contents($file);
        $mimeTypes = json_decode(file_get_contents(__DIR__ . "/mimeType.json"), true);

        // Set the appropriate Content-Type header
        $contentType = $mimeTypes[strtolower(pathinfo($file, PATHINFO_EXTENSION))] ?? 'application/octet-stream'; // Default to binary if MIME type is application/octet-stream
        $response->setHeader("Content-Type", $contentType);
        $response->setContent($output);

        return $response;
    }







    private function getRealPath($path): bool|FileFragment|null
    {

        if (!$path) {
            return false;
        }

        $file_result = null;
        preg_match("/\@(\w+)/i", $path, $matches); // get module name

        if (isset($matches[1])) { // assets with module namespace
            /**
             * @var ModuleFileAssetsLoader  $loader
             */
            foreach ($this->loaders[AssetsType::MODULE->value] as $loader) {
                if ($file_result = $loader->getFile($path)) {
                    break;
                }
            }
        } else {
            /**
             * @var BuildinFilesLoader  $loader
             */
            foreach ($this->loaders[AssetsType::BUILDIN->value] as $loader) {
                if ($file_result = $loader->getFile($path)) {
                    break;
                }
            }
        }


        return $file_result;
    }

}
