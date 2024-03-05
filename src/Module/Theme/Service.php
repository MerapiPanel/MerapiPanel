<?php
namespace MerapiPanel\Module\Theme;
use MerapiPanel\Box;

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


    public function getThemes()
    {

        $default_thumbnail = Box::module("fileManager")->service("AssetsService")->url("@theme/img/placeholder-image.jpg");

        $themes = [];
        foreach (glob('' . $this->getDirectory() . '*') as $file) {

            $name = basename($file);
            $file_info = $file . "/theme.info";
            $info = [];

            $themes[] = array_merge([
                "name" => basename($file),
                "thumbnail" => $default_thumbnail,
                "description" => "",
            ], $info);
        }
        return $themes;
    }
}