<?php

namespace MerapiPanel\Core\View\Component;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Event;

class ViewComponent
{

    public static function from(string $className): ComponentProvider
    {

        return new ComponentProvider($className);
    }
}



class ComponentProvider
{


    private $className;
    private $reflector;


    public function __construct($className)
    {
        $this->className = $className;
        $this->reflector = new \ReflectionClass($className);
    }



    public function getComponents()
    {

        $thisMethods = $this->reflector->getMethods(\ReflectionMethod::IS_FINAL);
        $thisMethods = array_map(function ($method) {
            return $method->getName();
        }, $thisMethods);

        $moduleName = null;
        $stack = [];
        foreach ($thisMethods as $method) {

            $r = new \ReflectionMethod($this->reflector->newInstanceWithoutConstructor(), $method);

            $params = [];
            foreach ($r->getParameters() as $param) {

                $option = [
                    "name" => $param->getName(),
                ];

                if ($param->isDefaultValueAvailable()) {
                    $option["default"] = $param->getDefaultValue();
                }

                $params[] = $option;
            }

            if (!$moduleName) $moduleName = Module::getModuleName($this->className);

            $stack[] = [
                "id"     => strtolower("comp:{$moduleName}:{$method}"),
                "params" => $params,
                "model"  => self::extractDoc($r->getDocComment()),
            ];
        }

        return $stack;
    }





    public static function getHtmlDoc($comment)
    {

        $pattern = '/\*\s+<.*>.*/m';
        if (preg_match_all($pattern, $comment, $matches)) {

            $htmlContent = implode("\n", array_map(function ($match) {
                return ltrim($match, "\*");
            }, $matches[0]));

            return trim($htmlContent);
        }
    }




    public static function extractDoc($comment)
    {

        $docs = [
            "content" => "",
            "label"   => "No name",
            "media"    => "",
        ];
        $pattern = '/\@(.*?)\s(.*)/';
        if (preg_match_all($pattern, $comment, $matches)) {
            foreach ($matches[1] as $key => $value) {
                $docs[$value] = trim($matches[2][$key]);
            }
        }

        $content = self::getHtmlDoc($comment);
        if (!empty($content)) {
            $docs['content'] = $content;
        }

        return $docs;
    }
}
