<?php

namespace MerapiPanel\Views;

use Symfony\Component\Filesystem\Path;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Yaml\Yaml;
use Throwable;

class Intl extends Translator
{
    public function __construct()
    {

        parent::__construct('en'); // Default locale is 'en'

        $intlLoaded = false;
        foreach (get_loaded_extensions() as $extension) {
            if (strtolower($extension) == "intl") {
                $intlLoaded = true;
                break; // No need to continue the loop once intl is found
            }
        }

        if ($intlLoaded) {
            $locale = locale_get_default();
            $locale = explode("_", $locale);

            if (isset ($locale[1])) {
                $this->setLocale(strtolower($locale[1]));
            }
        }

        $this->addLoader('yaml', new YamlFileLoader());
        $this->addLoader('yml', new YamlFileLoader());
        $this->addLoader('array', new ArrayLoader());
        $this->addLoader('json', new JsonFileLoader());
        $this->addResource('yaml', __DIR__ . '/locales/en.yaml', 'en');
    }


    public function scanResources($directory = null)
    {

        if (is_dir($directory . "/intl")) {

            foreach (glob($directory . "/intl.{yaml,yml,json}", GLOB_BRACE) as $file) {

                if (pathinfo($file, PATHINFO_EXTENSION) == "json") {
                    $this->addResource('json', $file, strtolower(basename($file, ".json")));
                } else if (pathinfo($file, PATHINFO_EXTENSION) == "yaml") {
                    $this->addResource('yaml', $file, strtolower(basename($file, ".yaml")));
                } else if (pathinfo($file, PATHINFO_EXTENSION) == "yml") {
                    $this->addResource('yaml', $file, strtolower(basename($file, ".yml")));
                }
            }

        } else {

            foreach (glob($directory . "/intl.{yaml,yml,json}", GLOB_BRACE) as $file) {

                try {
                    if (pathinfo($file, PATHINFO_EXTENSION) == "json") {
                        $locale = json_decode(file_get_contents($file), true);
                    } else if (pathinfo($file, PATHINFO_EXTENSION) == "yaml") {
                        $locale = Yaml::parseFile($file);
                    } else if (pathinfo($file, PATHINFO_EXTENSION) == "yml") {
                        $locale = Yaml::parseFile($file);
                    }

                    if ($locale && is_array($locale)) {
                        foreach ($locale as $key => $value) {
                            if (!is_array($value))
                                continue;
                            $this->addResource("array", $value, $key);
                        }
                    }
                } catch (Throwable $e) {
                    error_log("ERROR IN LOADING LOCALE: " . $e->getMessage());
                }
            }
        }
    }

    function onViewFileLoaded($file)
    {

        $filename = basename($file, "." . pathinfo($file, PATHINFO_EXTENSION));
        $dirname = dirname($file);

        $intl_files = glob(Path::join($dirname, $filename . ".intl.{yaml,yml,json}"), GLOB_BRACE);

        if (isset ($intl_files[0])) {
            try {

                $locale = null;
                if (pathinfo($intl_files[0], PATHINFO_EXTENSION) == "json") {
                    $locale = json_decode(file_get_contents($intl_files[0]), true);
                } else if (pathinfo($intl_files[0], PATHINFO_EXTENSION) == "yaml") {
                    $locale = Yaml::parseFile($intl_files[0]);
                } else if (pathinfo($intl_files[0], PATHINFO_EXTENSION) == "yml") {
                    $locale = Yaml::parseFile($intl_files[0]);
                }

                if ($locale && is_array($locale)) {
                    foreach ($locale as $key => $value) {
                        if (!is_array($value))
                            continue;
                        $this->addResource("array", $value, $key);
                    }
                }
            } catch (Throwable $e) {
                error_log("ERROR IN LOADING LOCALE: " . $e->getMessage());
            }
        }

    }

}