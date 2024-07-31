<?php

namespace MerapiPanel\Module\Website {

    use Exception;
    use MerapiPanel\Box\Module\__Fragment;
    use MerapiPanel\Box\Module\Entity\Module;
    use MerapiPanel\Utility\Util;

    class Assets extends __Fragment
    {
        protected $module;
        function onCreate(Module $module)
        {
            $this->module = $module;
        }

        
        function listpops()
        {
            $file = __DIR__ . "/data/assets.json";
            if (file_exists($file))
                return  json_decode(file_get_contents($file) ?? "{}", 1) ?? [];
            else return [];
        }


        function addpop($name, $type)
        {
            if (empty($name)) throw new Exception("Please enter asset name", 401);
            if (!in_array($type, ["style", "link", "link:style", "script"])) throw new Exception("Please enter asset name", 401);

            $realType = preg_replace("/\:.*$/", "", $type);
            $minimal = [
                "link" => [
                    "name" => "",
                    "id"   => "",
                    "attributes" => []
                ],
                "link:style" => [
                    "name" => "",
                    "id"   => "",
                    "attributes" => [
                        [
                            "key"   => "rel",
                            "value" => "stylesheet",
                        ],
                        [
                            "key"   => "href",
                            "value" => "",
                        ]
                    ]
                ],
                "style" => [
                    "name" => "",
                    "id"   => "",
                    "content" => ""
                ],
                "script" => [
                    "name" => "",
                    "id"   => "",
                    "attributes" => [],
                    "content"    => ""
                ]
            ];

            $file = __DIR__ . "/data/assets.json";
            $data = $this->listpops();
            $data[] = [
                ...$minimal[$type],
                ...[
                    "id" => Util::uniq(12),
                    "name" => $name,
                    "type" => $realType
                ]
            ];
            file_put_contents($file, json_encode(array_values($data)));
        }


        function savepop($id, $name, $attributes, $content, $type)
        {

            $file = __DIR__ . "/data/assets.json";
            $data = $this->listpops();

            $index = array_search($id, array_column($data, "id"));
            if ($index < 0) throw new Exception("Asset not found!", 404);

            $asset = $data[$index];
            $keys = array_keys($asset);
            if (in_array("attributes", $keys)) {
                $asset['attributes'] = !is_array($attributes) ? json_decode($attributes, 1) : $attributes;
            }
            if (in_array("content", $keys)) {
                $asset['content'] = trim(preg_replace("/\s+|\r\n/", " ", $content));
            }
            if ($name != $asset['name']) {
                $asset['name'] = $name;
            }
            $data[$index] = $asset;
            file_put_contents($file, json_encode(array_values($data)));
        }


        function rmpop($id)
        {
            $file = __DIR__ . "/data/assets.json";
            $data = $this->listpops();

            $index = array_search($id, array_column($data, "id"));
            if ($index < 0) throw new Exception("Asset not found!", 404);
            unset($data[$index]);
            file_put_contents($file, json_encode(array_values($data)));
        }
    }
}
