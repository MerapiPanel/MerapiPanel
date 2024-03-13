<?php

namespace MerapiPanel\Module\Theme;

use DOMDocument;
use DOMXPath;
use MerapiPanel\Box;
use MerapiPanel\Core\View\View;
use MerapiPanel\Utility\Http\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EventRegister
{
    static function core_view_view_render(&$args)
    {

        if (isset($args["view"])) {
            if (strtolower($args['module']) == "site") {

                $theme = Box::module("theme")->service()->getActive();
                $view = View::newInstance([$theme['dirname']]);
                $view->getTwig()->addExtension(new ViewFunction($args['view']->__toString()));
                $request = new Request();

                if ($request->getPath() == "/" || empty($request->getPath())) {
                    $view->load("index.html");
                } else {
                    $view->load("page.html");
                }


                $args["view"] = $view;
            }
        }
    }
}

class ViewFunction extends AbstractExtension
{
    public $content = "";
    public DOMDocument $dom;
    public DOMXPath $xpath;


    public function __construct($content)
    {
        $this->content = $content;
        $this->dom = new DOMDocument();
        @$this->dom->loadHTML($content);
        $this->xpath = new DOMXPath($this->dom);
    }


    public function getFunctions()
    {
        return [
            new TwigFunction("content_html", fn() => $this->html()),
            new TwigFunction("content_style", fn() => $this->style()),
            new TwigFunction("content_script", fn() => $this->script()),
            new TwigFunction("content_title", fn() => $this->title()),
        ];
    }


    public function title()
    {

        return $this->dom->getElementsByTagName("title")->item(0)->nodeValue;
    }

    public function html()
    {
        // Extract all HTML content within the <body> tag
        $bodyContent = $this->xpath->query('//body')->item(0)->childNodes;

        $htmlElements = [];
        foreach ($bodyContent as $node) {
            $htmlElements[] = $this->dom->saveHTML($node);
        }

        return implode("\n", $htmlElements);
    }




    function script()
    {

        // Extract link elements for stylesheets
        $scriptElements = $this->xpath->query('//script');
        $scripts = [];
        foreach ($scriptElements as $link) {
            $scripts[] = $this->dom->saveHTML($link);
        }
        return implode("\n", $scripts);
    }



    public function style()
    {

        // Extract link elements for stylesheets
        $linkElements = $this->xpath->query('//link[@rel="stylesheet"]');
        $stylesheets = [];
        foreach ($linkElements as $link) {
            $stylesheets[] = $this->dom->saveHTML($link);
        }

        // Extract style tags
        $styleTags = $this->xpath->query('//style');
        $styles = [];
        foreach ($styleTags as $style) {
            $styles[] = $this->dom->saveHTML($style);
        }

        return implode("\n", array_merge($stylesheets, $styles));
    }

}