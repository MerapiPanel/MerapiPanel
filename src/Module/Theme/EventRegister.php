<?php

namespace MerapiPanel\Module\Theme;

use DOMDocument;
use DOMXPath;
use MerapiPanel\Box;
use MerapiPanel\Core\View\View;
use MerapiPanel\Utility\Http\Request;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EventRegister
{
    static function core_view_view_render(&$args)
    {

        if (isset($args["view"])) {
            if (strtolower($args['env']) == "guest") {

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


    public function __construct($content)
    {
        $this->content = $content;
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

        $dom = new DOMDocument();
        $dom->loadHTML($this->content);
        return $dom->getElementsByTagName("title")->item(0)->nodeValue;
    }

    public function html()
    {
        $dom = new DOMDocument();
        $dom->loadHTML($this->content);

        $xpath = new DOMXPath($dom);
        // Extract all HTML content within the <body> tag
        $bodyContent = $xpath->query('//body')->item(0)->childNodes;

        $htmlElements = [];
        foreach ($bodyContent as $node) {
            $htmlElements[] = $dom->saveHTML($node);
        }

        return implode("\n", $htmlElements);
    }




    function script()
    {
        $dom = new DOMDocument();
        $dom->loadHTML($this->content);

        $xpath = new DOMXPath($dom);

        // Extract link elements for stylesheets
        $scriptElements = $xpath->query('//script');
        $scripts = [];
        foreach ($scriptElements as $link) {
            $scripts[] = $dom->saveHTML($link);
        }
        return implode("\n", $scripts);
    }



    public function style()
    {
        $dom = new DOMDocument();
        $dom->loadHTML($this->content);

        $xpath = new DOMXPath($dom);

        // Extract link elements for stylesheets
        $linkElements = $xpath->query('//link[@rel="stylesheet"]');
        $stylesheets = [];
        foreach ($linkElements as $link) {
            $stylesheets[] = $dom->saveHTML($link);
        }

        // Extract style tags
        $styleTags = $xpath->query('//style');
        $styles = [];
        foreach ($styleTags as $style) {
            $styles[] = $dom->saveHTML($style);
        }

        return implode("\n", array_merge($stylesheets, $styles));
    }

}