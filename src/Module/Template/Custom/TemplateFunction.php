<?php

namespace MerapiPanel\Module\Template\Custom;

use MerapiPanel\Core\View\Abstract\FunctionAbstract;

class TemplateFunction extends FunctionAbstract
{


    function create_style($value)
    {
        return "<style>" . $value . "</style>";
    }

    function getAssets() {

        $assetsFiles = realpath(__DIR__ . "/../assets.json");
       
        if(!file_exists($assetsFiles)) {
            return [];
        }

        $stack = [];
        $assets = json_decode(file_get_contents($assetsFiles), true);
        foreach($assets as $asset) {
            $extension = pathinfo($asset, PATHINFO_EXTENSION);
            if($extension == "css") {
                $stack[] = "<link rel=\"stylesheet\" href=\"{$asset}\">";
            }
            if($extension == "js") {
                $stack[] = "<script src=\"{$asset}\"></script>";
            }
        }
        return $stack;
    }
}
