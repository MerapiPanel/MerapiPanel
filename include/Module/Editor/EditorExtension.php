<?php
namespace MerapiPanel\Module\Editor;

use MerapiPanel\Views\Abstract\Extension;

class EditorExtension extends Extension
{

    /**
     * @param array $option
     * @option needs_environment true
     */
    function fn_editor_option(\Twig\Environment $env, $options = [])
    {

        $options_json = json_encode($options);

        $content = <<<HTML
        <script type="text/javascript">
            window.editor = {};
            window.editor.options = $options_json;
        </script>
        HTML;

        return new \Twig\Markup($content, $env->getCharset());
    }
}