<?php

namespace MerapiPanel\Module\Dashboard;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Views\View;

class Widget extends __Fragment
{

    protected $module;

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }

    public function scanProvideWidgets()
    {

        $classNames = [];
        $dirs = glob(realpath(__DIR__ . "/../") . "/**/Views/Widget.php", GLOB_NOSORT);
        foreach ($dirs as $dir) {

            $namespace = str_replace(realpath(__DIR__ . "/../"), "", str_replace("/Views/Widget.php", "", $dir));
            $className = "\\MerapiPanel\\Module\\" . trim($namespace, "\\/") . "\\Views\\Widget";

            if (class_exists($className)) {
                $classNames[] = $className;
            }
        }

        return $classNames;
    }



    public function getDefindedWidgets()
    {
        $classNames = $this->scanProvideWidgets();
        $widgets = [];
        foreach ($classNames as $className) {

            $moduleName = Module::getModuleName($className);
            $reflector = new \ReflectionClass($className);
            $methods = $reflector->getMethods(\ReflectionMethod::IS_FINAL);
            foreach ($methods as $method) {

                $doc = self::extractDoc($method->getDocComment());
                $widgets[] = array_merge($doc, [
                    "name" => strtolower($moduleName . ":" . $method->getName()),
                    "category" => $moduleName,
                ]);
            }
        }

        return $widgets;
    }




    public function renderWidget($name)
    {

        try {
            list($widget_module, $widget_name) = explode("/", $name);

            $render = "render.php";
            $css = "index.css";
            $js = "index.js";
            $render_fragment = Box::module(ucfirst(preg_replace("/^@/", "", $widget_module . "/")))->Widgets->$widget_name->$render;
            $css_fragment = Box::module(ucfirst(preg_replace("/^@/", "", $widget_module . "/")))->Widgets->$widget_name->dist->$css;
            $js_fragment = Box::module(ucfirst(preg_replace("/^@/", "", $widget_module . "/")))->Widgets->$widget_name->dist->$js;

            ob_start();
            require_once $render_fragment->path;
            $content = ob_get_contents();
            ob_end_clean();
            $css_content = "<style type=\"text/css\">" . ($css_fragment ? $css_fragment->getContent() : "") . "</style>";
            $js_content = "<script type=\"text/javascript\">" . ($js_fragment ? $js_fragment->getContent() : "") . "</script>";

            $twig = View::getInstance()->getTwig();

            if ($twig) {
                $html = <<<HTML
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Widget | $name</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="stylesheet" href="{{ 'dist/main.css' | assets | url }}" type="text/css">
                    $css_content
                </head>
                <body>
                    $content
                    $js_content
                </body>
            </html>
            HTML;
                $content = $twig->createTemplate($html)->render([]);
            }

            if (!empty($content)) {
                return new Response($content, 200, ["Content-Type" => "text/html"]);
            }
        } catch (\Throwable $e) {

            $twig = View::getInstance()->getTwig();

            if ($twig) {
                $html = <<<HTML
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="UTF-8"><title>Widget | $name</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="{{ 'dist/main.css' | assets | url }}" type="text/css"><style>body, html {height: 100vh;display: flex;align-items: center;justify-content: center;}</style>
                </head>
                <body>
                    <div class="alert alert-danger" role="alert">failed to render widget: $name</div>
                </body>
            </html>
            HTML;
                $content = $twig->createTemplate($html)->render([]);
                return new Response($content, 200, ["Content-Type" => "text/html"]);
            }
            return [
                "code" => 400,
                "message" => $e->getMessage(),
            ];
        }

        return [
            "code" => 400,
            "message" => "fail to find widget: " . $name,
        ];
    }


    private static function extractDoc($comment)
    {

        $docs = [
            "title" => "No title",
            "icon" => "",
        ];
        $pattern = '/\@(.*?)\s(.*)/';

        if (preg_match_all($pattern, $comment, $matches)) {
            foreach ($matches[1] as $key => $value) {
                $docs[$value] = trim($matches[2][$key]);
            }
        }
        return $docs;
    }
}