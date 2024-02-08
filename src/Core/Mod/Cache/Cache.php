<?php

namespace MerapiPanel\Core\Mod\Cache;

use MerapiPanel\Utility\Util;
use ReflectionClass;

class Cache
{

    protected Map $cacheMap;
    private $datadir;
    private static $instance;


    private function __construct()
    {
        $this->datadir = __DIR__ . "/data";
        if (!file_exists($this->datadir)) {
            mkdir($this->datadir);
        }
        $this->cacheMap = new Map();
    }


    public function getMap(): Map
    {
        return $this->cacheMap;
    }



    public function store($className, $identify)
    {

        $this->cacheMap[$className] = $identify;
        $content = $this->createDefinitionString($className, $identify);
        $namespace = $this->getNamespace($content);
        file_put_contents($this->datadir . "/" . $identify . ".php", $content);


        $finalClassName = $namespace . "\\" . $identify;
        if (!class_exists($finalClassName)) {
            require_once($this->datadir . "/" . $identify . ".php");
        }

        return $finalClassName;
    }


    public function retrive($className)
    {

        $identify = $this->cacheMap[$className];
        $namespace = $this->getNamespace(file_get_contents($this->datadir . "/" . $identify . ".php"));
        $finalClassName = $namespace . "\\" . $identify;
        if (!class_exists($finalClassName)) {
            require_once($this->datadir . "/" . $identify . ".php");
        }

        return $finalClassName;
    }

    public function remove($className)
    {
        unset($this->cacheMap[$className]);
        unlink($this->datadir . "/" . $this->cacheMap[$className] . ".php");
    }



    public function isDataExist($className)
    {
        return file_exists($this->datadir . "/" . $this->cacheMap[$className] . ".php");
    }



    public function getNamespace($stringCode)
    {

        if (is_file($stringCode)) {
            $stringCode = file_get_contents($stringCode);
        }
        // Regular expression to match the namespace
        $pattern = '/namespace\s+([^;]+);/';

        // Perform the match
        if (preg_match($pattern, $stringCode, $matches)) {
            $namespace = $matches[1]; // Capture the namespace
            return $namespace;
        }
    }



    public function createDefinitionString($className, $identify)
    {

        $reflector = new ReflectionClass($className);
        $classDefinitionString = $this->getIntierCode($reflector);

        // Regular expression to remove parameter types
        $pattern = '/\bfunction\s+\w+\s*\(([^)]*)\)/';
        preg_match_all($pattern, $classDefinitionString, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            // Adjusted to match optional nullable indicator `?` before type
            $modifiedParameters = preg_replace('/\??\w+\s+\&?\$/', '$', $match[1]);
            $newFunctionDef = str_replace($match[1], $modifiedParameters, $match[0]);
            $classDefinitionString = str_replace($match[0], $newFunctionDef, $classDefinitionString);
        }

        $classDefinitionString = preg_replace('/\bclass\s+' . ($reflector->getShortName()) . '\b/', "class $identify", $classDefinitionString);

        $fileName = $reflector->getFileName();
        return "<?php\r\n//$fileName\r\n" . $this->getHeader($reflector) . "\r\n" . $classDefinitionString . "\r\n?>";
    }



    private function getIntierCode(ReflectionClass $reflector)
    {

        // Get the file name where the class is defined
        $filename = $reflector->getFileName();

        // Get the start and end line numbers of the class definition
        $startLine = $reflector->getStartLine() - 1; // it's zero-based in the file lines
        $endLine = $reflector->getEndLine();
        $length = $endLine - $startLine;

        // Read the file and extract the class code
        $source = file($filename);
        $classCode = implode("", array_slice($source, $startLine, $length));

        return $classCode;
    }


    private function getHeader(ReflectionClass $reflector)
    {

        // Get the file name where the class is defined
        $filename = $reflector->getFileName();

        // Read the content of the file
        $fileContent = file_get_contents($filename);

        // Extract the header code (namespace and use statements)
        $headerCode = '';
        $matches = [];
        preg_match('/^<\?php\s+(namespace\s+[^\s;]+;)?\s*([\s\S]+?)\bclass\s+' . $reflector->getShortName() .  '\b/s', $fileContent, $matches);
        if (isset($matches[1])) {
            $headerCode = $matches[1] . "\r\n";
        }
        if (isset($matches[2])) {
            $headerCode .= $matches[2];
        }

        return $headerCode;
    }











    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Cache();
        }
        return self::$instance;
    }






    public static function get($identify)
    {
        return self::getInstance()->retrive($identify);
    }


    public static function delete($key)
    {
        return self::getInstance()->remove($key);
    }

    public static function set($key, $identify)
    {
        return self::getInstance()->store($key, $identify);
    }


    public static function isExist($key): bool
    {

        $instance = self::getInstance();
        $map = $instance->getMap();
        $status = true;
        if (!isset($map[$key])) {
            $status = false;
        }
        if (!$instance->isDataExist($key)) {
            $status = false;
        }
        return $status;
    }


    public static function getIdentify($className)
    {

        $instance = self::getInstance();
        $map = $instance->getMap();
        if (isset($map[$className])) {
            return $map[$className];
        }

        $identify = "cache_" . Util::uniq();
        $map[$className] = $identify;

        return $identify;
    }
}
