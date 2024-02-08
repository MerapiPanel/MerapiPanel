<?php

namespace MerapiPanel\Core\View\Abstract;

use MerapiPanel\Core\Abstract\Module;
use ReflectionClass;
use ReflectionMethod;
use Twig\Extension\AbstractExtension;

class ViewComponent extends AbstractExtension
{

    protected $payLoad = false;






    public function setPayload($payload)
    {
        $this->payLoad = $payload;
    }





    public function getPayload()
    {
        return $this->payLoad;
    }






    public function getFunctions()
    {

        $thisMethod = get_class_methods($this);
        $functions = array_diff($thisMethod, get_class_methods(AbstractExtension::class));

        $regs = [];
        foreach ($functions as $function) {
            $regs[] = new \Twig\TwigFunction("$function", [$this, $function]);
        }

        return $regs;
    }








    public function getAvailableMethods()
    {

        $thisMethod = get_class_methods($this);
        $thisMethods = array_diff($thisMethod, array_merge(get_class_methods(AbstractExtension::class), get_class_methods(ViewComponent::class)));

        $stack = [];

        foreach ($thisMethods as $method) {

            $r = new ReflectionMethod($this, $method);

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

            $moduleName = Module::getModuleName($this::class);

            $stack[] = [
                "id"     => strtolower("comp:{$moduleName}:{$method}"),
                "params" => $params,
                "model"  => $this->extractDoc($r->getDocComment()),
            ];
        }

        return $stack;
    }








    function getHtmlDoc($comment)
    {

        $pattern = '/\*\s+<.*>.*/m';
        if (preg_match_all($pattern, $comment, $matches)) {

            $htmlContent = implode("\n", array_map(function ($match) {
                return ltrim($match, "\*");
            }, $matches[0]));

            return trim($htmlContent);
        }
    }








    function extractDoc($comment)
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

        $content = $this->getHtmlDoc($comment);
        if (!empty($content)) {
            $docs['content'] = $content;
        }

        return $docs;
    }
}
