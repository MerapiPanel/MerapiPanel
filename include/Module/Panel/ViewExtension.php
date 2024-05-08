<?php
namespace MerapiPanel\Module\Panel;

use MerapiPanel\Views\Abstract\Extension;

class ViewExtension extends Extension
{

    /**
     * @param array $config
     * @option needs_environment true
     */
    function fn_panel_render(\Twig\Environment $env, $html = "")
    {
        // Render the Twig template
        $result = $env->createTemplate($html)->render([]);
        // Return the rendered Twig markup
        return new \Twig\Markup($result, $env->getCharset());
    }
}