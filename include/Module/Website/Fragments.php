<?php

namespace MerapiPanel\Module\Website {

    use MerapiPanel\Box\Module\__Fragment;
    use MerapiPanel\Box\Module\Entity\Module;

    class Fragments extends __Fragment
    {
        protected $module;
        function onCreate(Module $module)
        {
            $this->module = $module;
        }

        function listpops()
        {
            $data = [];
            foreach (glob(__DIR__ . "/data/fragments/*.json") as $file) {
                $name = basename($file, ".json");
                $content = json_decode(file_get_contents($file) ?? "{}", 1);
                $data[] = [
                    "name" => $name,
                    ...(is_array($content) ? $content : [$content])
                ];
            }
            return $data;
        }
    }
}
