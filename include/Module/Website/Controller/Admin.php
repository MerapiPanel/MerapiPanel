<?php

namespace MerapiPanel\Module\Website\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;
use MerapiPanel\Views\View;

class Admin extends __Controller
{

    function register()
    {

        if ($this->module->getRoles()->isAllowed(0)) {
            $customize = Router::GET("website/customize", "customize", self::class);
            Box::module("Panel")->addMenu([
                "name" => "Website",
                "icon" => "fa-solid fa-globe",
                "children" => [
                    [
                        "name" => "Customize",
                        "icon" => "fa-solid fa-paint-roller",
                        "link" => $customize
                    ]
                ]
            ]);
        }
    }

    function customize(Request $request)
    {
        return View::render("customize.html.twig", []);
    }
}