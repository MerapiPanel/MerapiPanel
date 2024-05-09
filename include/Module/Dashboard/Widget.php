<?php

namespace MerapiPanel\Module\Dashboard;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Views\View;
use Symfony\Component\Filesystem\Path;

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
                    <link rel="stylesheet" href="{{ '/vendor/fontawesome/css/all.min.css' | assets | url }}" type="text/css">
                    <style>body, html {height: 100vh;display: flex;align-items: center;justify-content: center;}</style>
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








    function edit()
    {

        if (!$this->module->getRoles()->isAllowed(0)) {
            throw new \Exception('Permission denied');
        }

        $widgets = [];
        foreach (glob(Path::join($_ENV['__MP_APP__'], "Module", "**", "Widgets", "index.php")) as $file) {
            /**
             * @var array $data
             */
            $data = require $file;
            $widgets = array_merge($widgets, $data);
        }
        return $widgets;
    }




    public function save($data)
    {

        if (!$this->module->getRoles()->isAllowed(0)) {
            throw new \Exception('Permission denied');
        }

        if (!$data) {
            throw new \Exception('Invalid data');
        }

        $file = __DIR__ . "/widget.json";
        file_put_contents($file, is_string($data) ? $data : json_encode($data));

        return is_string($data) ? json_decode($data, true) : $data;
    }


    public function fetch()
    {

        $file = __DIR__ . "/widget.json";
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }

        $wgetData = json_decode(file_get_contents($file), true);
        if (!is_array($wgetData)) {
            $wgetData = [];
        }
        return $wgetData;
    }
}