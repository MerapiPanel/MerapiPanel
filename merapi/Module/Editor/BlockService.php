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

        function registerBlockType(string $name, array $blockProps)
        {

            $this->stack[] = new Block($name, $blockProps);
        }
    }

}

namespace {

    use MerapiPanel\Box;

    function registerBlockType(string $name, $block)
    {
        Box::module("Editor")->service("block")->registerBlockType($name, $block);
    }


    function __loadBlockComponent($file)
    {

        if (file_exists($file)) {
            include_once $file;
        }
    }
}