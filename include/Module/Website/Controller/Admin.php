<?php

namespace MerapiPanel\Module\Website\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use JonnyW\PhantomJs\Client;
use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;
use MerapiPanel\Utility\Util;
use MerapiPanel\Views\View;

class Admin extends __Controller
{

    function register()
    {

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

    function customize(Request $request)
    {
        return View::render("customize.html.twig", []);
    }
}