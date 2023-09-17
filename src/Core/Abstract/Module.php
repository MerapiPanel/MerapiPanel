<?php

namespace Mp\Core\Abstract;

use Mp\Core\Database;
use ReflectionClass;

abstract class Module
{

    final public function getDatabase()
    {

        $file = $this->__getIndex();
        return new Database($file);
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
}
