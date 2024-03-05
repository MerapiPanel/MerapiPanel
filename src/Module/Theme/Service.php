<?php
namespace MerapiPanel\Module\Theme;

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

        $themes = [];
        foreach (glob('' . $this->getDirectory() . '*') as $file) {
            $themes[] = basename($file);
        }
        return $themes;
    }
}