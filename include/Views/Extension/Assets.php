<?php

namespace MerapiPanel\Views\Extension;

use MerapiPanel\Box;
use MerapiPanel\Views\Abstract\Extension;

class Assets extends Extension
{

    function fl_assets($path)
    {

        return Box::module("FileManager")->Assets->url(ltrim($path, "\\/"));
    }

    /**
     * @option needs_environment true
     */
    public function fn_style_tag(\Twig\Environment $env, string $template): \Twig\Markup
    {
        $content = $env->render($template);

        // Embed the template inside a <style> tag and return as Twig Markup
        $styleTag = "<style>\n$content\n</style>";
        return new \Twig\Markup($styleTag, $env->getCharset());
    }
}