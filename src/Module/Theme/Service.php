<?php
namespace MerapiPanel\Module\Theme;

use MerapiPanel\Box;
use MerapiPanel\Utility\Util;
use Symfony\Component\Yaml\Yaml;

class Service
{
    private function getDirectory()
    {

        if (!isset($_ENV['APP'])) {
            throw new \Exception('Environment variable APP is not set');
        }

        $directory = $_ENV['APP'] . '/public/themes/';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return $directory;
    }



    private function resetToDefault()
    {
        $opt = Box::module("theme")->getOptions();
        $themes = $opt['themes']->toArray();

        $find = array_search("default", array_map(fn($item) => $item['name'], $themes));

        if ($find === false) {
            throw new \Exception('Default theme not found');
        }
        $opt['theme_id'] = $find;
    }



    public function getActive()
    {
        $opts = Box::module("theme")->getOptions();
        if (!isset($opts["theme_id"])) {
            $this->resetToDefault();
            return $this->getActive();
        }
        $theme_id = $opts["theme_id"];
        if (!isset($opts['themes'])) {
            $opts['themes'] = [];
            $this->scan($opts['themes']);
            return $this->getActive();
        }

        if (!isset($opts['themes'][$theme_id])) {
            $this->resetToDefault();
            return $this->getActive();
        }

        return $this->getThemeById($theme_id);
    }




    public function getThemeById($id)
    {

        $opts = Box::module("theme")->getOptions();

        if (!isset($opts["themes"])) {
            $opts["themes"] = [];
            $this->scan($opts["themes"]);
            return $this->getThemeById($id);
        }

        $themes = $opts["themes"];
        if (!isset($themes[$id])) {
            throw new \Exception("Theme with id \"{$id}\" not found");
        }

        $theme = $opts['themes'][$id]->toArray(); // convert to array to prevent update options
        $theme['id'] = $id;

        $default_thumbnail = Box::module("fileManager")->service("AssetsService")->url("@theme/img/placeholder-image.jpg");

        $name = basename($theme['dirname']);
        $file_info = rtrim($theme['dirname']) . "/theme.yml";
        $info = [];

        $theme['thumbnail'] = $default_thumbnail;
        if (file_exists(rtrim($theme['dirname']) . "/thumbnail.jpg")) {
            $theme["thumbnail"] = "/public/themes/" . $name . "/thumbnail.jpg";
        }
        if (file_exists($file_info)) {
            $info = Yaml::parseFile($file_info) ?? [];
        }

        return array_merge($theme, $info);
    }


    public function scan(&$themes = [])
    {
        $stack = [];
        foreach (glob('' . $this->getDirectory() . '*') as $file) {

            $name = basename($file);
            $stack[] = [
                "dirname" => $file,
                "name" => $name,
            ];
        }

        foreach ($stack as $key => $item) {

            if (array_search($item['dirname'], array_map(fn($item) => $item['dirname'], $themes->toArray())) == false) {
                $themes[Util::uniqReal(16, "")] = $item;
                continue;
            }

        }
    }


    public function fetchAll()
    {

        $options = Box::module("theme")->getOptions();

        if (!isset($options["themes"])) {
            $options["themes"] = [];
        }
        $themes = &$options["themes"];
        if (count($themes) <= 0) {
            $this->scan($themes);
        }

        $output = [];
        foreach ($themes as $key => $theme) {

            if (!isset($theme['dirname']) || (isset($theme['dirname']) && !is_dir($theme['dirname']))) {
                $this->scan($themes);
                unset($themes[$key]);
                return $this->fetchAll();
            }

            $output[] = $this->getThemeById($key);
        }

        return $output;
    }
}