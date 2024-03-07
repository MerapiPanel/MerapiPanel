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


    private function matchOptThemes($theme_dirs = [])
    {

        $opt_themes = Box::module("theme")->getOptions();
        if (!isset($opt_themes["themes"])) {
            $opt_themes["themes"] = [];
        }

        if (count($opt_themes["themes"]) != count($theme_dirs)) {

            $themes_in_opt = array_keys($opt_themes["themes"]);
            $diff = array_diff(array_map(fn($item) => basename($item), $theme_dirs), $themes_in_opt);

            $themes = $opt_themes["themes"];

            foreach ($diff as $theme) {
                $themes[$theme] = [
                    "dirname" => $theme_dirs[array_search($theme, array_map(fn($item) => basename($item), $theme_dirs))],
                    "id" => Util::uniqReal(25, ""),
                ];
            }

            $opt_themes["themes"] = $themes;
        }
    }


    public function scan()
    {

    }


    public function getThemes()
    {

        $options = Box::module("theme")->getOptions();

        if (!isset($options["themes"])) {
            $options["themes"] = [];
        }

        $themes = &$options["themes"];

        foreach ($themes as $key => $theme) {

            if (
                (!isset($theme['basedir']) || !isset($theme['id']))
                ||
                (isset($theme['basedir']) && !is_dir($theme['basedir']))
            ) {

                error_log("Theme {$key} is not valid");
                unset($themes[$key]);
            }
        }

        return [];






        // $default_thumbnail = Box::module("fileManager")->service("AssetsService")->url("@theme/img/placeholder-image.jpg");

        // $theme_dirs = [];
        // $themes = [];
        // foreach (glob('' . $this->getDirectory() . '*') as $file) {

        //     $theme_dirs[] = $file;

        //     $name = basename($file);
        //     $file_info = $file . "/theme.yml";
        //     $info = [];

        //     if (file_exists($file_info)) {
        //         $info = Yaml::parseFile($file_info) ?? [];
        //     }
        //     if (file_exists($file . "/thumbnail.jpg")) {
        //         $info["thumbnail"] = "/public/themes/" . $name . "/thumbnail.jpg";
        //     }

        //     $themes[] = array_merge([
        //         "name" => $name,
        //         "thumbnail" => $default_thumbnail,
        //         "description" => "",
        //     ], $info);
        // }

        // $this->matchOptThemes($theme_dirs);

        // return $themes;
    }


}