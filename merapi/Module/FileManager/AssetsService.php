<?php

namespace MerapiPanel\Module\FileManager;

use MerapiPanel\Core\AES;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;

class AssetsService
{

    private AssetMap $assetsMap;
    public string $routeLink = "/public/assets/{data}";

    public function __construct()
    {
        $this->assetsMap = new AssetMap();
    }



    private static function createMapKey($absoluteFilePath)
    {
        return strtolower(preg_replace("/[^a-z0-9]+/i", "", $absoluteFilePath));
    }




    /**
     * Generates a URL for the given absolute file path, with caching for performance.
     *
     * @param string $absoluteFilePath The absolute file path for which to generate the URL
     * @return string The generated URL
     */
    public function url($absoluteFilePath)
    {
        // Remove query string from the absolute file path
        $absoluteFilePath = preg_replace("/\?.*/", "", $absoluteFilePath);

        // Generate a key map using the sanitized absolute file path
        $keyMap = self::createMapKey($absoluteFilePath);

        // Check if the URL for the key map exists in the assets map and is not expired
        if (isset ($this->assetsMap[$keyMap]["url"]) && time() - $this->assetsMap[$keyMap]["time"] < 3600) {
            return $this->assetsMap[$keyMap]["url"];
        }

        // Encrypt the absolute file path and generate the URL using the route link template
        $data = rawurlencode(AES::encrypt($absoluteFilePath));
        $final = str_replace("{data}", $data, $this->routeLink);

        // Cache the generated URL in the assets map with the current time
        $this->assetsMap[$keyMap] = [
            "url" => $final,
            "time" => time()
        ];

        // Return the generated URL
        return $final;
    }




    public function getAssset(Request $req)
    {

        $referer_path = parse_url($req->http("referer"), PHP_URL_PATH);

        if (!empty ($referer_path) && $this->assetsMap->contains($referer_path)) {

            $pattern = preg_replace("/\//", "\/", $this->routeLink);
            $pattern = "/" . str_replace("{data}", "(.*)", $pattern) . "$/";

            preg_match($pattern, $req->http("referer"), $matches);
            if (isset ($matches[1])) {

                $base = AES::decrypt(rawurldecode($matches[1]));
                if ($base[0] === "!") {
                    $base = substr($base, 1);
                }

                return $this->loadAssetComponent($req, $base, $req->data);

            }

            return [
                "code" => 403,
                "message" => "Forbidden - Not allowed!",
            ];
        }

        $path = AES::decrypt(rawurldecode($req->data));

        if (($path && $path[0]) !== "!" && !$req->http("referer")) {
            return [
                "code" => 403,
                "message" => "Forbidden - Not allowed!"
            ];
        } else if ($path[0] === "!") // ignore referer
        {
            $path = substr($path, 1);
        }


        $realPath = $this->getRealPath($path);

        if (!is_string($realPath) || !file_exists($realPath)) {

            $keyMap = self::createMapKey($path);
            unset($this->assetsMap[$keyMap]);

            return [
                "code" => 404,
                "message" => "Assets not found " . $realPath
            ];
        }

        return $this->sendResponse($req, $realPath);
    }


    private function loadAssetComponent($req, $base, $file)
    {

        $realPath = $this->getRealPath($base);

        if (!file_exists($realPath)) {
            return [
                "code" => 404,
                "message" => "Assets not found ",
            ];
        }

        $dirname = dirname($realPath);
        $fullPath = rtrim($dirname, "\/") . "/" . ltrim($file, "\/");

        if (!file_exists($fullPath)) {
            return [
                "code" => 404,
                "message" => "Assets not found ",
            ];
        }

        return $this->sendResponse($req, $fullPath);
    }





    private function sendResponse($req, $file)
    {

        $response = new Response();

        // Determine the last modified time of the file for caching
        $lastModifiedTime = filemtime($file);
        $etag = md5($lastModifiedTime . $file);

        // Send the headers for caching
        header("Cache-Control: public, max-age=86400"); // Example: cache for 1 day (86400 seconds)
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModifiedTime) . " GMT");
        header("Etag: $etag");
        $response->setHeader("Cache-Control", "public, max-age=86400");
        $response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s", $lastModifiedTime) . " GMT");
        $response->setHeader("Etag", $etag);

        // Check if the page has been modified since the browser's cache
        if ($req->http("if-modified-since") && $req->http("if-modified-since") == gmdate("D, d M Y H:i:s", $lastModifiedTime)) {
            // Return 304 Not Modified without any content if the ETag matches
            $response->setStatusCode(304);
            return $response;
        }

        ob_start();
        echo file_get_contents($file);
        $output = ob_get_flush();
        ob_clean();

        $mimeTypes = json_decode(file_get_contents(__DIR__ . "/mimeType.json"), true);

        // Set the appropriate Content-Type header
        $contentType = $mimeTypes[strtolower(pathinfo($file, PATHINFO_EXTENSION))] ?? 'application/octet-stream'; // Default to binary if MIME type is unknown
        header("Content-Type: $contentType");
        $response->setHeader("Content-Type", $contentType);


        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) == "svg") {
            error_log("Content-Type: " . $contentType);
        }

        $response->setContent($output);

        return $response;
    }







    private function getRealPath($path)
    {

        if (!$path)
            return false;

        preg_match("/\@(\w+)/i", $path, $matches);
        $moduleName = "base";

        if (isset ($matches[1])) {
            $moduleName = "module/" . $matches[1];
            $path = ltrim(str_replace("@" . $matches[1], "", $path), "\\/");
        }
        if (!is_file($_SERVER['DOCUMENT_ROOT'] . "/merapi/" . $moduleName . "/" . $path) && strpos($path, "assets") !== 0) {
            $path = "assets/" . ltrim($path, "\/");
        }

        $path = ltrim(rtrim($moduleName, "\/") . "/" . ltrim($path, "\/"), "\/");
        return (preg_replace("/\?.*/", "", $_SERVER['DOCUMENT_ROOT'] . "/merapi/" . $path));
    }



}



class AssetMap implements \ArrayAccess
{

    private $stack_data;
    private $fileName = "map.json";
    private $savePath = __DIR__;


    public function __construct()
    {

        $this->savePath = __DIR__ . "/" . $this->fileName;

        if (!file_exists($this->savePath)) {
            file_put_contents($this->savePath, json_encode([]));
        }
        $this->stack_data = json_decode(file_get_contents($this->savePath), true);
    }




    function offsetExists(mixed $offset): bool
    {
        return isset ($this->stack_data[$offset]);
    }





    function offsetGet(mixed $offset): mixed
    {
        return $this->stack_data[$offset];
    }





    function offsetSet($offset, $value): void
    {
        $this->stack_data[$offset] = $value;
        $this->save();
    }





    function offsetUnset($offset): void
    {
        unset($this->stack_data[$offset]);
        $this->save();
    }



    public function contains($value)
    {

        $serialized = array_map(function ($e) {
            return preg_replace("/[^a-z0-9]+/im", "", $e);
        }, array_values(array_column($this->stack_data, "url")));

        return in_array(preg_replace("/[^a-z0-9]+/im", "", $value), $serialized);
    }




    private function save(): void
    {

        file_put_contents($this->savePath, json_encode($this->stack_data, JSON_PRETTY_PRINT));
    }


}
