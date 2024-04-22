<?php
namespace MerapiPanel\Module\Editor {

    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\__Fragment;
    use Symfony\Component\Filesystem\Path;

    class Blocks extends __Fragment
    {

        protected $replace = [];
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


        private function renderResolve($component = [])
        {

            if (gettype($component) === "string")
                return $component;


            if (isset($component['tagName'])) {
                $className = isset($component['classes']) ? implode(" ", $component['classes']) : null;
                $attribute = isset($component['attributes']) ? implode(" ", array_map(function ($attr) use ($component) {
                    if (isset ($component['attributes'][$attr])) {
                        return "{$attr}=\"{$component['attributes'][$attr]}\"";
                    }
                    return $attr;
                }, array_keys($component['attributes']))) : null;

                if (isset($component['content'])) {
                    return "<{$component['tagName']}" . ($className ? " class='$className'" : '') . " {$attribute}>{$component['content']}</{$component['tagName']}>";
                } else if (isset($component['components'])) {
                    return "<{$component['tagName']} " . ($className ? " class='$className'" : '') . " {$attribute}>{$this->render($component['components'])}</{$component['tagName']}>";
                } else {
                    return "<{$component['tagName']} " . ($className ? " class='$className'" : '') . " {$attribute}/>";
                }


            } else if (isset($component['components'])) {
                $component['type'] = "editor-group";
                return $this->render([$component]);
            }
        }


        function render($components = [], $replacer = [])
        {
            if (gettype($components) === "string")
                return $components;

            if (!empty($replacer)) {
                $this->replace = $replacer;
            }
            $resolve_namespace = [
                "bs" => "Editor",
            ];

            $rendered = [];
            foreach ($components as $key => $component) {

                $type = $component['type'] ?? null;


                if (!$type) {
                    $rendered[] = $this->renderResolve($component);
                    continue;
                }

                if (isset($this->replace[$type])) {
                    $rendered[] = is_array($this->replace[$type]) ? $this->renderResolve($this->replace[$type]) : $this->replace[$type];
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
                $rendered[] = blockContext($component, $fragment, $key);


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

    function blockContext($component, $__fragment, $__index = 0)
    {

        extract($component);
        if (!isset($attributes)) {
            $attributes = [];
        } else {
            $attributes = array_map(function ($item) {
                if (is_object($item)) {
                    $item = implode(";", json_decode(json_encode($item), true));
                } else if (is_array($item)) {
                    $item = implode(";", $item);
                }
                return $item;
            }, $attributes);
        }
        if (!isset($classes)) {
            $classes = [];
        }
        $className = null;
        if (count($classes)) {
            $className = implode(" ", $classes);
        }

        if (!isset($components)) {
            $components = [];
        }

        ob_start();
        include $__fragment;
        return ob_get_clean();
    }
}