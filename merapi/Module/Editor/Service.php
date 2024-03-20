<?php
namespace MerapiPanel\Module\Editor;


class Service {

    public static function getInitialScript()
    {
        return json_decode(file_get_contents(__DIR__ . '/initial-scripts.json'), true);
    }
}