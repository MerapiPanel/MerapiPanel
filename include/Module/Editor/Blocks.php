<?php

namespace MerapiPanel\Module\Editor {

    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\__Fragment;
    use MerapiPanel\Box\Module\Entity\Module;
    use MerapiPanel\Utility\Util;
    use Symfony\Component\Filesystem\Path;

    class Blocks extends __Fragment
    {

        protected $module;
        private $styles = "";

        function onCreate(Module $module)
        {
            $this->module = $module;
        }

        function getBlocks()
        {
            $module_dirname = Path::canonicalize(Path::join(__DIR__, ".."));

            $blocks = [];
            foreach (glob(Path::join($module_dirname, "**", "Blocks", "*", "index.php")) as $block_file) {

                $data = require_once($block_file);
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

        private function defaultRender($tagName = "div", $attributes = [], $content = "")
        {
            $attribute = (!empty($attributes) ? " " : "") . implode(" ", array_map(fn ($k) => "$k=\"$attributes[$k]\"", array_keys($attributes)));
            return "<$tagName{$attribute}>{$content}</$tagName>";
        }

        private function renderResolve($component = [])
        {

            if (gettype($component) === "string")
                return $component;


            if (isset($component['tagName'])) {
                $className = isset($component['classes']) ? implode(" ", $component['classes']) : null;
                $attribute = isset($component['attributes']) ? implode(" ", array_map(function ($attr) use ($component) {
                    if (isset($component['attributes'][$attr])) {
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


        function render($components = [])
        {

            if (gettype($components) === "string")
                return $components;

            $resolve_namespace = [
                "bs" => "Editor",
            ];

            $rendered = [];
            foreach ($components as $key => $component) {

                $type = $component['type'] ?? null;
                if (isset($component["attributes"]["style"])) {
                    $selector = "";
                    if (!isset($component["attributes"]["id"])) {
                        $component["attributes"]["id"] = Util::uniq(46);
                    }
                    $selector .= "#" . $component["attributes"]["id"];
                    if (isset($component["attributes"]["classes"])) {
                        $selector .= "." . implode(".", $component["attributes"]["classes"]);
                    }

                    $this->styles .= "$selector{" . $component['attributes']['style'] . "}";
                    unset($component["attributes"]["style"]);
                }



                if (!$type) {
                    $rendered[] = $this->renderResolve($component);
                    continue;
                }


                if ($type === "textnode") {
                    $rendered[] = $component["content"];
                    continue;
                }
                if ($type === "text") {
                    $rendered[] = $this->defaultRender(...[
                        "tagName"    => "span",
                        "attributes" => $component['attributes'] ?? [],
                        "content"    =>  isset($component['content']) ? $component['content'] : $this->render($component['components'] ?? [])
                    ]);
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
                    $rendered[] = $this->defaultRender(...[
                        "attributes" => $component['attributes'] ?? [],
                        "content" =>  isset($component['content']) ? $component['content'] : $this->render($component['components'] ?? [])
                    ]);
                    continue;
                }
                $rendered[] = blockContext($component, $fragment, $key);
            }
            $output = implode("", $rendered);
            return $output;
        }

        function getStyles()
        {
            return $this->styles;
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
