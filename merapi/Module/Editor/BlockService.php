<?php
namespace MerapiPanel\Module\Editor {

    use MerapiPanel\Module\Editor\Component\Block;



    class BlockService
    {

        private array $stack;

        function __construct()
        {
            $this->stack = [];
        }

        public function getStack()
        {
            foreach (glob(__DIR__ . "/component/blocks/**/index.php") as $filename) {
                __loadBlockComponent($filename);
            }
            return $this->stack;
        }

        function registerBlockType(string $name, mixed $blockProps)
        {

            $this->stack[] = new Block($name, $blockProps);
        }
    }

}

namespace {

    use MerapiPanel\Box;

    function registerBlockType(string $name, $block)
    {
        if (isset (debug_backtrace()[0]['file'])) {
            $dirname = dirname(debug_backtrace()[0]['file']);

            if (is_string($block)) {
                if (file_exists($block)) {
                    $block = json_decode(file_get_contents($block), true);
                } else {
                    $block = json_decode($block, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return;
                    }
                }

                foreach ($block as $key => $item) {
                    if (is_string($item) && strpos($item, 'file:') === 0) {
                        $file    = ltrim(pathinfo($item, PATHINFO_BASENAME), "\\/");
                        $path    = ltrim(realpath(substr($item, 5)), "\\/");
                        $dirname = trim(str_replace($_ENV['__MP_APP__'], '', $dirname), "\\/");
                        $block[$key] = "/".str_replace('\\', '/', (!empty ($path) ? ($dirname . "/" . $path) : $dirname) . "/dist/" . $file);
                    }
                }
            }

            Box::module("Editor")->service("block")->registerBlockType($name, $block);
        }
    }


    function __loadBlockComponent($file)
    {

        if (file_exists($file)) {
            include_once $file;
        }
    }
}