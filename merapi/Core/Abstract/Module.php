<?php

namespace MerapiPanel\Core\Abstract;

use Exception;
use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Component\Options;
use MerapiPanel\Core\Abstract\Component\Settings;
use MerapiPanel\Core\Database;
use ReflectionClass;


abstract class Module
{

    protected $box;


    public function setBox(Box $box)
    {

        $this->box = $box;
    }






    public function getBox()
    {
        return $this->box;
    }






    final public function getDatabase()
    {

        $file = $this->__getIndex();

        $db = new Database($file);
        return $db;
    }







    private function __getChildFile()
    {
        $reflection = new ReflectionClass($this::class);
        return $reflection->getFileName();
    }









    public function __getIndex()
    {
        $file = $this->__getChildFile();

        $parts = explode((PHP_OS == "WINNT" ? "\\" : "/"), $file);

        foreach ($parts as $key => $part) {
            if (strtolower($part) == "module") {
                $modName = $parts[$key + 1];
                $file = implode("/", array_slice($parts, 0, $key + 1)) . "/" . $modName;
                break;
            }
        }

        return $file;
    }









    public function __call($name, $arguments)
    {

        $index = $this->__getIndex();
        $basename = "\\" . basename($index);
        $class = $this::class;
        $pos = strpos($class, $basename);

        $baseClass = substr($class, 0, $pos + strlen($basename));
        $target = implode("/", array_map("ucfirst", explode("/", $name)));
        $instance = "{$baseClass}\\{$target}";

        if (!class_exists($instance)) {
            throw new Exception("Module $target not found");
        }

        return $this->box->$instance($arguments);
    }





    final public function getOptions()
    {

        return Options::getInstance($this::class);
    }



    public function __getSettings()
    {

        $db = $this->getDatabase();
        return new Settings($db);
    }



    public static function getModuleName($pathOrClassName)
    {
        // Combining both Unix and Windows style paths in one pattern
        if (preg_match("/module[\\\\\/]([^\\\\\/]+)/i", $pathOrClassName, $matches)) {
            return $matches[1]; // Return the module name directly
        }
        return false;
    }

    public static function moduleExist($name)
    {

        return is_dir($_ENV["__MP_CWD__"] . "/merapi/module/$name");
    }
}