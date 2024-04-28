<?php

namespace MerapiPanel\Module\Setting;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Module\Setting\ViewParser;
use MerapiPanel\Views\View;
use Symfony\Component\Filesystem\Path;

class Service extends __Fragment
{

    protected $module;
    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {

        View::getInstance()->getTwig()->addTokenParser(new ViewParser());
        $this->module = $module;

        $panel = Box::module('Panel');
        $module_configs = [];
        foreach (glob(Path::canonicalize(__DIR__ . "/..") . "/*/config.json") as $config) {

            $directory = dirname($config);
            $module = basename($directory);
            $icons = glob($directory . "/icon.{png,jpg,jpeg,svg}", GLOB_BRACE);

            $module_configs[] = [
                "module" => $module,
                "icon" => $icons ? $icons[0] : null,
                "config" => json_decode(file_get_contents($config), true)
            ];

            $panel->addMenu([
                "name" => ucfirst($module),
                "link" => $_ENV['__MP_ADMIN__']['prefix'] . "/settings/config/" . strtolower($module),
                "icon" => $icons ? $icons[0] : null,
                "parent" => "Configuration"
            ]);
        }
        if(!empty($module_configs)){
            $panel->addMenu([
                "name" => "Configuration",
                "parent" => "Settings",
            ]);
        }
    }


    public function getTimeZones()
    {
        $raw = file_get_contents(__DIR__ . "/assets/timezone.json");
        return json_decode($raw, true);
    }
}
