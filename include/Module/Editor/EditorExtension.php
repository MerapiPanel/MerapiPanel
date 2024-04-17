<?php
namespace MerapiPanel\Module\Editor;

use MerapiPanel\Views\Abstract\Extension;

class EditorExtension extends Extension
{

    /**
     * @param array $config
     * @option needs_environment true
     */
    function fn_editor_config(\Twig\Environment $env, $config = [])
    {

        $config_json = json_encode($config);

        $content = <<<HTML
        <script type="text/javascript">
            const editor = $config_json;
            window.editor = editor;
        </script>
        HTML;

        return new \Twig\Markup($content, $env->getCharset());
    }
}