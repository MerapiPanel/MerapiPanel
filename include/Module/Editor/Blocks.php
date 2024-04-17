<?php
namespace MerapiPanel\Module\Editor {

    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\__Fragment;
    use Symfony\Component\Filesystem\Path;

    class Blocks extends __Fragment
    {
        protected $module;
        function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
        {
            $this->module = $module;

        }

        function getBlocks()
        {
            $module_dirname = Path::canonicalize(Path::join(__DIR__, ".."));

            $blocks = [];
            foreach (glob(Path::join($module_dirname, "**", "Blocks", "*", "index.php")) as $block_file) {

                $data = require_once ($block_file);
                if (!is_array($data)) {
                    continue;
                }

                $data = array_combine(array_keys($data), array_map(function ($item) use ($block_file) {
                    if (is_string($item) && strpos($item, "file:.") === 0) {
                        $item = str_replace("file:.", str_replace(Path::normalize($_ENV['__MP_CWD__']), "", Path::normalize(dirname($block_file))), $item);

                    }
                    return $item;
                }, array_values($data)));

                $blocks = array_merge($blocks, [$data]);
            }

            return $blocks;
        }


        function render($components = [])
        {
            $resolve_namespace = [
                "bs" => "Editor",
            ];

            $rendered = [];
            foreach ($components as $component) {

                $type = $component['type'] ?? null;


                if (!$type) {
                    if (isset($component['tagName'])) {
                        if (isset($component['content'])) {
                            $rendered[] = "<{$component['tagName']}>{$component['content']}</{$component['tagName']}>";
                        } else {
                            $rendered[] = "<{$component['tagName']}/>";
                        }
                    } else {
                        $rendered[] = "";
                    }
                    continue;
                }

                if ($type === "textnode") {
                    $rendered[] = $component['content'];
                    continue;
                }




                if (count(explode('-', $type)) > 1) {

                    preg_match("/\w+/i", $type, $matches);
                    if (empty($matches)) {
                        $rendered[] = "<div class='text-center py-3'>Unknown type: {$type}</div>";
                        continue;
                    }
                    $module = ucfirst($resolve_namespace[$matches[0]] ?? $matches[0]);
                    $blockName = trim(str_replace($matches[0], "", $type), '-');

                } else {

                    $module = "Editor";
                    $blockName = $type;
                }

                $fragment = Path::join(Box::module($module)->path, "Blocks", $blockName, "render.php");
                if (!file_exists($fragment)) {
                    $rendered[] = "<div class='text-center border border-warning bg-dark bg-opacity-10 py-3'>Block $module:$blockName</div>";
                }
                $rendered[] = blockContext($component, $fragment);

            }
            return implode("", $rendered);
        }
    }
}





namespace {

    use MerapiPanel\Box;

    function renderComponents($components = [])
    {
        return Box::module("Editor")->Blocks->render($components);
    }

    function blockContext($component, $fragment)
    {

        extract($component);

        ob_start();
        include $fragment;
        return ob_get_clean();
    }
}