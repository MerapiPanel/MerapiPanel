<?php

namespace MerapiPanel\Module\Website\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Guest extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {

        $this->module = $module;
    }


    public function register()
    {

        $pages = $this->module->Pages->fetchAll();
        $BlocksEditor = Box::module("Editor")->Blocks;
        $module = $this->module;

        foreach ($pages as $page) {

            $route = $page['route'];
            Router::GET($route, function () use ($page, $BlocksEditor, $module) {

                $components = $page['components'] ?? [];
                $styles     = $page['styles'] ?? "";
                $header     = $page['header'] ?? "";
                $variables  = $page['variables'] ?? "";
                $content    = $BlocksEditor->render($components);
                self::cleanTwigFragmentFromHtml($content);
                $lang = View::getInstance()->getIntl()->getLocale();

                $styles .= $BlocksEditor->getStyles();
                
                $html = <<<HTML
<!DOCTYPE html>
<html lang="{$lang}">
    <head>
        {% block header %}
        {$header}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {% block stylesheet %}
        <link rel="stylesheet" href="{{ '/dist/main.css' | assets }}">
        <link rel="stylesheet" href="{{ '/vendor/fontawesome/css/all.min.css' | assets }}">
        <style>{$styles}</style>
        {% endblock %}
        {% endblock %}
    </head>
    <body>
        {$content}
        {% block javascript %}
        <script src="{{ '/vendor/bootstrap/js/bootstrap.bundle.min.js' | assets }}"></script>
        <script src="{{ '/dist/main.js' | assets }}"></script>
        {% endblock %}
    </body>
</html>
HTML;
                $twig = View::getInstance()->getTwig();
                $template = $twig->createTemplate($html, "template");
                $_variables = [];
                try {

                    $variables = is_string($variables) ? json_decode($variables ?? '[]', true) : $variables;
                    if (is_array($variables) && count($variables) > 0) {
                        $_variables = Box::module("Website")->Variable->execute($variables);
                    }
                } catch (\Throwable $th) {
                    $_variables = [];
                }
                $_variables["_page"] = $page;
                $_variables["_request"] = Request::getInstance();
                $_variables["_lang"] = $lang;
                $output = $template->render($_variables);

                try {
                    $client_ip = Request::getClientIP();
                    $client_ip = $client_ip == '::1' ? '127.0.0.1' : $client_ip;
                    $page_path = Request::getInstance()->getPath();
                    $page_title = $this->getTitleFromHtml($output);
                    $module->Logs->write($client_ip, $page_path, $page_title);
                } catch (\Throwable $th) {
                    // silent
                }

                return View::minimizeHTML($output);
            });
        }
    }





    private static function cleanTwigFragmentFromHtml(&$html)
    {
        preg_match_all("/{{.*?}}|{%.*?%}/", $html, $matches);

        foreach ($matches[0] as $match) {
            // Remove any HTML tags from the Twig syntax
            $cleanedMatch = strip_tags($match);
            // Replace the Twig syntax with the cleaned text
            $html = str_replace($match, trim($cleanedMatch), $html);
        }
    }


    private function getTitleFromHtml($htmlString)
    {
        // Match the title tag and its content, allowing for attributes
        preg_match('/<title[^>]*>(.*?)<\/title>/is', $htmlString, $matches);

        // If a match is found, return the content of the title tag
        if (isset($matches[1])) {
            return $matches[1];
        }

        // If no match is found, return null
        return null;
    }
}
